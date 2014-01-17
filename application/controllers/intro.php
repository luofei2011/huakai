<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Intro extends CI_Controller {

    private $data = [];

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Page');
        $this->load->model('News');

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
            $msg["nav"] = [
                ['url'=>base_url('intro/index/company_profile'),'name'=>'公司简介'],
                ['url'=>base_url('intro/index/company_event'),'name'=>'公司大事记'],
                ['url'=>base_url('intro/index/company_strategy'),'name'=>'公司战略'],
                ['url'=>base_url('intro/partners'),'name'=>'合作伙伴']
            ];
            //echo $data["data"];
            //return;
            $this->load->view('common/page_frameset', $msg);
            $this->load->view('common/footer');
        } else {
            return false;
        }
    }

    public function page_frameset($idx) {
        $msg = [];
        switch($idx) {
            case 1:
                $this->data['title'] = "新闻动态";
                $msg['nav'] = [
                    ['url'=>base_url('intro/news_list'),'name'=>'公司动态'],
                    ['url'=>'','name'=>'行业新闻']
                ];
                break;
            case 2:
                $this->data['title'] = "技术与产品";
                $msg['nav'] = [
                    ['url'=>base_url('intro/server_info'),'name'=>'S-BMS'],
                    ['url'=>base_url('intro/car_net'),'name'=>'车联网'],
                    ['url'=>'','name'=>'电池数据分析']
                ];
                break;
            case 3:
                $this->data['title'] = "服务支持";
                $msg['nav'] = [
                    ['url'=>'','name'=>'售后'],
                    ['url'=>'','name'=>'下载中心'],
                    ['url'=>'','name'=>'联系我们']
                ];
                break;
        }
        $this->load->view('common/header', $this->data);
        $this->load->view('common/page', $msg);
        $this->load->view('common/footer');
    }

    public function news_list() {
        $this->data['news_list'] = $this->News->get_news_list();
        $this->load->view('pages/news_list', $this->data);
    }

    public function news($id) {
        $msg = [];
        $msg['news'] = $this->News->get_news_detail($id);
        $this->data['header'] = true;
        $this->data['title'] = $msg['news']['title'];
        $this->load->view('common/header', $this->data);
        $this->load->view('pages/news_detail', $msg);
        $this->load->view('common/footer');
    }

    public function server_info() {
        $this->load->view('pages/sbms');
    }

    public function car_net() {
        $this->load->view('pages/car_net');
    }

    public function partners() {
        $this->data['title'] = "合作伙伴";
        $this->load->view('common/header', $this->data);
        $this->load->view('pages/logo_list');
        $this->load->view('common/footer');
    }

    public function down() {
        $this->load->view('pages/down_center');
    }

    public function test() {
        $this->data['title'] = "详情页面";
        $this->load->view('common/header', $this->data);
        $this->load->view('common/page_frameset');
        $this->load->view('common/footer');
    }
}
