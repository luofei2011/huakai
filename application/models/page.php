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
}
?>
