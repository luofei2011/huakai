<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Battery extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

	public function index() {
        $cName = $this->input->post('companyName');
        $eleCar = $this->input->post('eleCar');
        $batteryArr = $this->input->post('batteryArr');
        $signalBattery = $this->input->post('signalBattery');
        $colDate = $this->input->post('colDate');
        $timeGap = $this->input->post('timeGap');

        // 显示整车数据
        if (!$batteryArr && !$signalBattery) {
            echo "整车";
        }

        // 显示电池组数据
        if ($batteryArr && !$signalBattery) {
            echo "电池组";
        }

        // 显示单体电池数据
        if ($signalBattery) {
            echo "单体电池";
        }
	}

    private function jpgraph() {
        echo "hello world!";
    }
}
