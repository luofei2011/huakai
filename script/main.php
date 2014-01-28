<?php
require_once('analyze.php');
$fd = fopen("file/test", "r");
while(!feof($fd)) {
    $line = fgets($fd);
    if (substr($line, 0, 4) == "18FF") {
        new Analyze($line);
    } else {
        echo "存在非法数据, 已跳过插入！\n";
    }
}
fclose($fd);
?>
