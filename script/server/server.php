<?php
// 涉及到数据库操作
require_once 'db_config.php';

// 打印信息相关
define('LOG_MSG', '[LOG]');
define('INFO_MSG', '[INFO]');
define('WARN_MSG', '[WARN]');
define('ERROR_MSG', '[ERROR]');

$host = "localhost";
//$host = "apms.hit.edu.cn";
$port = "10080";
// 参数初始化
$clientID = "";
$output = "";
$isUpdate = "";
$isEnd = false;
// 断点续传的依据, 从这个地方开始传输
$line = 0;
// BootLoader所需的codeSize, blockSize, blockNumber
$codesize = 0;
$blocksize = 32;
$blocknum = 0;
// 校验值
$checksum = 0;
// 计算s19文件codeSize所需的数据
//==============================================
$MCUAddressLimit = array(
    "DZ60_MAX" => 0xbdff,
    "DZ60_MIN" => 0x1900,
    "XEP100_UNPAGE_MAX" => 0xFFFF,
    "XEP100_UNPAGE_MIN" => 0xC000,
    "XEP100_MAX" => 0xFFFFFF,
    "XEP100_MIN" => 0xFE8000
);

$AddressLimitMax = $MCUAddressLimit['DZ60_MAX'];
$AddressLimitMin = $MCUAddressLimit['DZ60_MIN'];

$AddrXEPUnpageMax = 0xffff;
$AddrXEPUnpageMin = 0xC000;
//===============================================

set_time_limit(0);
ob_implicit_flush();
//setsockopt(listenfd, SOL_SOCKET, SO_REUSEADDR, &opt, sizeof(&opt));
// 创建Socket连接
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket!\n");
// 绑定主机和端口
$binding = socket_bind($socket, $host, $port) or die("Could not bind to socket!\n");
print_screen(LOG_MSG, "Binding the socket on $host:$port......");
// 开始监听
$listening = socket_listen($socket, 5) or die("Could not set up socket listener!\n");
print_screen(LOG_MSG, "Start listening on $host:$port......");
// 设置时区
date_default_timezone_set("PRC");

