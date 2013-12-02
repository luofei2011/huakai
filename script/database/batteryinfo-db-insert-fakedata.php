<?php
	// 读取数据库配置
	require_once('db_config.php');
	// 数据库连接
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	mysqli_set_charset($dbc, 'utf8'); //设置字符集, 避免中文乱码

/************************ 整车相关数据 ******************************/
	// 插入沂星1-3号车2012-12-10的数据
	for ($i=0; $i<3; $i++) {
		create_vehicleX_date($i+1, "试验".($i+1)."号车", "2012-12-10", $i);
	}
	// 插入沂星1-3号车2012-12-11的数据
	for ($i=0; $i<3; $i++) {
		create_vehicleX_date($i+1, "试验".($i+1)."号车", "2012-12-11", $i);
	}		
	// 插入沂星1-3号车2012-12-12的数据
	for ($i=0; $i<3; $i++) {
		create_vehicleX_date($i+1, "试验".($i+1)."号车", "2012-12-12", $i);
	}

/************************ 电池组相关数据 *****************************/
	// 插入沂星1-3号车1-8号电池组2012-12-10的数据
	for ($j=0; $j<3; $j++) {
		for($i=1;$i<9;i++) {
			create_vehicleX_packageN_date($j+1, $i, "2012-12-10", $j);
		}
	}	
	// 插入沂星1-3号车1-8号电池组2012-12-11的数据
	for ($j=0; $j<3; $j++) {
		for($i=1;$i<9;i++) {
			create_vehicleX_packageN_date($j+1, $i, "2012-12-11", $j);
		}
	}
	// 插入沂星1-3号车1-8号电池组2012-12-12的数据
	for ($j=0; $j<3; $j++) {
		for($i=1;$i<9;i++) {
			create_vehicleX_packageN_date($j+1, $i, "2012-12-12", $j);
		}
	}

/*********************** 单体电池相关数据 ****************************/
/**
	//-- 插入沂星1号车2012-12-10单体电池数据表	
	create_vehicleX_battery_date(1, "2012-12-10", 0);
	//-- 插入沂星2号车2012-12-10单体电池数据表
	create_vehicleX_battery_date(2, "2012-12-10", 1);
	//-- 插入沂星3号车2012-12-10单体电池数据表
	create_vehicleX_battery_date(3, "2012-12-10", 2);
	
	//-- 插入沂星1号车2012-12-11单体电池数据表	
	create_vehicleX_battery_date(1, "2012-12-11", 0);
	//-- 插入沂星2号车2012-12-11单体电池数据表
	create_vehicleX_battery_date(2, "2012-12-11", 1);
	//-- 插入沂星3号车2012-12-11单体电池数据表
	create_vehicleX_battery_date(3, "2012-12-11", 2);
	
	
	//-- 插入沂星1号车2012-12-12单体电池数据表	
	create_vehicleX_battery_date(1, "2012-12-12", 0);
	//-- 插入沂星2号车2012-12-12单体电池数据表
	create_vehicleX_battery_date(2, "2012-12-12", 1);
	//-- 插入沂星3号车2012-12-12单体电池数据表
	create_vehicleX_battery_date(3, "2012-12-12", 2);
*/	

/**
 * 生成沂星X车YYYY-MM-DD的数据
 * lizhongzhen@huakaienergy.com
 */
function create_vehicleX_date($vehicle, $name, $date, $flag) {
	global $dbc;
	$soc = create_soc(0, $flag);
	$voltage = create_voltage(0, $flag);
	$temperature = create_temperature(0, $flag);
	for($i=1; $i<50; $i++) {
		$soc .= ";" .create_soc($i, $flag);
		$voltage .= ";" .create_voltage($i, $flag);
		$temperature .= ";" .create_temperature($i, $flag);
	}	
	$query = "INSERT INTO Vehicle_Data_YX values('', '$vehicle', '$name', '$date', '$soc', '$voltage', '$temperature', 'null')";
  $result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  echo "已插入沂星".$vehicle."号".$name.$date."的数据\n";
}

/**
 * 生成沂星X号车第N个电池组YYYY-MM-DD的数据
 * lizhongzhen@huakaienergy.com
 */
