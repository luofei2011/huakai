<?php
$MCUAddressLimit = array(
    "DZ60_MAX" => 0xffff,
    "DZ60_MIN" => 0x1900,
    "XEP100_UNPAGE_MAX" => 0xFFFF,
    "XEP100_UNPAGE_MIN" => 0xC000,
    "XEP100_MAX" => 0xFFFFFF,
    "XEP100_MIN" => 0xFE8000
);

$AddressLimitMax = $MCUAddressLimit['DZ60_MAX'];
$AddressLimitMin = $MCUAddressLimit['DZ60_MIN'];

$AddrXEPUnpageMax = 0xffff;
$AddrXEPUnpageMin = 0xC000;

function AnalyzerFile($filename) {
    global $MCUAddressLimit;
    global $AddressLimitMax;
    global $AddressLimitMin;
    global $AddrXEPUnpageMax;
    global $AddrXEPUnpageMin;

    $s_data_byte16 = array(16);
    $data_len = 0;
    $tmp_bin_data = array();

    $s1s2s3_data = array();
    $handle = fopen($filename, "r");
    $s_data_byte = fread($handle, filesize($filename));
    fclose($handle);
    $s_data = array();
    $i;
    do {
        $data_len = strlen($s_data_byte);
        $data_len /= 16;
        $unzip_again = false;
        $s_data_string_sub = split("\r\n", $s_data_byte);
        $s_data_string_sub = array_filter($s_data_string_sub);
        try {
            $i = 0;
            $s_data[$i] = array();
            foreach($s_data_string_sub as $str) {
                $temp_data = $str;
                $aw = 0;
                switch($temp_data[1]) {
                    case '0':
                        $aw = 2;
                        $s_data[$i]['_Type'] = "S0";
                        break;
                    case "1":
                        $aw = 2;
                        $s_data[$i]['_Type'] = "S1";
                        break;
                    case "2":
                        $aw = 3;
                        $s_data[$i]['_Type'] = "S2";
                        break;
                    case "3":
                        $aw = 4;
                        $s_data[$i]['_Type'] = "S3";
                        break;
                    case "4":
                        $s_data[$i]['_Type'] = "S4";
                        break;
                    case "5":
                        $s_data[$i]['_Type'] = "S5";
                        break;
                    case "6":
                        $s_data[$i]['_Type'] = "S6";
                        break;
                    case "7":
                        $aw = 4;
                        $s_data[$i]['_Type'] = "S7";
                        break;
                    case "8":
                        $aw = 3;
                        $s_data[$i]['_Type'] = "S8";
                        break;
                    case "9":
                        $aw = 2;
                        $s_data[$i]['_Type'] = "S9";
                        break;
                    case "\0":
                        break;
                    default:
                        break;
                }
                //endregion
                // 把地址转换成十进制
                $s_data[$i]['_Count'] = hexdec(substr($temp_data, 2, 2));
                $s_data[$i]['_Address'] = hexdec(substr($temp_data, 4, $aw * 2));
                $s_data[$i]['_Checksum'] = hexdec(substr($temp_data, ($s_data[$i]['_Count'] + 1) * 2, 2));

                if ($AddrXEPUnpageMin == 0xff) {
                    if (($s_data[$i]['_Address'] >= 0xFFC0) && ($s_data[$i]['_Address'] < 0xffff)) {
                        $s_data[$i]['_Address'] &=  0x00ff;
                        $s_data[$i]['_Address'] |=  0xbd00;
                    }
                }

                $dl = $s_data[$i]['_Count'] - $aw - 1;
                if ($dl < 0) {
                    $dl = 0;
                }
                $s_data[$i]['_Data'] = array($dl);
                for ($j = 0; $j < $dl; $j++) {
                    $s_data[$i]['_Data'][$j] = hexdec(substr($temp_data, 2 + 2+ $aw * 2 + $j * 2, 2));
                }
                $s_data[$i]['_DataString'] = dechex($s_data[$i]['_Data']);
                $i += 1;
            }
        } catch(Exception $e) {
            $unzip_again = true;
        }
    } while($unzip_again);

    foreach($s_data as $rd) {
        if ($rd["_Type"] == "S1" || $rd["_Type"] == "S2" || $rd["_Type"] == "S3") {
            if ($rd['_Address'] == 0xffb0) {
            }
            if ((($rd['_Address'] < $AddressLimitMax) && ($rd['_Address'] > $AddressLimitMin)) || (($rd['_Address'] < $AddrXEPUnpageMax) && ($rd['_Address'] > $AddrXEPUnpageMin))) {
                array_push($s1s2s3_data, $rd);
                $count = 0;
                foreach($rd['_Data'] as $d) {
                    if ($d == 0xff) {
                        $count += 1;
                    }
                }

                if ($count >= count($rd['_Data'])) {
                    continue;
                }

                $tmp_addr = $rd['_Address'];
                $i = 0;
                foreach($rd['_Data'] as $d) {
                    // 全部还原成十六进制
                    $tmp_bin_data[dechex($tmp_addr + $i)] = dechex($d);
                    $i += 1;
                }
            }
        }
    }
    return $tmp_bin_data;
}
$arr = AnalyzerFile("file/MCU.s19");
echo count($arr);
?>
