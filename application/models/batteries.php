<?php
class Batteries extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function query_battery_data() {
        $sql = "SELECT * FROM `Battery_Data_YX`";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    function query_package_data() {
        $sql = "SELECT * FROM `Package_Data_YX`";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    function query_vehicle_data($name) {
        $sql = "SELECT * FROM `Vehicle_Data_YX` WHERE `Name` = '" . $name . "'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    public function query_date_info($con) {
        $result;

        switch($con) {
            case 1:
                $sql = "SELECT distinct `Day` FROM `Vehicle_Data_YX`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                break;
            case 2:
                $sql = "SELECT distinct `Day` FROM `Package_Data_YX`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                break;
            case 3:
                $sql = "SELECT distinct `Day` FROM `Battery_Data_YX`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                break;
            default:
                break;
        }

        return $result;
    }
}
?>
