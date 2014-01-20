<?php
require_once('analyze.php');
$fd = fopen("file/test", "r");
while(!feof($fd)) {
    new Analyze(fgets($fd));
}
fclose($fd);
?>
