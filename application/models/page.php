<?php
class Page extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->db = $this->load->database('huakai', true);
    }

    public function query_info($name) {
        $sql = "SELECT `". $name ."` FROM `info`";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result ? $result[0][$name] : false;
    }

    public function set_nav_main($arr) {
        $result = $this->db->insert('nav_1', $arr);
        return $result;
    }

    public function set_nav_second($arr) {
        $result = $this->db->insert('nav_2', $arr);
        return $result;
    }

    public function query_nav_main() {
        $sql = "SELECT * FROM `nav_1`";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function query_nav_second($pid) {
        $sql = "SELECT * FROM `nav_2` WHERE `pid` = '$pid'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
}
?>