// 持续监听该端口的数据请求
do {
    // 接收请求并调用一个子连接Socket来处理客户端和服务器的信息
    $spawn = socket_accept($socket) or die("Could not accept incoming connection!\n");
    print_screen(LOG_MSG, "Reading Client data......");
    // 读取客户端数据
    while ($input = socket_read($spawn, 1024)) {
        // 判断是否有更新(因为服务器端的脚本会一直运行，因此客户端每次连接时都要判断更新文件是否存在)
        // 格式化输入的数据
        $input = str_replace("\n", "", $input);
        print_screen(INFO_MSG, "Received data: $input");
        if (substr($input, 0, 10) == "S-BMS GPRS") {
            print_screen(INFO_MSG, "Client is connect at " . date("Y-m-d H:i:s"));
            // 将SIM卡的唯一ID存为客户端ID，以便识别是哪辆车发送的数据
            $clientID = substr($input, 11, 27);
            print_screen(LOG_MSG, "Client ID is: $clientID");

            // 不需要单独抽为函数了,后面不会用到
            if ($df = opendir("../file/")) {
                while(($f = readdir($df)) !== false) {
                    if ($f != "." && $f != "..") {
                        $fExt = end(explode(".", $f));
                        $fLen = strpos($f, ".");
                        $fName = substr($f, 0, $fLen);
                        // 针对是否重复更新的问题,可以把已更新的文件ID和更新文件时间模块等绑定
                        // 针对更新补重复问题，建议每次更新的时候文件名上追加版本号！！！
                        if ($fExt== "s19" && !query_cid_is_updated($clientID, $fName)) {
                            // 有文件需要更新
                            print_screen(LOG_MSG, "NEW VERSION $fName");
                            $isUpdate = "NEW VERSION " . $fName . "\n";
                            // 每次只更新一个文件,因此当遇到一个文件以后就退出当前循环
                            break;
                        }
                    }
                }
                if (!$fName) {
                    print_screen(LOG_MSG, "NO UPDATE");
                    $isUpdate = "NO UPDATE\n";
                }
                closedir($df);
            }
            $output = $isUpdate;
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 3) == "END") {
                $output = "BYE\n";
                // TODO 暂时存为文件，后面更新为数据库(可先制定好数据库的格式)
                socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
                // 存入updated_file中, 已id做索引可减少遍历的次数
                //store_content_into_file("updated_file", $clientID . " " . $fName . "\n");
                store_update_info_to_db($clientID, $fName);
                $isEnd = true;
                print_screen(LOG_MSG, "Client is disconnect!");
        } else if (substr($input, 0, 8) == "CODESIZE") {
            // 行数首先归0；因为如果校验失败，则重头开始传
            $line = 0;
            $codesize = count(AnalyzerFile("../file/" . $fName . ".s19"));
	        $codesize = create_codesize($codesize);
            $output = "CODESIZE:" . $codesize . "\n";
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 9) == "BLOCKSIZE") {
            $output = "BLOCKSIZE:" . $blocksize . "\n";
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 8) == "BLOCKNUM") {
            if($codesize%32 == 0) {
                $blocknum = intval($codesize/32);
            } else {
                $blocknum = intval($codesize/32) + 1;
            }
	        $blocknum = create_blocknum($blocknum);
            $output = "BLOCKNUM:" . $blocknum . "\n";
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 2) == "OK") {
            // Sleep2秒等待can通信
            sleep(2);
            $line += 1;
            $linedata = get_file_line($fName, $line);
            if ($linedata) {
                //每行只发address+data, 不足32位以FF补齐
		        $type = substr($linedata, 0, 2);
                if($type == "S1") { //判断是S1开头还是S3开头
                    $address = substr($linedata, 4, 4); //S1开头,地址4位
                    $data_length = hexdec(substr($linedata, 2, 2) - 3) * 2;
                    $data = substr($linedata, 8, $data_length);
                    if(substr($address, 0, 2) == "FF") { //地址以FF开头的不要
                        $output = "USELESS" . "\n";
                                    socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");   
                    } else {
                        if($data_length < 64) { //不足64位的以FF补齐
                            for($i=0; $i<(64-$data_length); $i++) {
                                $data = $data."F";
                            }
                            for($j=0;$j<(64-$data_length)/2; $j++) { //校验值加FF
                                $checksum += hexdec("FF");
                            }						
                        }
                        $line = create_line_number($line);	
                        $address = create_address($address);	    
                        $output = "L".$line."A".$address."D".$data."\n";
                        socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");  
                    }                
                } else if ($type == "S2"){
			        $address = substr($linedata, 4, 6); //S2开头,地址6位
                    $data_length = hexdec(substr($linedata, 2, 2) - 4) * 2;
                    $output = "L".$line."A".$address."D".$data."\n";
                    socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");		
                } else if ($type == "S3"){
                    $address = substr($linedata, 4, 8); //S3开头,地址8位
                    $data_length = hexdec(substr($linedata, 2, 2) - 5) * 2;
                    $output = "L".$line."A".$address."D".$data."\n";
                    socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");				
		        } else {
                    $output = "USELESS" . "\n";
                    socket_write($spawn,$output,strlen($output)) or die("Could not write output\n"); 
		        }
            } else {
                $output = "OVER\n"; //发送完毕
                socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");
            }
        } else if ($input == "ERROR") { //客户端暂时不会回复ERROR
                // 断点续传
                //$output = get_file_line($line);
                //socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 8) == "VALIDATE") {
            // 客户端传来的验证总和
            $client_checksum = substr($input, 9, strlen($input) - 9);
	        print_screen(INFO_MSG, "CLIENT CHECKSUM: $client_checksum");
	        $checksum = dechex($checksum%65536);
	        print_screen(INFO_MSG, "SERVER CHECKSUM: $checksum");
            // 与本地checksum校验值比较
            $output = ($client_checksum == $checksum) ? "VALIDATE OK\n" : "VALIDATE ERROR\n";
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else {
            // 得到当前的日期
            $date = date("Y-m-d");
            // 把当天的文件都存到已时间命名的文件夹中
            if (!is_dir($date)) {
                mkdir($date);
            }
            $filename = $date . "/" . $clientID . '-' . $date;
            $fd = fopen($filename, "a");
            // 将收到的数据写入文件
            fwrite($fd, $input."\n");
            fclose($fd);
            // 结束本次链接
            $output = "OK\n";
            socket_write($spawn,$output,strlen($output)) or die("Could not write output"."\n");
        }
    }
    print_screen(LOG_MSG, "Client is disconnect at " . date("Y-m-d H:i:s"));
    if ($isEnd) {
        // 关闭当前连接
        socket_close($spawn);
    }
} while(true);
// 关闭Socket连接
socket_close($socket);

/**
   * 读取S19文件任意一行
   */
