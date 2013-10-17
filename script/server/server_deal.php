<?php 
//$host = "192.168.17.61";
$host = "localhost";
$port = "4444";
$isUpdate = "";
$isEnd = false;
// 断点续传的依据, 从这个地方开始传输
$line = 0;

set_time_limit(0);
ob_implicit_flush();
// 创建socket连接
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('Could not create socket\n');
// 绑定主机和端口
$result = socket_bind($socket, $host, $port) or die('Could not bind to socket\n');
  
// 开始监听
$result=socket_listen($socket) or die("Could not set up socket listener\n");  

echo "Bindling the socket on $host:$port...\n";

// 持续监听该端口的数据请求
do {
    // 接收请求并调用一个子连接Socket来处理客户端和服务器的信息
    $spawn=socket_accept($socket) or die("Could not accept incoming connection\n");  
    echo "Read client data\n";
    // 读取客户端数据
    if (file_exists("../file/update.s19")) {
        echo "NEW VERSION\n";
        $isUpdate = "NEW VERSION\n";
    } else {
        echo "NO UPDATE\n";
        $isUpdate = "NO UPDATE\n";
    }

    while ($input = socket_read($spawn, 1024, PHP_NORMAL_READ)) {  
        // 格式化输入的数据
        $input=trim($input);  
        echo "Received data: $input \n";

        if ($input == "S-BMS GPRS") {
            $output = $isUpdate;
            socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");  
        } else if ($input == "END") {
            $output = "BYE\n";
            socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");  
        } else if ($input == "OK") {
            $line += 1;
            $output = get_file_line($line);
            if ($output) {
                socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");  
            } else {
                $output = "END\n";
                socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");  
            }
        } else if ($input == "ERROR") {
            // 断点续传
            $output = get_file_line($line);
            socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");  
        } else if ($input == "BYE") {
            //$output = "END SOCKET";
            $isEnd = true;
        }
    }
    if ($isEnd) {
        socket_close($spawn);
    }
} while(true);
socket_close($socket);

function get_file_line($line_num){
    $n = 0;
    $handle = fopen("../file/update.s19", "r");
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
?>
