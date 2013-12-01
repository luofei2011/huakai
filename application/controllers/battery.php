<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Battery extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('batteries');
    }

    public function index() {
        $this->load->view('common/header');
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
            $result = $this->Battery_info->query_vehicle_data($eleCar);
            //Y轴数据
            $ydata = $
            $ydata2 = array(35,32,29,26,23,21,19,16,13);
            $ydata3 = array(2,10,14,19,23,26,30,35,45);
            $r = $this->jpgraph(
                array(
                    "width" => 900,
                    "height" => 420,
                    "title" => "整车信息显示页面",
                    "yLable" => "值域",
                    "xLable" => "时间"
                ),
                array($ydata, $ydata2, $ydata3), 
                array(
                    "#FF0000",
                    "#00FF00",
                    "#0000FF"
            ));
            echo json_encode(array(
                "code" => 200,
                "msg" => $r
            ));
        }

        // 显示电池组数据
        if ($batteryArr && !$signalBattery) {
            $result = $this->Battery_info->query_package_data();
            //Y轴数据
            $ydata = array(40,37,35,33,28,25,23,19,15,10);
            $ydata2 = array(35,32,29,26,23,21,19,16,13);
            $ydata3 = array(2,10,14,19,23,26,30,35,45);
            $r1 = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "电池组信息显示页面",
                    "yLable" => "电压",
                    "xLable" => "时间"
                ),
                array($ydata, $ydata2, $ydata3), 
                array(
                    "#FF0000",
                    "#00FF00",
                    "#0000FF"
            ));
            $r2 = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "电池组信息显示页面",
                    "yLable" => "温度",
                    "xLable" => "时间"
                ),
                array($ydata, $ydata2, $ydata3), 
                array(
                    "#FF0000",
                    "#00FF00",
                    "#0000FF"
            ));

            echo json_encode(array(
                "code" => 200,
                "msg" => $r1,
                "msg2" => $r2
            ));
        }

        // 显示单体电池数据
        if ($signalBattery) {
            $result = $this->Battery_info->query_battery_data();
            //Y轴数据
            $ydata = array(40,37,35,33,28,25,23,19,15,10);
            $ydata2 = array(35,32,29,26,23,21,19,16,13);
            $ydata3 = array(2,10,14,19,23,26,30,35,45);
            $ydata4 = array(21,19,17,10,2,26,30,35,45);
            $r1 = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "单体电池显示页面",
                    "yLable" => "电压",
                    "xLable" => "时间"
                ),
                array($ydata), 
                array(
                    "#FF0000"
            ));
            $r2 = $this->jpgraph(
                array(
                    "width" => 440,
                    "height" => 420,
                    "title" => "单体电池显示页面",
                    "yLable" => "温度",
                    "xLable" => "时间"
                ),
                array($ydata, $ydata2, $ydata3), 
                array(
                    "#FF0000",
                    "#00FF00",
                    "#0000FF"
            ));

            echo json_encode(array(
                "code" => 200,
                "msg" => $r1,
                "msg2" => $r2
            ));
        }
	}

    public function query_date() {
        $this->load->model('batteries');
        $con = $this->input->post('con');
        $resutl = $this->Batteries->query_date_info($con);

        echo json_encode(array(
            "date" => $result
        ));
    }

    /*
     * data is an Array!
     * */
    private function jpgraph($config, $data, $color) {
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
            if (count($color)) 
                $tmpLine->setColor($color[$i]);
            $graph->Add($tmpLine);
        }
         
        //显示到浏览器
        // 以当前时间的md5加密取前8位作为文件名
        $dir = "jpgraph/g_" . substr(md5(date("Y-m-d H:i:s")), 0, 8) . ".png";
        $graph->Stroke($dir);

        return $dir;
    }
}
