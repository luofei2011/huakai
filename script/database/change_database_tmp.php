<?php
require_once("db_config.php");
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$sql = "SELECT * FROM `Package_Data` WHERE 1";
$result = mysqli_query($db, $sql);
//$result = mysqli_fetch_array($result);
while($data = mysqli_fetch_array($result)) {
    $out = [];
    $id = $data["id"];
    for($i = 1; $i <= 6; $i++) {
        $arr = [];
        $temp = $data["t" . $i];
        $tmp_arr = explode(";", $temp);
        foreach($tmp_arr as $s) {
            $s_int = (int)$s;
            if ($s_int >= 40) 
                $s_int -= 40;
            array_push($arr, strval($s_int));
        }
        $out[$i] = join(";", $arr);
    }

    // 更新数据库
    $sql = "UPDATE `Package_Data` SET `t1`='$out[1]',`t2`='$out[2]',`t3`='$out[3]',`t4`='$out[4]',`t5`='$out[5]',`t6`='$out[6]' WHERE `id`=$id";
    $r = mysqli_query($db, $sql);
    if ($r)
        echo "更新温度数据成功！\n";
    else 
        echo "更新温度数据失败！，请手动更新。\n";
}
mysqli_close($db);
?>
