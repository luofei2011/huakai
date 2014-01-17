<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Accounts');
    }

    public function index() {
        $username = $this->input->post('username', true);
        $pwd = $this->input->post('password', true);
        $eamil = $this->input->post('email', true);

        // 默认的账户权限
        $auth = 0;
        $result = $this->Accounts->get_account($username, $pwd, $auth);

        // 登录成功
        if ($result) {
            // 跳转到管理员界面
            //redirect('/admin', 'refresh');
            echo "admin";
        } else {
            //return false;
            echo "false";
        }
    }
}
