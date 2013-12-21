<?php
class News extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->db = $this->load->database('huakai', true);
    }

    public function save_news($arr) {
        $result = $this->db->insert('news', $arr);
        return $result;
    }

    public function get_category() {
        $sql = "SELECT `name` FROM `category`";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result ? $result : false;
    }
}
?>