function get_file_line($f, $line_num){
    $n = 0;
    $handle = fopen("../file/" . $f . ".s19", "r");
    if ($handle) {
        while (!feof($handle)) {
            ++$n;
            $out = fgets($handle, 1024);
            if($line_num == $n)
                break;
        }
        fclose($handle);
    }

    if( $line_num == $n)
        return $out;
    return false;
}

/**
 * codesize补足6位
 */
function create_codesize($codesize){
    switch (strlen($codesize)) {
	case 1:
	    $codesize = "00000".$codesize;
	    break;
	case 2:
	    $codesize = "0000".$codesize;
	    break;
	case 3:
	    $codesize = "000".$codesize;
	    break;
	case 4:
	   $codesize = "00".$codesize;
	    break;
	case 5:
	   $codesize = "0".$codesize;
	   break;
    }
    return $codesize;
}

/**
 * blocknum补足4位
 */
function create_blocknum($blocknum){
    switch (strlen($blocknum)) {
	case 1:
	    $blocknum = "000".$blocknum;
	    break;
	case 2:
	    $blocknum = "00".$blocknum;
	    break;
	case 3:
	    $blocknum = "0".$blocknum;
	    break;
    }
    return $blocknum;
}

/**
 * 行数补足4位
 */
function create_line_number($line) {
    switch (strlen($line)) {
	case 1:
	    $line = "000".$line;
	    break;
	case 2:
	    $line = "00".$line;
	    break;
	case 3:
	    $line = "0".$line;
	    break;
    }
    return $line;
}

/**
 * 地址补足8位
 */
function create_address($address) {
    switch (strlen($address)) {
	case 4:
	    $address = "0000".$address;
	    break;
	case 6:
	    $address = "00".$address;
	    break;
    }
    return $address;	
}


/**
 * 存文件操作
 */
function store_content_into_file($f, $content) {
    $fd = fopen($f, 'a');
    // 自己管理是否添加换行等功能
    fwrite($fd, $content);
    fclose($fd);
}

/**
 * 到updated_fle文件中查找当前id的指定文件是否已经更新
 */
function find_id_is_updated($id, $name) {
    $fd = fopen("updated_file", "r");
    // 得到当前已经更新了的客户端信息和文件信息，文件一定要追加版本号
    $updated = fread($fd, filesize("updated_file"));
    // 根据存储的格式进行分割处理
    $arr = explode("\n", $updated);
    foreach($arr as $item) {
        $msg = explode(" ", $item);
        // 完全匹配的情况下说明当前文件对于这个id来说是已经更新过后的！
        if ($msg[0] == $id && $msg[1] == $name) {
            fclose($fd);
            return true;
        }
    }
    fclose($fd);
    return false;
}

/*
 * 到数据库中查询当前车次的当前文件是否已经更新
 * */
function query_cid_is_updated($cid, $version) {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query = "SELECT * FROM update_info WHERE cid='$cid' AND version='$version'";
    $result = mysqli_query($dbc, $query);
    if (!$result)
        print_screen(ERROR_MSG, "INSERT ERROR");
    $count = mysqli_num_rows($result);
    mysqli_close($dbc);
    print_screen(LOG_MSG, "QUERY SUCCESS");

    // 返回查询结果
    if ($count)
        return true;
    else 
        return false;
}

/*
 * 把每次每辆车的更新记录存到数据库
 * */
function store_update_info_to_db($cid, $version) {
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $query = "INSERT INTO update_info values('', '$cid', '$version')";
    $result = mysqli_query($dbc, $query);
    if (!$result)
        print_screen(ERROR_MSG, "INSERT ERROR");
    mysqli_close($dbc);
}

/*
 * 根据不同的类型打印提示信息
 * @param {Number} $type 信息类型:
 *      {
 *          1: LOG普通信息
 *          2: INFO信息
 *          3: WARN信息
 *          4: ERROR信息
 *      }
 * */
function print_screen($type, $msg) {
    echo $type . " " . $msg . "\n";
}

/**
 * 读取S19文件任意一行
 */