function create_vehicleX_packageN_date($vehicle, $package, $date, $flag) {
	global $dbc;
  $temperature1 = create_temperature(0, $flag);
	$temperature2 = create_temperature(0, $flag);
	$temperature3 = create_temperature(0, $flag);
	$temperature4 = create_temperature(0, $flag);
	$temperature5 = create_temperature(0, $flag);
	$temperature6 = create_temperature(0, $flag);
	for($i=1; $i<50; $i++) {
		$temperature1 .= ";" .create_temperature($i, $flag); //生成温度1
		$temperature2 .= ";" .create_temperature($i, $flag); //生成温度2
		$temperature3 .= ";" .create_temperature($i, $flag); //生成温度3
		$temperature4 .= ";" .create_temperature($i, $flag); //生成温度4
		$temperature5 .= ";" .create_temperature($i, $flag); //生成温度5
		$temperature6 .= ";" .create_temperature($i, $flag); //生成温度6
	}
	
  $query = "INSERT INTO Package_Data_YX values('','$vehicle','$package','$date',
  				'$temperature1','$temperature2','$temperature3','$temperature4', '$temperature5', '$temperature6')";
  $result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  echo "已插入沂星".$vehicle."号车".$package."号电池组".$date."的数据\n";
}


/**
 * 生成沂星X号车YYYY-MM-DD的单体电池数据表
 * lizhongzhen@huakaienergy.com
 */
