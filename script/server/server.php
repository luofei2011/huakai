<?php 
//$host = "192.168.17.61";
$host = "localhost";
$port = "4444";

set_time_limit(0);
// 创建socket连接
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('Could not create socket\n');
// 绑定主机和端口
$result = socket_bind($socket, $host, $port) or die('Could not bind to socket\n');
  
// 开始监听
$result=socket_listen($socket,3) or die("Could not set up socket listener\n");  

echo "Bindling the socket on $host:$port...\n";

// 持续监听该端口的数据请求
do {
    // 接收请求并调用一个子连接Socket来处理客户端和服务器的信息
    $spawn=socket_accept($socket) or die("Could not accept incoming connection\n");  
    echo "Read client data\n";
    // 读取客户端数据
    $input=socket_read($spawn, 8192) or die("Could not read input\n");  

    // 格式化输入的数据
    $input=trim($input);  
    echo "Received data: $input \n";

    // 给客户端的响应信息
    $output = "response OK!!!\n";
    socket_write($spawn,$output,strlen($output)) or die("Could not write output\n");  

    // 一旦信息返回成功则关掉子Socket
    socket_close($spawn);

    // 后续会在这里把客户端发送的数据进行存数据库操作
} while(true);

socket_close($socket);
?>
