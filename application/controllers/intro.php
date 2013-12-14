<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Intro extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Page');
    }

    public function index($name) {
        switch($name) {
            case "company_profile":
                $table = "profile";
                break;
            case "company_event":
                $table = "big_event";
                break;
            case "company_strategy":
                $table = "strategy";
                break;
            default:
                $table = false;
                break;
        }
        if ($table) {
            $this->load->view('common/header');
            $data = array();
            $data["data"] = $this->Page->query_info($table);
            //echo $data["data"];
            //return;
            $this->load->view('common/page', $data);
            $this->load->view('common/footer');
        } else {
            return false;
        }
    }

    public function server_info() {
        $this->load->view('common/header');
        $this->load->view('pages/sbms');
        $this->load->view('common/footer');
    }

    public function car_net() {
        $this->load->view('common/header');
        $this->load->view('pages/car_net');
        $this->load->view('common/footer');
    }

    public function partners() {
        $this->load->view('common/header');
        $this->load->view('pages/logo_list');
        $this->load->view('common/footer');
    }

    public function down() {
        $this->load->view('common/header');
        $this->load->view('pages/down_center');
        $this->load->view('common/footer');
    }
}
