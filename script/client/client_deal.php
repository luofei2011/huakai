<?php  
/*
 * 客户端的测试程序, 向一主机的某个端口发送数据$msg, 并等待服务器的响应信息
 * */
set_time_limit(0);  
  
//$host = "192.168.17.61";  
$host = "localhost";
$port = "4444";  
$msg = "S-BMS GPRS\n";
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
            $output = "END\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if ($buff == "NEW VERSION") {
            $output = "OK\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else if ($buff == "BYE") {
            $isEnd = true;
            // 传输完成或者没有更新的时候退出
            break;
        } else if ($buff == "END") {
            $output = "BYE\n";
            socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
        } else {
            if ($buff) {
                $err = append_to_update_file($buff);
                $output = $err ? "OK\n" : "ERROR\n";
                socket_write($socket, $output, strlen($output)) or die("Write failed\n"); 
            }
        }
    }  
    if ($isEnd) {
        break;
    }
    sleep(1);
}
socket_close($socket);

function append_to_update_file($data) {
    $fd = fopen("update.s19", "a");
    $err = fwrite($fd, $data . "\n");
    fclose($fd);
    return $err;
}
?>
