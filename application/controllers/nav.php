<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author luofei
 * @email luofeihit2010@gmail.com
 * @time 2013-12-21
 *
 * */

class Nav extends CI_Controller {

    private $data = [];
    // 当前用户
    private $un = "";

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Page');
    }

    public function show_nav_main() {
        $nav = $this->Page->query_nav_main();
        echo json_encode($nav);
    }

    public function show_nav_second($pid) {
        $nav = $this->Page->query_nav_second();
        echo json_encode($nav);
    }

    public function set_nav_main() {
        $name = $this->input->post('name', true);
        $arr = [
            id => '',
            name => $name
        ];
        $this->Page->set_nav_main($arr);
    }

    public function set_nav_second() {
        $name = $this->input->post('name', true);
        $pid = $this->input->post('pid', true);
        $arr = [
            id => '',
            name => $name
        ];
        $this->Page->set_nav_second($arr);
    }
}
?>
