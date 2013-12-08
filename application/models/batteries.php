<?php
class Batteries extends CI_Model {

    public function __construct(){
        parent::__construct();
   }

    function query_battery_data($name, $date, $num = false) {
        $sql = "SELECT `VehicleId` FROM `vehicle_data_yx` WHERE `Name`='" . $name . "' and `Day`='" . $date . "'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $cId = $result[0]['VehicleId'];

        if ($num)
            $sql = "SELECT `Battery". $num ."` FROM `battery_data_yx` WHERE `VehicleId`='". $cId ."' and `Day`='". $date ."'";
        else
            $sql = "SELECT * FROM `battery_data_yx` WHERE `VehicleId`='". $cId ."' and `Day`='". $date ."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if ($result)
            return $result[0];
        else 
            return false;
    }

    function query_package_data($name, $date, $batteryArr) {
        $sql = "SELECT `VehicleId` FROM `vehicle_data_yx` WHERE `Name`='" . $name . "' and `Day`='" . $date . "'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $cId = $result[0]['VehicleId'];

        // 根据id到电池组表中进行选择
        $sql = "SELECT * FROM `package_data_yx` WHERE `VehicleId`='". $cId ."' and `Number`='". $batteryArr ."' and `Day`='". $date ."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if ($result)
            return $result[0];
        else 
            return false;
    }

    function query_vehicle_data($name, $date) {
        // TODO 是否更换为id查询
        $sql = "SELECT * FROM `vehicle_data_yx` WHERE `Name`='" . $name . "' and `Day`='" . $date . "'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if ($result)
            return $result[0];
        else 
            return false;
    }

    function query_date_info($con) {
        switch($con) {
            case 1:
                $sql = "SELECT distinct `Day` FROM `vehicle_data_yx`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            case 2:
                $sql = "SELECT distinct `Day` FROM `package_data_yx`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            case 3:
                $sql = "SELECT distinct `Day` FROM `battery_data_yx`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            default:
                break;
        }
    }
}
?>
