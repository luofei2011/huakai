<?php
class Cookie extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->db = $this->load->database('huakai', true);
    }

    public function query_cookie_state($un, $token) {
        $sql = "SELECT * FROM `security` WHERE `username`='$un' and `token`='$token'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result ? true : false;
    }

    public function destroy_cookie() {
        setcookie('token', '', 0, '/');
        setcookie('username', '', 0, '/');
    }
}
?>
