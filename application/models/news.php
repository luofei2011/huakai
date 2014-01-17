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

    public function get_news_list() {
        $sql = "SELECT `id`, `title`, `pub_time`, `readers` FROM `news` WHERE `show` = 1";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    public function get_news_detail($id) {
        $sql = "SELECT * FROM `news` WHERE `id` = $id";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result ? $result[0] : false;
    }
}
?>