function create_vehicleX_battery_date($vehicle, $date, $flag) {
	global $dbc;
	
	//TODO: 改为数组
	$battery1 = create_battery_voltage(0, $flag);
	$battery2 = create_battery_voltage(0, $flag);
	$battery3 = create_battery_voltage(0, $flag);
	$battery4 = create_battery_voltage(0, $flag);
	$battery5 = create_battery_voltage(0, $flag);
	$battery6 = create_battery_voltage(0, $flag);
	$battery7 = create_battery_voltage(0, $flag);
	$battery8 = create_battery_voltage(0, $flag);
	$battery9 = create_battery_voltage(0, $flag);
	$battery10 = create_battery_voltage(0, $flag);
	$battery11 = create_battery_voltage(0, $flag);
	$battery12 = create_battery_voltage(0, $flag);
	$battery13 = create_battery_voltage(0, $flag);
	$battery14 = create_battery_voltage(0, $flag);
	$battery15 = create_battery_voltage(0, $flag);
	$battery16 = create_battery_voltage(0, $flag);
	$battery17 = create_battery_voltage(0, $flag);
	$battery18 = create_battery_voltage(0, $flag);
	$battery19 = create_battery_voltage(0, $flag);
	$battery20 = create_battery_voltage(0, $flag);
	$battery21 = create_battery_voltage(0, $flag);
	$battery22 = create_battery_voltage(0, $flag);
	$battery23 = create_battery_voltage(0, $flag);
	$battery24 = create_battery_voltage(0, $flag);
	$battery25 = create_battery_voltage(0, $flag);
	$battery26 = create_battery_voltage(0, $flag);
	$battery27 = create_battery_voltage(0, $flag);
	$battery28 = create_battery_voltage(0, $flag);
	$battery29 = create_battery_voltage(0, $flag);
	$battery30 = create_battery_voltage(0, $flag);
	$battery31 = create_battery_voltage(0, $flag);
	$battery32 = create_battery_voltage(0, $flag);
	$battery33 = create_battery_voltage(0, $flag);
	$battery34 = create_battery_voltage(0, $flag);
	$battery35 = create_battery_voltage(0, $flag);
	$battery36 = create_battery_voltage(0, $flag);
	$battery37 = create_battery_voltage(0, $flag);
	$battery38 = create_battery_voltage(0, $flag);
	$battery39 = create_battery_voltage(0, $flag);
	$battery40 = create_battery_voltage(0, $flag);
	$battery41 = create_battery_voltage(0, $flag);
	$battery42 = create_battery_voltage(0, $flag);
	$battery43 = create_battery_voltage(0, $flag);
	$battery44 = create_battery_voltage(0, $flag);
	$battery45 = create_battery_voltage(0, $flag);
	$battery46 = create_battery_voltage(0, $flag);
	$battery47 = create_battery_voltage(0, $flag);
	$battery48 = create_battery_voltage(0, $flag);
	$battery49 = create_battery_voltage(0, $flag);
	$battery50 = create_battery_voltage(0, $flag);
	$battery51 = create_battery_voltage(0, $flag);
	$battery52 = create_battery_voltage(0, $flag);
	$battery53 = create_battery_voltage(0, $flag);
	$battery54 = create_battery_voltage(0, $flag);
	$battery55 = create_battery_voltage(0, $flag);
	$battery56 = create_battery_voltage(0, $flag);
	$battery57 = create_battery_voltage(0, $flag);
	$battery58 = create_battery_voltage(0, $flag);
	$battery59 = create_battery_voltage(0, $flag);
	$battery60 = create_battery_voltage(0, $flag);
	$battery61 = create_battery_voltage(0, $flag);
	$battery62 = create_battery_voltage(0, $flag);
	$battery63 = create_battery_voltage(0, $flag);
	$battery64 = create_battery_voltage(0, $flag);
	$battery65 = create_battery_voltage(0, $flag);
	$battery66 = create_battery_voltage(0, $flag);
	$battery67 = create_battery_voltage(0, $flag);
	$battery68 = create_battery_voltage(0, $flag);
	$battery69 = create_battery_voltage(0, $flag);
	$battery70 = create_battery_voltage(0, $flag);
	$battery71 = create_battery_voltage(0, $flag);
	$battery72 = create_battery_voltage(0, $flag);
	$battery73 = create_battery_voltage(0, $flag);
	$battery74 = create_battery_voltage(0, $flag);
	$battery75 = create_battery_voltage(0, $flag);
	$battery76 = create_battery_voltage(0, $flag);
	$battery77 = create_battery_voltage(0, $flag);
	$battery78 = create_battery_voltage(0, $flag);
	$battery79 = create_battery_voltage(0, $flag);
	$battery80 = create_battery_voltage(0, $flag);
	$battery81 = create_battery_voltage(0, $flag);
	$battery82 = create_battery_voltage(0, $flag);
	$battery83 = create_battery_voltage(0, $flag);
	$battery84 = create_battery_voltage(0, $flag);
	$battery85 = create_battery_voltage(0, $flag);
	$battery86 = create_battery_voltage(0, $flag);
	$battery87 = create_battery_voltage(0, $flag);
	$battery88 = create_battery_voltage(0, $flag);
	$battery89 = create_battery_voltage(0, $flag);
	$battery90 = create_battery_voltage(0, $flag);
	$battery91 = create_battery_voltage(0, $flag);
	$battery92 = create_battery_voltage(0, $flag);
	$battery93 = create_battery_voltage(0, $flag);
	$battery94 = create_battery_voltage(0, $flag);
	$battery95 = create_battery_voltage(0, $flag);
	$battery96 = create_battery_voltage(0, $flag);
	$battery97 = create_battery_voltage(0, $flag);
	$battery98 = create_battery_voltage(0, $flag);
	$battery99 = create_battery_voltage(0, $flag);
	$battery100 = create_battery_voltage(0, $flag);
	$battery101 = create_battery_voltage(0, $flag);
	$battery102 = create_battery_voltage(0, $flag);
	$battery103 = create_battery_voltage(0, $flag);
	$battery104 = create_battery_voltage(0, $flag);
	$battery105 = create_battery_voltage(0, $flag);
	$battery106 = create_battery_voltage(0, $flag);
	$battery107 = create_battery_voltage(0, $flag);
	$battery108 = create_battery_voltage(0, $flag);
	$battery109 = create_battery_voltage(0, $flag);
	$battery110 = create_battery_voltage(0, $flag);
	$battery111 = create_battery_voltage(0, $flag);
	$battery112 = create_battery_voltage(0, $flag);
	$battery113 = create_battery_voltage(0, $flag);
	$battery114 = create_battery_voltage(0, $flag);
	$battery115 = create_battery_voltage(0, $flag);
	$battery116 = create_battery_voltage(0, $flag);
	$battery117 = create_battery_voltage(0, $flag);
	$battery118 = create_battery_voltage(0, $flag);
	$battery119 = create_battery_voltage(0, $flag);
	$battery120 = create_battery_voltage(0, $flag);
	$battery121 = create_battery_voltage(0, $flag);
	$battery122 = create_battery_voltage(0, $flag);
	$battery123 = create_battery_voltage(0, $flag);
	$battery124 = create_battery_voltage(0, $flag);
	$battery125 = create_battery_voltage(0, $flag);
	$battery126 = create_battery_voltage(0, $flag);
	$battery127 = create_battery_voltage(0, $flag);
	$battery128 = create_battery_voltage(0, $flag);
	$battery129 = create_battery_voltage(0, $flag);
	$battery130 = create_battery_voltage(0, $flag);
	$battery131 = create_battery_voltage(0, $flag);
	$battery132 = create_battery_voltage(0, $flag);
	$battery133 = create_battery_voltage(0, $flag);
	$battery134 = create_battery_voltage(0, $flag);
	$battery135 = create_battery_voltage(0, $flag);
	$battery136 = create_battery_voltage(0, $flag);
	$battery137 = create_battery_voltage(0, $flag);
	$battery138 = create_battery_voltage(0, $flag);
	$battery139 = create_battery_voltage(0, $flag);
	$battery140 = create_battery_voltage(0, $flag);
	$battery141 = create_battery_voltage(0, $flag);
	$battery142 = create_battery_voltage(0, $flag);
	$battery143 = create_battery_voltage(0, $flag);
	$battery144 = create_battery_voltage(0, $flag);
	$battery145 = create_battery_voltage(0, $flag);
	$battery146 = create_battery_voltage(0, $flag);
	$battery147 = create_battery_voltage(0, $flag);
	$battery148 = create_battery_voltage(0, $flag);
	$battery149 = create_battery_voltage(0, $flag);
	$battery150 = create_battery_voltage(0, $flag);
	$battery151 = create_battery_voltage(0, $flag);
	$battery152 = create_battery_voltage(0, $flag);
	$battery153 = create_battery_voltage(0, $flag);
	$battery154 = create_battery_voltage(0, $flag);
	$battery155 = create_battery_voltage(0, $flag);
	$battery156 = create_battery_voltage(0, $flag);
	$battery157 = create_battery_voltage(0, $flag);
	$battery158 = create_battery_voltage(0, $flag);
	$battery159 = create_battery_voltage(0, $flag);
	$battery160 = create_battery_voltage(0, $flag);	
	$battery161 = create_battery_voltage(0, $flag);
	$battery162 = create_battery_voltage(0, $flag);
	$battery163 = create_battery_voltage(0, $flag);
	$battery164 = create_battery_voltage(0, $flag);
	$battery165 = create_battery_voltage(0, $flag);
	$battery166 = create_battery_voltage(0, $flag);
	$battery167 = create_battery_voltage(0, $flag);
	$battery168 = create_battery_voltage(0, $flag);
	$battery169 = create_battery_voltage(0, $flag);
	$battery170 = create_battery_voltage(0, $flag);
	$battery171 = create_battery_voltage(0, $flag);
	$battery172 = create_battery_voltage(0, $flag);
	$battery173 = create_battery_voltage(0, $flag);
	$battery174 = create_battery_voltage(0, $flag);
	$battery175 = create_battery_voltage(0, $flag);
	$battery176 = create_battery_voltage(0, $flag);
	
	for($i=1; $i<50; $i++) {
		//TODO: 改为数组
		$battery1 .= ";" . create_battery_voltage($i, $flag);
		$battery2 .= ";" . create_battery_voltage($i, $flag);
		$battery3 .= ";" . create_battery_voltage($i, $flag);
		$battery4 .= ";" . create_battery_voltage($i, $flag);
		$battery5 .= ";" . create_battery_voltage($i, $flag);
		$battery6 .= ";" . create_battery_voltage($i, $flag);
		$battery7 .= ";" . create_battery_voltage($i, $flag);
		$battery8 .= ";" . create_battery_voltage($i, $flag);
		$battery9 .= ";" . create_battery_voltage($i, $flag);
		$battery10 .= ";" . create_battery_voltage($i, $flag);
		$battery11 .= ";" . create_battery_voltage($i, $flag);
		$battery12 .= ";" . create_battery_voltage($i, $flag);
		$battery13 .= ";" . create_battery_voltage($i, $flag);
		$battery14 .= ";" . create_battery_voltage($i, $flag);
		$battery15 .= ";" . create_battery_voltage($i, $flag);
		$battery16 .= ";" . create_battery_voltage($i, $flag);
		$battery17 .= ";" . create_battery_voltage($i, $flag);
		$battery18 .= ";" . create_battery_voltage($i, $flag);
		$battery19 .= ";" . create_battery_voltage($i, $flag);
		$battery20 .= ";" . create_battery_voltage($i, $flag);
		$battery21 .= ";" . create_battery_voltage($i, $flag);
		$battery22 .= ";" . create_battery_voltage($i, $flag);
		$battery23 .= ";" . create_battery_voltage($i, $flag);
		$battery24 .= ";" . create_battery_voltage($i, $flag);
		$battery25 .= ";" . create_battery_voltage($i, $flag);
		$battery26 .= ";" . create_battery_voltage($i, $flag);
		$battery27 .= ";" . create_battery_voltage($i, $flag);
		$battery28 .= ";" . create_battery_voltage($i, $flag);
		$battery29 .= ";" . create_battery_voltage($i, $flag);
		$battery30 .= ";" . create_battery_voltage($i, $flag);
		$battery31 .= ";" . create_battery_voltage($i, $flag);
		$battery32 .= ";" . create_battery_voltage($i, $flag);
		$battery33 .= ";" . create_battery_voltage($i, $flag);
		$battery34 .= ";" . create_battery_voltage($i, $flag);
		$battery35 .= ";" . create_battery_voltage($i, $flag);
		$battery36 .= ";" . create_battery_voltage($i, $flag);
		$battery37 .= ";" . create_battery_voltage($i, $flag);
		$battery38 .= ";" . create_battery_voltage($i, $flag);
		$battery39 .= ";" . create_battery_voltage($i, $flag);
		$battery40 .= ";" . create_battery_voltage($i, $flag);
		$battery41 .= ";" . create_battery_voltage($i, $flag);
		$battery42 .= ";" . create_battery_voltage($i, $flag);
		$battery43 .= ";" . create_battery_voltage($i, $flag);
		$battery44 .= ";" . create_battery_voltage($i, $flag);
		$battery45 .= ";" . create_battery_voltage($i, $flag);
		$battery46 .= ";" . create_battery_voltage($i, $flag);
		$battery47 .= ";" . create_battery_voltage($i, $flag);
		$battery48 .= ";" . create_battery_voltage($i, $flag);
		$battery49 .= ";" . create_battery_voltage($i, $flag);
		$battery50 .= ";" . create_battery_voltage($i, $flag);
		$battery51 .= ";" . create_battery_voltage($i, $flag);
		$battery52 .= ";" . create_battery_voltage($i, $flag);
		$battery53 .= ";" . create_battery_voltage($i, $flag);
		$battery54 .= ";" . create_battery_voltage($i, $flag);
		$battery55 .= ";" . create_battery_voltage($i, $flag);
		$battery56 .= ";" . create_battery_voltage($i, $flag);
		$battery57 .= ";" . create_battery_voltage($i, $flag);
		$battery58 .= ";" . create_battery_voltage($i, $flag);
		$battery59 .= ";" . create_battery_voltage($i, $flag);
		$battery60 .= ";" . create_battery_voltage($i, $flag);
		$battery61 .= ";" . create_battery_voltage($i, $flag);
		$battery62 .= ";" . create_battery_voltage($i, $flag);
		$battery63 .= ";" . create_battery_voltage($i, $flag);
		$battery64 .= ";" . create_battery_voltage($i, $flag);
		$battery65 .= ";" . create_battery_voltage($i, $flag);
		$battery66 .= ";" . create_battery_voltage($i, $flag);
		$battery67 .= ";" . create_battery_voltage($i, $flag);
		$battery68 .= ";" . create_battery_voltage($i, $flag);
		$battery69 .= ";" . create_battery_voltage($i, $flag);
		$battery70 .= ";" . create_battery_voltage($i, $flag);
		$battery71 .= ";" . create_battery_voltage($i, $flag);
		$battery72 .= ";" . create_battery_voltage($i, $flag);
		$battery73 .= ";" . create_battery_voltage($i, $flag);
		$battery74 .= ";" . create_battery_voltage($i, $flag);
		$battery75 .= ";" . create_battery_voltage($i, $flag);
		$battery76 .= ";" . create_battery_voltage($i, $flag);
		$battery77 .= ";" . create_battery_voltage($i, $flag);
		$battery78 .= ";" . create_battery_voltage($i, $flag);
		$battery79 .= ";" . create_battery_voltage($i, $flag);
		$battery80 .= ";" . create_battery_voltage($i, $flag);
		$battery81 .= ";" . create_battery_voltage($i, $flag);
		$battery82 .= ";" . create_battery_voltage($i, $flag);
		$battery83 .= ";" . create_battery_voltage($i, $flag);
		$battery84 .= ";" . create_battery_voltage($i, $flag);
		$battery85 .= ";" . create_battery_voltage($i, $flag);
		$battery86 .= ";" . create_battery_voltage($i, $flag);
		$battery87 .= ";" . create_battery_voltage($i, $flag);
		$battery88 .= ";" . create_battery_voltage($i, $flag);
		$battery89 .= ";" . create_battery_voltage($i, $flag);
		$battery90 .= ";" . create_battery_voltage($i, $flag);
		$battery91 .= ";" . create_battery_voltage($i, $flag);
		$battery92 .= ";" . create_battery_voltage($i, $flag);
		$battery93 .= ";" . create_battery_voltage($i, $flag);
		$battery94 .= ";" . create_battery_voltage($i, $flag);
		$battery95 .= ";" . create_battery_voltage($i, $flag);
		$battery96 .= ";" . create_battery_voltage($i, $flag);
		$battery97 .= ";" . create_battery_voltage($i, $flag);
		$battery98 .= ";" . create_battery_voltage($i, $flag);
		$battery99 .= ";" . create_battery_voltage($i, $flag);
		$battery100 .= ";" . create_battery_voltage($i, $flag);
		$battery101 .= ";" . create_battery_voltage($i, $flag);
		$battery102 .= ";" . create_battery_voltage($i, $flag);
		$battery103 .= ";" . create_battery_voltage($i, $flag);
		$battery104 .= ";" . create_battery_voltage($i, $flag);
		$battery105 .= ";" . create_battery_voltage($i, $flag);
		$battery106 .= ";" . create_battery_voltage($i, $flag);
		$battery107 .= ";" . create_battery_voltage($i, $flag);
		$battery108 .= ";" . create_battery_voltage($i, $flag);
		$battery109 .= ";" . create_battery_voltage($i, $flag);
		$battery110 .= ";" . create_battery_voltage($i, $flag);
		$battery111 .= ";" . create_battery_voltage($i, $flag);
		$battery112 .= ";" . create_battery_voltage($i, $flag);
		$battery113 .= ";" . create_battery_voltage($i, $flag);
		$battery114 .= ";" . create_battery_voltage($i, $flag);
		$battery115 .= ";" . create_battery_voltage($i, $flag);
		$battery116 .= ";" . create_battery_voltage($i, $flag);
		$battery117 .= ";" . create_battery_voltage($i, $flag);
		$battery118 .= ";" . create_battery_voltage($i, $flag);
		$battery119 .= ";" . create_battery_voltage($i, $flag);
		$battery120 .= ";" . create_battery_voltage($i, $flag);
		$battery121 .= ";" . create_battery_voltage($i, $flag);
		$battery122 .= ";" . create_battery_voltage($i, $flag);
		$battery123 .= ";" . create_battery_voltage($i, $flag);
		$battery124 .= ";" . create_battery_voltage($i, $flag);
		$battery125 .= ";" . create_battery_voltage($i, $flag);
		$battery126 .= ";" . create_battery_voltage($i, $flag);
		$battery127 .= ";" . create_battery_voltage($i, $flag);
		$battery128 .= ";" . create_battery_voltage($i, $flag);
		$battery129 .= ";" . create_battery_voltage($i, $flag);
		$battery130 .= ";" . create_battery_voltage($i, $flag);
		$battery131 .= ";" . create_battery_voltage($i, $flag);
		$battery132 .= ";" . create_battery_voltage($i, $flag);
		$battery133 .= ";" . create_battery_voltage($i, $flag);
		$battery134 .= ";" . create_battery_voltage($i, $flag);
		$battery135 .= ";" . create_battery_voltage($i, $flag);
		$battery136 .= ";" . create_battery_voltage($i, $flag);
		$battery137 .= ";" . create_battery_voltage($i, $flag);
		$battery138 .= ";" . create_battery_voltage($i, $flag);
		$battery139 .= ";" . create_battery_voltage($i, $flag);
		$battery140 .= ";" . create_battery_voltage($i, $flag);
		$battery141 .= ";" . create_battery_voltage($i, $flag);
		$battery142 .= ";" . create_battery_voltage($i, $flag);
		$battery143 .= ";" . create_battery_voltage($i, $flag);
		$battery144 .= ";" . create_battery_voltage($i, $flag);
		$battery145 .= ";" . create_battery_voltage($i, $flag);
		$battery146 .= ";" . create_battery_voltage($i, $flag);
		$battery147 .= ";" . create_battery_voltage($i, $flag);
		$battery148 .= ";" . create_battery_voltage($i, $flag);
		$battery149 .= ";" . create_battery_voltage($i, $flag);
		$battery150 .= ";" . create_battery_voltage($i, $flag);
		$battery151 .= ";" . create_battery_voltage($i, $flag);
		$battery152 .= ";" . create_battery_voltage($i, $flag);
		$battery153 .= ";" . create_battery_voltage($i, $flag);
		$battery154 .= ";" . create_battery_voltage($i, $flag);
		$battery155 .= ";" . create_battery_voltage($i, $flag);
		$battery156 .= ";" . create_battery_voltage($i, $flag);
		$battery157 .= ";" . create_battery_voltage($i, $flag);
		$battery158 .= ";" . create_battery_voltage($i, $flag);
		$battery159 .= ";" . create_battery_voltage($i, $flag);
		$battery160 .= ";" . create_battery_voltage($i, $flag);
		$battery161 .= ";" . create_battery_voltage($i, $flag);
		$battery162 .= ";" . create_battery_voltage($i, $flag);
		$battery163 .= ";" . create_battery_voltage($i, $flag);
		$battery164 .= ";" . create_battery_voltage($i, $flag);
		$battery165 .= ";" . create_battery_voltage($i, $flag);
		$battery166 .= ";" . create_battery_voltage($i, $flag);
		$battery167 .= ";" . create_battery_voltage($i, $flag);
		$battery168 .= ";" . create_battery_voltage($i, $flag);
		$battery169 .= ";" . create_battery_voltage($i, $flag);
		$battery170 .= ";" . create_battery_voltage($i, $flag);
		$battery171 .= ";" . create_battery_voltage($i, $flag);
		$battery172 .= ";" . create_battery_voltage($i, $flag);
		$battery173 .= ";" . create_battery_voltage($i, $flag);
		$battery174 .= ";" . create_battery_voltage($i, $flag);
		$battery175 .= ";" . create_battery_voltage($i, $flag);
		$battery176 .= ";" . create_battery_voltage($i, $flag);	
	}
	
	// $query = "INSERT INTO Battery_Data_YX values('','$vehicle','$date','$battery1','$battery2','$battery3','$battery4','$battery5','$battery6', '$battery7','$battery8','$battery9','$battery10'，'$battery11','$battery12','$battery13','$battery14','$battery15','$battery16','$battery17','$battery18','$battery19','$battery20'，'$battery21','$battery22','$battery23','$battery24','$battery25','$battery26','$battery27','$battery28','$battery29','$battery30'，'$battery31','$battery32','$battery33','$battery34','$battery35','$battery36','$battery37','$battery38','$battery39','$battery40'，'$battery41','$battery42','$battery43','$battery44','$battery45','$battery46','$battery47','$battery48','$battery49','$battery50'，'$battery51','$battery52','$battery53','$battery54','$battery55','$battery56','$battery57','$battery58','$battery59','$battery60'，'$battery61','$battery62','$battery63','$battery64','$battery65','$battery66','$battery67','$battery68','$battery69','$battery70'，'$battery71','$battery72','$battery73','$battery74','$battery75','$battery76','$battery77','$battery78','$battery79','$battery80'，'$battery81','$battery82','$battery83','$battery84','$battery85','$battery86','$battery87','$battery88','$battery89','$battery90'，'$battery91','$battery92','$battery93','$battery94','$battery95','$battery96','$battery97','$battery98','$battery99','$battery100','$battery101','$battery102','$battery103','$battery104','$battery105','$battery106','$battery107','$battery108','$battery109','$battery110'，'$battery111','$battery112','$battery113','$battery114','$battery115','$battery116','$battery117','$battery118','$battery119','$battery120'，'$battery121','$battery122','$battery123','$battery124','$battery125','$battery126','$battery127','$battery128','$battery129','$battery130'，'$battery131','$battery132','$battery133','$battery134','$battery135','$battery136','$battery137','$battery138','$battery139','$battery140'，'$battery141','$battery142','$battery143','$battery144','$battery145','$battery146','$battery147','$battery148','$battery149','$battery150'，'$battery151','$battery152','$battery153','$battery154','$battery155','$battery156','$battery157','$battery158','$battery159','$battery160'，'$battery161','$battery162','$battery163','$battery164','$battery165','$battery166','$battery167','$battery168','$battery169','$battery170','$battery171','$battery172','$battery173','$battery174','$battery175','$battery176')";
  
  // INSERT INTO `package_data_yx` (`Id`, `VehicleId`, `Number`, `Day`, `Temperature1`, `Temperature2`, `Temperature3`, `Temperature4`, `Temperature5`, `Temperature6`) VALUES
  
  //先插入前6个数据
  $query = "INSERT INTO Battery_Data_YX (Id, VehicleId, Day, Battery1, Battery2, Battery3, Battery4, Battery5, Battery6) VALUES ('', '$vehicle', '$date', '$battery1', '$battery2', '$battery3', '$battery4', '$battery5', '$battery6')";
  //echo $query ."\n";
  $result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  //再更新接下来的6个数据: Battery7-Battery12
  //$query = "UPDATE  where = AND ="
  $result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  
  //Battery13-Battery18
  
  //Battery19-Battery24
  
  //Battery25-Battery30
  
  //Battery31-Battery36
  
  //Battery37-Battery42
  
  //Battery42-Battery47
  
  //Battery48-Battery53
  
  //Battery54-Battery59
  
  //Battery60-Battery65
  
  //Battery25-Battery30
  
  //Battery25-Battery30
  
  //Battery25-Battery30
  
  //Battery25-Battery30
  
  //Battery25-Battery30
  
  //Battery25-Battery30
  
  
  
  
  //echo "已插入沂星".$vehicle."号车".$date."的所有单体电池数据\n";
}


