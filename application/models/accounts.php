<?php
class Accounts extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->db = $this->load->database('huakai', true);
    }

    public function get_account($username, $pwd, $auth) {
        $pwd = md5($pwd);
        $sql = "SELECT * FROM `account` WHERE `username`='$username' and `password`='$pwd' and `auth`='$auth'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if ($result) {
            $this->auto_set_cookie($result[0]);
            return true;
        }
        return false;
    }

    private function auto_set_cookie($arr) {
        $un = $arr['username'];
        $ip = $this->input->ip_address();
        $token = md5($arr['username'] . $arr['password'] . $arr['auth'] . time());
        $time = date('Y-m-d G:i:s', time());
        $sql = "INSERT INTO `security` values('', '$un', '$token', '$ip', '$time')";
        $query = $this->db->query($sql);
        // 浏览器关闭以后就删除会话
        // TODO 添加主动退出会话功能
        if ($query) {
            //$this->input->set_cookie('token', $token, 0, '/');
            setcookie('token', $token, 0, '/');
            setcookie('username', $un, 0, '/');
        }
    }
}
?>
