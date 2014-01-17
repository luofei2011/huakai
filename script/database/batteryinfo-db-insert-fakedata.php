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
		for($i=1;$i<9;$i++) {
			create_vehicleX_packageN_date($j+1, $i, "2012-12-10", $j);
		}
	}	
	// 插入沂星1-3号车1-8号电池组2012-12-11的数据
	for ($j=0; $j<3; $j++) {
		for($i=1;$i<9;$i++) {
			create_vehicleX_packageN_date($j+1, $i, "2012-12-11", $j);
		}
	}
	// 插入沂星1-3号车1-8号电池组2012-12-12的数据
	for ($j=0; $j<3; $j++) {
		for($i=1;$i<9;$i++) {
			create_vehicleX_packageN_date($j+1, $i, "2012-12-12", $j);
		}
	}

/*********************** 单体电池相关数据 ****************************/

	//-- 插入沂星1-3号车2012-12-10单体电池数据表
	for($i=0;$i<3;$i++){	
		create_vehicleX_battery_date($i+1, "2012-12-10", $i);
	}	
	//-- 插入沂星1-3号车2012-12-11单体电池数据表
	for($i=0;$i<3;$i++) {	
		create_vehicleX_battery_date($i+1, "2012-12-11", $i);
	}
	//-- 插入沂星1号车2012-12-12单体电池数据表	
	for($i=0;$i<3;$i++) {
		create_vehicleX_battery_date($i+1, "2012-12-12", $i);
	}


/******** 生成数据相关函数 ***********/

/**
 * 生成沂星X号车整车YYYY-MM-DD的关数据
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
	$temperature = array();
	for($i=0; $i<6; $i++){
		$temperature[$i] = create_temperature(0, $flag);
	}
	for($i=1; $i<50; $i++) {
		for($j=0;$j<6;$j++) {
			$temperature[$j] .= ";" .create_temperature($i, $flag); 
		}
	}	
  $query = "INSERT INTO Package_Data_YX values('','$vehicle','$package','$date',
  				'$temperature[0]','$temperature[1]','$temperature[2]','$temperature[3]', '$temperature[4]', '$temperature[5]')";
  $result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  echo "已插入沂星".$vehicle."号车".$package."号电池组".$date."的数据\n";
}


/**
 * 生成沂星X号车YYYY-MM-DD的单体电池数据表
 * lizhongzhen@huakaienergy.com
 */
function create_vehicleX_battery_date($vehicle, $date, $flag) {
	global $dbc;
	$battery = array();
	for($i=0;$i<176;$i++) {
		$battery[$i] = create_battery_voltage(0, $flag); 
	}
	for($i=1; $i<50; $i++) {
		for($j=0;$j<176;$j++) {
			$battery[$j] .= ";" . create_battery_voltage($i, $flag);
		}
	}  
  //先插入前6个数据
  $query = "INSERT INTO Battery_Data_YX (VehicleId, Day, Battery1, Battery2, Battery3, Battery4, Battery5, Battery6) VALUES 
  					('$vehicle', '$date', '$battery[0]', '$battery[1]', '$battery[2]', '$battery[3]', '$battery[4]', '$battery[5]')";
  //echo $query ."\n";
  $result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  
  //再更新接下来的4个数据: Battery7-Battery10 ...... 以此类推
  for($i=6;$i<173;$i=$i+4) {
  	$a=$i+1;
  	$b=$i+2;
  	$c=$i+3;
  	$query = "UPDATE Battery_Data_YX SET Battery".($i+1)."='$battery[$i]',Battery".($i+2)."='$battery[$a]',Battery".($i+3)."='$battery[$b]',Battery".($i+4)."='$battery[$c]' WHERE VehicleId='$vehicle' AND Day='$date'";
  	$result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  }
  //更新最后剩下的两个数据：Battery175-Battery176
  $query = "UPDATE Battery_Data_YX SET Battery175='$battery[174]', Battery176='$battery[174]' WHERE VehicleId='$vehicle' AND Day='$date'";
  $result = mysqli_query($dbc, $query) or die("mysqli_error($dbc)\n");
  echo "已插入沂星".$vehicle."号车".$date."的所有单体电池数据\n";
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
