<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Battery extends CI_Controller {

    private $data = [];

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Batteries');

        // 该页面不显示logo
        $this->data['header'] = true;
    }

    public function index() {
        $this->data['title'] = "电池数据分析页面";
        $this->load->view('common/header', $this->data);
        $this->load->view('common/jpgraph');
        $this->load->view('common/battery');
		$this->load->view('common/footer');
    }

	public function ajax_s() {
        $cName = $this->input->post('companyName');
        $eleCar = $this->input->post('eleCar');
        $batteryArr = $this->input->post('batteryArr');
        $signalBattery = $this->input->post('signalBattery');
        $colDate = $this->input->post('colDate');
        $timeGap = $this->input->post('timeGap');

        if (!$cName || !$eleCar) {
            echo json_encode(array(
                "code" => 100,
                "msg" => "error"
            ));
        }

        // 显示整车数据
        if (!$batteryArr && !$signalBattery) {
            $result = $this->Batteries->query_vehicle_data($eleCar, $colDate);
            //Y轴数据
            $ydata2 = explode(";", $result["Voltage"]);
            $ydata2 = array_merge($ydata2);
            $ydata = explode(";", $result["Soc"]);
            $ydata = array_merge($ydata);
            $ydata3 = explode(";", $result["Temperature"]);
            $ydata3 = array_merge($ydata3);
            $r = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "整车信息显示页面",
                    "yLable" => "值域",
                    "xLable" => "时间"
                ),
                array($ydata, $ydata3), 
                array(
                    "#FF0000",
                    "#00FF00"
            ));
            $r_t = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "整车信息显示页面",
                    "yLable" => "电压",
                    "xLable" => "时间"
                ),
                array($ydata2), 
                array(
                    "0000FF"
            ));
            echo json_encode(array(
                "code" => 200,
                "line" => array($r, $r_t)
            ));
        }

        // 显示电池组数据
        if ($batteryArr && !$signalBattery) {
            $result = $this->Batteries->query_package_data($eleCar, $colDate, $batteryArr);
            //Y轴数据
            $ydata1 = array_merge(explode(";", $result['Temperature1']));
            $ydata2 = array_merge(explode(";", $result['Temperature2']));
            $ydata3 = array_merge(explode(";", $result['Temperature3']));
            $ydata4 = array_merge(explode(";", $result['Temperature4']));
            $ydata5 = array_merge(explode(";", $result['Temperature5']));
            $ydata6 = array_merge(explode(";", $result['Temperature6']));
            $r1 = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "电池组信息显示页面",
                    "yLable" => "温度",
                    "xLable" => "时间"
                ),
                array($ydata1, $ydata2, $ydata3, $ydata4, $ydata5, $ydata6), 
                array(
                    "#FF0000",
                    "#00FF00",
                    "#0000FF",
                    "#FFFFFF",
                    "#369"
            ));

            $result = $this->Batteries->query_battery_data($eleCar, $colDate);
            $b_ = array();
            $idx = (intval($batteryArr) - 1) * 22;
            for ($i = 1; $i <= 22; $i++) {
                $b_[$i - 1] = array();
                $b_[$i - 1] = array_merge(explode(";", $result['Battery' . strval($idx + $i)]));
            }
            //var_dump($b_);
            //return;
            $r2 = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "电池组信息显示页面",
                    "yLable" => "电压",
                    "xLable" => "时间"
                ),
                $b_
            );

            echo json_encode(array(
                "code" => 200,
                "line" => array($r2, $r1)
            ));
        }

        // 显示单体电池数据
        if ($signalBattery) {
            $result = $this->Batteries->query_battery_data($eleCar, $colDate, $signalBattery);
            //Y轴数据
            $b_ = $result['Battery' . $signalBattery];
            $ydata = array_merge(explode(";", $b_));
            $r = $this->jpgraph(
                array(
                    "width" => 900,
                    "height" => 420,
                    "title" => "单体电池显示页面",
                    "yLable" => "电压",
                    "xLable" => "时间"
                ),
                array($ydata), 
                array(
                    "#FF0000"
            ));

            echo json_encode(array(
                "code" => 200,
                "line" => array($r)
            ));
        }
	}

    public function query_date() {
        $con = $this->input->post('con');
        $result = $this->Batteries->query_date_info($con);

        echo json_encode(array(
            "data" => $result
        ));
    }

    /*
     * data is an Array!
     * */
    private function jpgraph($config, $data, $color = false) {
        require_once ('jpgraph.php');
        require_once ('jpgraph_line.php');
         
        //设置图像大小
        $width = $config["width"];
        $height = $config["height"];
         
        //初始化jpgraph并创建画布
        $graph = new Graph($width,$height);
        $graph->SetScale('intlin');
         
        //设置左右上下距离
        $graph->SetMargin(40,20,20,40);
        //设置大标题
        $graph->title->SetFont(FF_SIMSUN,FS_BOLD);
        $title = $config["title"];
        $title = iconv("UTF-8", "gb2312", $title);
        $graph->title->Set($title);
        //设置小标题
        //$graph->subtitle->Set('(March 12, 2008)');
        //设置x轴title
        $graph->xaxis->title->setFont(FF_SIMSUN,FS_BOLD);
        $xTitle = $config["xLable"];
        $xTitle = iconv("UTF-8", "gb2312", $xTitle);
        $graph->xaxis->title->Set($xTitle);
        //设置y轴title
        $graph->yaxis->title->setFont(FF_SIMSUN,FS_BOLD);
        $yTitle = $config["yLable"];
        $yTitle = iconv("UTF-8", "gb2312", $yTitle);
        $graph->yaxis->title->Set($yTitle);
        //设置x轴的值
        $label_x  = array('0','1','2','3','4','5','6','7','8','9','10','11','12');
        $graph->xaxis->SetTickLabels($label_x);
         
         
        //实例化一个折线图的类并放入数据
        for ($i = 0; $i < count($data); $i++) {
            $tmpLine = new LinePlot($data[$i]);

            // 当传递了颜色值时
            if ($color) {
                if (count($color) < $i) 
                    $tmpLine->setColor($color[$i]);
            }
            $graph->Add($tmpLine);
        }
         
        //显示到浏览器
        // 以当前时间的md5加密取前8位作为文件名
        // 修正：当两次执行时间间隔非常短时，8位字母并不能区别；故再添加随机码进行区别
        $dir = "jpgraph/g" . rand(0, 100) ."_" . substr(md5(date("Y-m-d H:i:s")), 0, 8) . ".png";
        $graph->Stroke($dir);

        return $dir;
    }
}
