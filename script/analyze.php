<?php
class Analyze {
    // 得到的解析源数据
    protected $frame;
    public function __construct() {
        $args = func_get_args();
        $this->frame = $args[0];

        // 分析数据
        $arr = explode("18FF", $this->frame);
        $arr = array_filter($arr);
        foreach($arr as $item) {
            $this->category_data($item);
        }
    }

    /*
     * 数据中的第一两位代表查询的id，接下来的两位是SA
     * */
    protected function category_data($str) {
        // 得到该帧数据的PS
        $sort = substr($str, 0, 2);
        // 得到该帧数据的数据
        $data = substr($str, 4, 16);
        switch($sort) {
            // 单体电压数据, 共三组；
            case 16:
                $this->common_split($data, 4);
                break;
            case 17:
                $this->common_split($data, 2);
                break;
            case 18:
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
    protected function common_split($data, $l) {
        // 换成10进制的分组数据
        echo "单体电池电压解析数据：\n";
        $group = hexdec(substr($data, 0, 2));
        $s = substr($data, 2, 12);
        $arr = str_split($s, $l);
        var_dump($arr);
    }

    /*
     * 设置参数分析
     * */
    protected function setting_split($data) {
        // 模块地址
        echo "设置参数分析数据：\n";
        $mod_addr = substr($data, 0, 2);
        $s = substr($data, 2, 10);
        $arr = str_split($s, 2);
        var_dump($arr);

        // 均衡允许电压
        $last = substr($data, 12, 4);
        echo $last;
    }
}
?>
