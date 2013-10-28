<?php  
/*
 * 客户端的测试程序, 向一主机的某个端口发送数据$msg, 并等待服务器的响应信息
 * */
set_time_limit(0);  
  
//$host = "192.168.17.61";  
$host = "localhost";
$port = "4444";  
$msg = "192.168.17.61";
$socket = socket_create(AF_INET, SOCK_STREAM, 0)or die("Could not create  socket\n"); 
   
$connection = socket_connect($socket, $host, $port) or die("Could not connet server\n"); 
socket_write($socket, $msg) or die("Write failed\n"); 

while ($buff = socket_read($socket, 1024, PHP_NORMAL_READ)) {  
    echo("Response was:" . $buff . "\n");  
}  
socket_close($socket);  
?>