/**
 * 生成SOC值
 * lizhongzhen@huakaienergy.com
 */
function create_soc($x, $flag) {
  switch($flag) {
  	case 0:
  		$y = -0.01*$x*$x -0.555*$x + 100.9; // 拟合曲线函数
  		break;
  	case 1:
  		$y = 0.0009 *$x*$x*$x -0.052*$x*$x -0.541*$x + 103.4; // 拟合曲线函数  
  		break;
  	case 2:
  		$y = 0.0004 *$x*$x*$x -0.033*$x*$x -0.586*$x + 101.4; // 拟合曲线函数 
  		break;
 	}
 	// 随机加(减)一个随机的[0,1]两位小数
 	if(rand(0,100)%2 == 0) {	
 		$y += dotrand(0,1,0,99);
 	} else {
 		$y -= dotrand(0,1,0,99);
 	}
  if($y > 100) {
    $y = 100; //SOC不能大于100
  } 
  return $y;
}

/**
 * 生成总电压值
 * lizhongzhen@huakaienergy.com
 */
function create_voltage($x, $flag) {
    switch($flag) {
  	case 0:
  		$y = -0.024*$x*$x*$x + 2.080*$x*$x -57.17*$x +596.6; // 拟合曲线函数
  		break;
  	case 1:
  		$y = -0.0004*$x*$x*$x*$x + 0.034*$x*$x*$x - 0.238*$x*$x -30.58*$x + 596.9; // 拟合曲线函数  
  		break;
  	case 2:
  		$y = 0.397*$x*$x -27.77*$x + 596.3;// 拟合曲线函数 
  		break;
 	}
 	// 加一个随机的[100,300]两位小数
 	if($y<300){	
 		$y += dotrand(100,300, 0,99);
 	}
 	return $y;
}

