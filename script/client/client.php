<?php  
/*
 * 客户端的测试程序, 向一主机的某个端口发送数据$msg, 并等待服务器的响应信息
 * */
set_time_limit(0);  
  
//$host = "192.168.17.61";  
$host = "localhost";
$port = "10080";  
$msg = "S-BMS GPRS 111111111111111\n";
$isEnd = false;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)or die("Could not create  socket\n"); 
   
$connection = socket_connect($socket, $host, $port) or die("Could not connet server\n"); 

// 每次异常退出之后都重传
if (file_exists("update.s19"))
    unlink("update.s19");

socket_write($socket, $msg, strlen($msg)) or die("Write failed\n"); 

while(true) {
    while ($buff = socket_read($socket, 1024, PHP_NORMAL_READ)) {  
        $buff = trim($buff);
        echo("Response was:" . $buff . "\n");  
        if ($buff == "NO UPDATE") {
            //$output = "END\n";
            // 换成发送一条数据
            $output = "id=1001&data=somedata\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 

            // 如果没有更新则等待60s后继续连接
            sleep(60);
        } else if (substr($buff, 0, 11) == "NEW VERSION") {
            $output = "CODESIZE\n";
            $updateFileName = substr($buff, 12, strlen($buff) - 12);
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if (substr($buff, 0, 8) == "CODESIZE") {
            $output = "BLOCKSIZE\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if (substr($buff, 0, 9) == "BLOCKSIZE") {
            $output = "BLOCKNUM\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if (substr($buff, 0, 8) == "BLOCKNUM") {
            $output = "OK\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if ($buff == "OVER") {
            $output = "VALIDATE:\n"; // 加上校验值
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if ($buff == "VALIDATE OK") {
            $output = "END\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if ($buff == "VALIDATE ERROR") {
            // 重新发送数据
        } else if ($buff == "BYE") {
            $isEnd = true;
            // 传输完成或者没有更新的时候退出
            //break;
            sleep(60);
        } else {
            if ($buff) {
                $err = append_to_update_file($updateFileName, $buff);
                $output = $err ? "OK\n" : "ERROR\n";
                socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
            }
        }
    }  
    if ($isEnd) {
        break;
    }
    //sleep(1);
}
socket_close($socket);

function append_to_update_file($f, $data) {
    $fd = fopen($f . ".s19", "a");
    $err = fwrite($fd, $data . "\n");
    fclose($fd);
    return $err;
}
?>
