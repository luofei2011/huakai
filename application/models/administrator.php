<?php
class Administrator extends CI_Model {

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

    public function get_category() {
        $sql = "SELECT * FROM `category`";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    public function set_category($txt) {
        $sql = "INSERT INTO `category` values('', '$txt')";
        $query = $this->db->query($sql);

        return $query;
    }
}
?>
