<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Intro extends CI_Controller {

    private $data = [];

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Page');

        $this->data['header'] = false;
    }

    public function index($name) {
        switch($name) {
            case "company_profile":
                $table = "profile";
                $this->data['title'] = "公司简介";
                break;
            case "company_event":
                $table = "big_event";
                $this->data['title'] = "公司大事记";
                break;
            case "company_strategy":
                $table = "strategy";
                $this->data['title'] = "公司战略";
                break;
            default:
                $table = false;
                break;
        }
        if ($table) {
            $this->load->view('common/header', $this->data);
            $msg = [];
            $msg["data"] = $this->Page->query_info($table);
            //echo $data["data"];
            //return;
            $this->load->view('common/page', $msg);
            $this->load->view('common/footer');
        } else {
            return false;
        }
    }

    public function server_info() {
        $this->data['title'] = "S-BMS";
        $this->load->view('common/header', $this->data);
        $this->load->view('pages/sbms');
        $this->load->view('common/footer');
    }

    public function car_net() {
        $this->data['title'] = "车联网";
        $this->load->view('common/header', $this->data);
        $this->load->view('pages/car_net');
        $this->load->view('common/footer');
    }

    public function partners() {
        $this->data['title'] = "合作伙伴";
        $this->load->view('common/header', $this->data);
        $this->load->view('pages/logo_list');
        $this->load->view('common/footer');
    }

    public function down() {
        $this->data['title'] = "下载中心";
        $this->load->view('common/header', $this->data);
        $this->load->view('pages/down_center');
        $this->load->view('common/footer');
    }
}
