<?php
require_once('analyze.php');
date_default_timezone_set("PRC");
$date = date("Y-m-d", strtotime($argv[1]));
if ($df = opendir("server/")) {
    while(($f = readdir($df)) !== false) {
        if ($f != "." && $f != "..") {
            // 处理给定日期的数据
            if ($f == $argv[1]) {
                $dirf = opendir("server/" . $f . "/");
                while(($txt = readdir($dirf)) !== false) {
                    if ($txt != "." && $txt != "..") {
                        $fd = fopen("server/" . $f . "/" . $txt, "r");
                        while(!feof($fd)) {
                            $line = fgets($fd);
                            if (substr($line, 0, 4) == "18FF") {
                                new Analyze($line, $date);
                            } else {
                                echo "存在非法数据, 已跳过插入！\n";
                            }
                        }
                        fclose($fd);
                    }
                }
            }
        }
    }
}
?>