function AnalyzerFile($filename) {
    global $MCUAddressLimit;
    global $AddressLimitMax;
    global $AddressLimitMin;
    global $AddrXEPUnpageMax;
    global $AddrXEPUnpageMin;

    $s_data_byte16 = array(16);
    $data_len = 0;
    $tmp_bin_data = array();

    $s1s2s3_data = array();
    $handle = fopen($filename, "r");
    $s_data_byte = fread($handle, filesize($filename));
    fclose($handle);
    $s_data = array();
    $i;
    do {
        $data_len = strlen($s_data_byte);
        $data_len /= 16;
        $unzip_again = false;
        $s_data_string_sub = split("\r\n", $s_data_byte);
        $s_data_string_sub = array_filter($s_data_string_sub);
        try {
            $i = 0;
            $s_data[$i] = array();
            foreach($s_data_string_sub as $str) {
                $temp_data = $str;
                $aw = 0;
                $isContinue = false;
                switch($temp_data[1]) {
                    case '0':
                        $aw = 2;
                        $s_data[$i]['_Type'] = "S0";
                        break;
                    case "1":
                        $aw = 2;
                        $s_data[$i]['_Type'] = "S1";
                        break;
                    case "2":
                        $aw = 3;
                        $s_data[$i]['_Type'] = "S2";
                        break;
                    case "3":
                        $aw = 4;
                        $s_data[$i]['_Type'] = "S3";
                        break;
                    case "4":
                        $s_data[$i]['_Type'] = "S4";
                        $isContinue = true;
                        break;
                    case "5":
                        $s_data[$i]['_Type'] = "S5";
                        $isContinue = true;
                        break;
                    case "6":
                        $s_data[$i]['_Type'] = "S6";
                        $isContinue = true;
                        break;
                    case "7":
                        $aw = 4;
                        $isContinue = true;
                        $s_data[$i]['_Type'] = "S7";
                        break;
                    case "8":
                        $aw = 3;
                        $isContinue = true;
                        $s_data[$i]['_Type'] = "S8";
                        break;
                    case "9":
                        $aw = 2;
                        $isContinue = true;
                        $s_data[$i]['_Type'] = "S9";
                        break;
                    case "\0":
                        $isContinue = true;
                        break;
                    default:
                        $isContinue = true;
                        break;
                }
                if ($isContinue)
                    continue;
                //endregion
                // 把地址转换成十进制
                $s_data[$i]['_Count'] = hexdec(substr($temp_data, 2, 2));
                $s_data[$i]['_Address'] = hexdec(substr($temp_data, 4, $aw * 2));
                $s_data[$i]['_Checksum'] = hexdec(substr($temp_data, ($s_data[$i]['_Count'] + 1) * 2, 2));

                if ($AddrXEPUnpageMin == 0xff) {
                    if (($s_data[$i]['_Address'] >= 0xFFC0) && ($s_data[$i]['_Address'] < 0xffff)) {
                        $s_data[$i]['_Address'] &=  0x00ff;
                        $s_data[$i]['_Address'] |=  0xbd00;
                    }
                }

                $dl = $s_data[$i]['_Count'] - $aw - 1;
                if ($dl < 0) {
                    $dl = 0;
                }
                $s_data[$i]['_Data'] = array($dl);
                for ($j = 0; $j < $dl; $j++) {
                    $s_data[$i]['_Data'][$j] = hexdec(substr($temp_data, 2 + 2+ $aw * 2 + $j * 2, 2));
                }
                $s_data[$i]['_DataString'] = dechex($s_data[$i]['_Data']);
                $i += 1;
            }
        } catch(Exception $e) {
            $unzip_again = true;
        }
    } while($unzip_again);

    foreach($s_data as $rd) {
        if ($rd["_Type"] == "S1" || $rd["_Type"] == "S2" || $rd["_Type"] == "S3") {
            if (substr($filename, 8, 11) == "BMU") {
 		if (($rd['_Address'] >= $AddrXEPUnpageMax) || ($rd['_Address'] < $AddrXEPUnpageMin)) {
		    continue;
                }
            } else {
                if (($rd['_Address'] >= $AddressLimitMax) || ($rd['_Address'] < $AddressLimitMin)) {
                    continue;
		}                 
            }
	
	    array_push($s1s2s3_data, $rd);
            $count = 0;
            foreach($rd['_Data'] as $d) {
                if ($d == 0xff) {
                     $count += 1;
                 }
             }

            if ($count >= count($rd['_Data'])) {
                continue;
            }

            $tmp_addr = $rd['_Address'];
            $i = 0;
            foreach($rd['_Data'] as $d) {
                // 全部还原成十六进制
                $tmp_bin_data[dechex($tmp_addr + $i)] = dechex($d);
                $i += 1;
            }            
        }
    }
    //有效值求和，作为校验值
    //var_export($s1s2s3_data);
    global $checksum;
    foreach($s1s2s3_data as $dataArray) {
    	$checksum += array_sum($dataArray['_Data']);
    }
    return $tmp_bin_data;
}
?>
