<?php
class Batteries extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->db = $this->load->database('default', true);
   }

    function query_battery_data($name, $date, $num = false) {
        $sql = "SELECT `VehicleId` FROM `Vehicle_Data_YX` WHERE `Name`='" . $name . "' and `Day`='" . $date . "'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $cId = $result[0]['VehicleId'];

        if ($num)
            $sql = "SELECT `Battery". $num ."` FROM `Battery_Data_YX` WHERE `VehicleId`='". $cId ."' and `Day`='". $date ."'";
        else
            $sql = "SELECT * FROM `Battery_Data_YX` WHERE `VehicleId`='". $cId ."' and `Day`='". $date ."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if ($result)
            return $result[0];
        else 
            return false;
    }

    function query_battery_data_new($batteryArr, $date, $signal = false) {
        if (!$signal) 
            $sql = "SELECT * FROM `Battery_Data` WHERE `mod_num`='$batteryArr' and `day`='$date'";
        else
            $sql = "SELECT * FROM `Battery_Data` WHERE `mod_num`='$batteryArr' and `day`='$date' and `battery_id`='$signal'";
        return $this->query($sql);
    }

    function query_package_data($name, $date, $batteryArr) {
        $sql = "SELECT `VehicleId` FROM `Vehicle_Data_YX` WHERE `Name`='" . $name . "' and `Day`='" . $date . "'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $cId = $result[0]['VehicleId'];

        // 根据id到电池组表中进行选择
        $sql = "SELECT * FROM `Package_Data_YX` WHERE `VehicleId`='". $cId ."' and `Number`='". $batteryArr ."' and `Day`='". $date ."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if ($result)
            return $result[0];
        else 
            return false;
    }

    function query_package_data_new($date, $batteryArr) {
        $sql = "SELECT * FROM `Package_Data` WHERE `mod_num`='$batteryArr' and `day`='$date'";
        $result = $this->query($sql);
        if ($result)
            return $result[0];
        else 
            return false;
    }

    function query_vehicle_data($name, $date) {
        // TODO 是否更换为id查询
        $sql = "SELECT * FROM `Vehicle_Data_YX` WHERE `Name`='" . $name . "' and `Day`='" . $date . "'";
        //$sql = "SELECT * FROM `Vehicle_Data_YX`";
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
                $sql = "SELECT distinct `Day` FROM `Vehicle_Data_YX`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            case 2:
                $sql = "SELECT distinct `Day` FROM `Package_Data_YX`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            case 3:
                $sql = "SELECT distinct `Day` FROM `Battery_Data_YX`";
                $query = $this->db->query($sql);
                $result = $query->result_array();
                return $result;
            default:
                break;
        }
    }

    function query_mod_idx() {
        $sql = "SELECT distinct `mod_num` FROM `Package_Data`";
        return $this->query($sql);
    }

    function query_battery_in_mod($mod_num) {
        $sql = "SELECT `battery_id` FROM `Battery_Data` WHERE `mod_num`='$mod_num'";
        return $this->query($sql);
    }
 
    function query_date_new($mod_num, $battery_id = false) {
        $sql = "SELECT distinct `day` FROM `Package_Data` WHERE `mod_num`='$mod_num'";
        if ($battery_id) {
            $sql = "SELECT distinct `day` FROM `Battery_Data` WHERE `mod_num`='$mod_num' and `battery_id`='$battery_id'";
        }
        return $this->query($sql);
    }

    private function query($sql) {
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
}
?>
