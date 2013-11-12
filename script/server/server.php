<?php
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
$blocksize = 20;
$blocknum = 0;
// 校验数组
$validateArray = array();
// 计算s19文件codeSize所需的数据
//==============================================
$MCUAddressLimit = array(
    "DZ60_MAX" => 0xffff,
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
// 创建Socket连接
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket!\n");
// 绑定主机和端口
$binding = socket_bind($socket, $host, $port) or die("Could not bind to socket!\n");
echo "Binding the socket on $host:$port.\n";
// 开始监听
$listening = socket_listen($socket, 5) or die("Could not set up socket listener!\n");
echo "Start listening on $host:$port.\n";
// 设置时区
date_default_timezone_set("PRC");

// 持续监听该端口的数据请求
do {
    // 接收请求并调用一个子连接Socket来处理客户端和服务器的信息
    $spawn = socket_accept($socket) or die("Could not accept incoming connection!\n");
    echo "Reading Client data......\n";
    // 读取客户端数据
    while ($input = socket_read($spawn, 1024)) {
        // 判断是否有更新(因为服务器端的脚本会一直运行，因此客户端每次连接时都要判断更新文件是否存在)
        if ($df = opendir("../file/")) {
            while(($f = readdir($df)) !== false) {
                if ($f != "." && $f != "..") {
                    $fExt = end(explode(".", $f));
                    // 针对是否重复更新的问题,可以把已更新的文件ID和更新文件时间模块等绑定
                    if ($fExt== "s19") {
                        $fLen = strpos($f, ".");
                        $fName = substr($f, 0, $fLen);

                        // 有文件需要更新
                        echo "NEW VERSION " . $fName . "\n";
                        $isUpdate = "NEW VERSION " . $fName . "\n";

                        // 每次只更新一个文件,因此当遇到一个文件以后就退出当前循
                        // 环
                        break;
                    }
                }
            }
            if (!$fName) {
                echo "NO UPDATE\n";
                $isUpdate = "NO UPDATE\n";
            }
            closedir($df);
        }
        // 格式化输入的数据
        $input = str_replace("\n", "", $input);
        echo "Received data: $input \n";
        if (substr($input, 0, 10) == "S-BMS GPRS") {
            echo "Client is connect at " . date("Y-m-d H:i:s") . "\n";
            // 将SIM卡的唯一ID存为客户端ID，以便识别是哪辆车发送的数据
            $clientID = substr($input, 11, 27);
            echo "Client ID is: " . $clientID . "\n";
            $output = $isUpdate;
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 3) == "END") {
                $output = "BYE\n";
                //TODO: 此时如果存在更新文件，则要将其删除
                socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
                $isEnd = true;
                echo "Client is disconnect!\n";
        } else if (substr($input, 0, 8) == "CODESIZE") {
            // 行数首先归0；因为如果校验失败，则重头开始传
            $line = 0;
            $codesize = count(AnalyzerFile("../file/" . $fName . ".s19"));
            $output = "CODESIZE: " . $codesize . "\n";
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 9) == "BLOCKSIZE") {
            $output = "BLOCKSIZE: " . $blocksize . "\n";
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 8) == "BLOCKNUM") {
            if($codesize%32 == 0) {
                $blocknum = intval($codesize%32);
            } else {
                $blocknum = intval($codesize%32) + 1;
            }
            $output = "BLOCKNUM: " . $blocknum . "\n";
            socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 2) == "OK") {
                // Sleep2秒等待can通信
                sleep(2);
                $line += 1;
                $s19data = get_file_line($fName, $line);
                if ($s19data) {
                    //每行只发address+data
                    if(substr($s19data, 0, 2) == "S3") { //判断是S1开头还是S3开头
                        //去掉前10位、后2位
                        $s19data = substr($s19data, 11, -2);
                        //TODO 将data存入校验数组
                        $output = "L" . $line . "S3" . $s19data . "\n";
                    } else {
                        //去掉前8位、后2位
                        $s19data = substr($s19data, 9, -2);
                        //TODO 将data存入校验数组
                        $output = "L" . $line . "S1" . $s19data . "\n";
                    }
                    socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");
                } else {
                    $output = "OVER\n"; //发送完毕
                    socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");
                }
        } else if ($input == "ERROR") { //客户端暂时不会回复ERROR
                // 断点续传
                //$output = get_file_line($line);
                //socket_write($spawn, $output, strlen($output)) or die("Could not write output\n");
        } else if (substr($input, 0, 8) == "VALIDATE") {
            //TODO 稍后增加校验算法，对比校验值
            $output = "VALIDATE OK\n";
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
    echo "Client is disconnect at " . date("Y-m-d H:i:s") . "\n";
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
 * 解析S19文件
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
                        break;
                    case "5":
                        $s_data[$i]['_Type'] = "S5";
                        break;
                    case "6":
                        $s_data[$i]['_Type'] = "S6";
                        break;
                    case "7":
                        $aw = 4;
                        $s_data[$i]['_Type'] = "S7";
                        break;
                    case "8":
                        $aw = 3;
                        $s_data[$i]['_Type'] = "S8";
                        break;
                    case "9":
                        $aw = 2;
                        $s_data[$i]['_Type'] = "S9";
                        break;
                    case "\0":
                        break;
                    default:
                        break;
                }
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
            if ($rd['_Address'] == 0xffb0) {
            }
            if ((($rd['_Address'] < $AddressLimitMax) && ($rd['_Address'] > $AddressLimitMin)) || (($rd['_Address'] < $AddrXEPUnpageMax) && ($rd['_Address'] > $AddrXEPUnpageMin))) {
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
    }
    return $tmp_bin_data;
}
?>
