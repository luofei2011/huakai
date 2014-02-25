<?php
class Analyze {
    // 得到的解析源数据
    protected $frame;
    protected $mode_data_vol = [];
    protected $mode_data_tmp = [];
    protected $date;

    public function __construct() {
        require_once('database/db_config.php');
        $args = func_get_args();
        $this->frame = $args[0];
        $this->date = $args[1];

        // 数据库配置
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


        // 分析数据
        $arr = explode("18FF", $this->frame);
        $arr = array_filter($arr);
        foreach($arr as $item) {
            $this->category_data($item);
        }
        // 存储电压数据
        if ($this->mode_data_vol) {
            foreach(array_keys($this->mode_data_vol) as $item) {
                foreach(array_keys($this->mode_data_vol[$item]) as $id) {
                    $sql = "SELECT `voltage` FROM `Battery_Data` WHERE `vehicle_id`='0' and `mod_num`='$item' and `battery_id`='$id' and `day`='$this->date'";
                    $value = $this->query_is_stored($sql);
                    $vol = $this->mode_data_vol[$item][$id];
                    $query = "INSERT INTO Battery_Data(vehicle_id,mod_num,battery_id,day,voltage) values('0','$item',$id,'$this->date','$vol')";
                    if (strlen($value[0])) {
                        $vol = $value[0] . ";" . $vol;
                        $query = "UPDATE `Battery_Data` SET `voltage`='$vol' WHERE `vehicle_id`='0' and `mod_num`='$item' and `battery_id`='$id' and `day`='$this->date'";
                    }
                    $result = mysqli_query($dbc, $query);
                }
            }
        }
        // 存储温度数据
        if ($this->mode_data_tmp) {
            $mod_num = array_keys($this->mode_data_tmp);
            foreach($mod_num as $item) {
                foreach($this->mode_data_tmp[$item] as $t) {
                    $sql = "SELECT * FROM `Package_Data` WHERE `vehicle_id`='0' and `mod_num`='$item' and `day`='$this->date'";
                    $value = $this->query_is_stored($sql);
                    $query = "INSERT INTO Package_Data(vehicle_id,mod_num,day,t1,t2,t3,t4,t5,t6) values('0','$item','$this->date','$t[0]','$t[1]','$t[2]','$t[3]','$t[4]','$t[5]')";
                    if($value) {
                        $t1 = $value['t1'] . ";" . $t[0];
                        $t2 = $value['t2'] . ";" . $t[1];
                        $t3 = $value['t3'] . ";" . $t[2];
                        $t4 = $value['t4'] . ";" . $t[3];
                        $t5 = $value['t5'] . ";" . $t[4];
                        $t6 = $value['t6'] . ";" . $t[5];
                        $query = "UPDATE `Package_Data` SET `t1`='$t1',`t2`='$t2',`t3`='$t3',`t4`='$t4',`t5`='$t5',`t6`='$t6' WHERE `vehicle_id`='0' and `mod_num`='$item' and `day`='$this->date'";
                    }
                    $result = mysqli_query($dbc, $query);
                }
            }
        }
        mysqli_close($dbc);
    }

    protected function query_is_stored($sql) {
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $result = mysqli_query($db, $sql);
        $result = mysqli_fetch_array($result);
        mysqli_close($db);
        return $result;
    }

    /*
     * 数据中的第一两位代表查询的id，接下来的两位是SA
     * */
    protected function category_data($str) {
        // 得到该帧数据的PS
        $sort = substr($str, 0, 2);
        // 得到该帧数据的数据
        $data = substr($str, 2, 18);
        switch($sort) {
            // 单体电压数据, 共三组；
            case 16:
                //echo "单体电压:\n";
                $this->common_split($data, 4, "电压");
                break;
            // 温度数据
            case 17:
                //echo "温度数据：\n";
                $this->common_split($data, 2, "温度");
                break;
            // 均电数据
            case 18:
                $this->common_split($data, 2);
                break;
            case 19:
                break;
            case 20:
                break;
            case 21:
                break;
            case 22:
                break;
            case 23:
                $this->setting_split($data);
                break;
            case 24:
                break;
            default:
                break;
        }
    }

    /*
     * 第一位数据是电池分组，接下来是三个2位的电池数据，最后一位是保留数据
     * {string} $data 源数据
     * {int} $l 截取长度
     * */
    protected function common_split($data, $l, $m) {
        // 得到模块地址
        $mod_id = substr($data, 0, 2);
        // 换成10进制的分组数据
        $group = hexdec(substr($data, 2, 2));
        // 得到具体数据
        $s = substr($data, 4, 12);
        $arr = str_split($s, $l);
        $arr = $this->create_correct_hex($arr);
        // 各种数据
        switch($m) {
            case "电压":
                if (!is_array($this->mode_data_vol[$mod_id])) {
                    $this->mode_data_vol[$mod_id] = [];
                }
                $i = 0;
                foreach($arr as $vol) {
                    // 电压数据是2该字节，高字节在前。
                    $byte_arr = str_split($arr[$i], 2);
                    $true_hex_str = $byte_arr[1] . $byte_arr[0];
                    $this->mode_data_vol[$mod_id][$group * 3 + $i] = hexdec($true_hex_str) * 0.001;
                    $i += 1;
                }
                break;
            case "温度":
                if (!is_array($this->mode_data_tmp[$mod_id])) {
                    $this->mode_data_tmp[$mod_id] = [];
                }
                $arr = $this->hex_dec_tmp($arr);
                array_push($this->mode_data_tmp[$mod_id], $arr);
                break;
        }
    }

    protected function create_correct_hex($arr) {
        $result = [];
        foreach($arr as $d) {
            array_push($result, $this->is_hex($d));
        }
        return $result;
    }

    protected function is_hex($str) {
        $len = strlen($str);
        $is_correct = true;
        $re = "";
        for($i = 0; $i < $len; $i++) {
            if (!(($str[$i] >= '0' && $str[$i] <= '9') || ($str[$i] >= 'A' && $str[$i] <= 'F'))) {
                $is_correct = false;
                break;
            }
        }
        if (!$is_correct) {
            for($i = 0; $i < $len; $i++) {
                $re .= "0";
            }
            return $re;
        }  
        return $str;
    }

    protected function hex_dec_tmp($arr) {
        $result = [];
        foreach($arr as $item) {
            if ($item != "FE") {
                // 数据偏移修正
                array_push($result, hexdec($item) - 40);
            } else {
                array_push($result, 0);
            }
        }
        return $result;
    }

    /*
     * 设置参数分析
     * */
    protected function setting_split($data) {
        // 模块地址
        //echo "设置参数分析数据：\n";
        $mod_addr = substr($data, 2, 2);
        $s = substr($data, 4, 10);
        $arr = str_split($s, 2);
        //var_dump($arr);

        // 均衡允许电压
        $last = substr($data, 12, 4);
        //echo $last;
    }
}
?>