/**
 * 生成温度值 
 * lizhongzhen@huakaienergy.com
 */
function create_temperature($x, $flag) {
    switch($flag) {
  	case 0:
  		$y = 0.1925*$x + 10.8385; // 拟合曲线函数
  		break;
  	case 1:
  		$y = 0.1797*$x + 9.225; // 拟合曲线函数  
  		break;
  	case 2:
  		$y = 0.1823*$x + 9.95;// 拟合曲线函数 
  		break;
 	}
	// 加(减)一个随机的[1,2]两位小数
 	if(rand(0,100)%2==0){	
 		$y += dotrand(1,2,0,99);
 	} else {
 		$y -= dotrand(1,2,0,99);
 	}
 	return $y;
}

/**
 * 生成单体电压值
 * lizhongzhen@huakaienergy.com
 */
function create_battery_voltage($x, $flag){
  switch($flag) {
  	case 0:
  		$y = -0.00007 *$x*$x*$x + 0.003*$x*$x -0.047*$x + 3.503;// 拟合曲线函数
  		$y += dotrand(0, 1, 0, 999); //随机数
  		break;
  	case 1:
  		$y = -0.00007 *$x*$x*$x + 0.006*$x*$x -0.194*$x + 3.924; // 拟合曲线函数
  		$y += dotrand(0, 1, 0, 999); //随机数 
  		break;
  	case 2:
  		$y = -0.00008 *$x*$x*$x + 0.006*$x*$x -0.143*$x + 3.734; // 拟合曲线函数
  		$y += dotrand(0, 1, 0, 999); //随机数 
  		break;
 	}
 	// 加一个随机的[1,2]三位小数
 	if($y < 2.4) {
 		$y += dotrand(1, 2, 0, 999);
 	}
 	// 减一个随机的[0,1]三位小数
 	if($y > 4) {
 		$y -= dotrand(0, 1, 0, 999);
 	}
  return sprintf("%.3f", $y); //保留三位小数
}

/**
 * 生成随机小数 
 * lizhongzhen@huakaienergy.com
 */
function dotrand($startnum,$endnum,$sdot,$edot){
	$zerostr = "";
	$dotcount = strlen($edot);
	$newnum = rand($startnum,$endnum);
	$newdot = rand($sdot,$edot);
	$zerocount = $dotcount - strlen($newdot);
	if($zerocount>0){
		for($i=0; $i<$zerocount; $i++){
			$zerostr .= "0";
		}
	}
	$newdot = $zerostr . $newdot;
	return $newnum . "." . $newdot;
}

?>
