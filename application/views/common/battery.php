<!DOCTYPE html>
<meta charset="utf-8">
<head>
	<title>test</title>
</head>
<body>
<style>
select {
    display: inline-block;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555;
    vertical-align: middle;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.battery-container {
   width: 900px; 
   height: 400px;
   margin: 20px auto;
   border-left: 1px solid #eee;
   border-bottom: 1px solid #eee;
   position: relative;
}
.select-arr {
    margin: 20px 0 20px 50px;
}
.select-label {
    margin-left: 5px;
    font-size: 14px;
}
.b-date {
    margin-right: 20px;
}
input.date {
    width: 126px;
    height: 20px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555;
    vertical-align: middle;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.b-btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}
.b-btn-primary {
    color: #fff;
    background-color: #428bca;
    border-color: #357ebd;
}
.b-btn-primary:hover {
    color: #fff;
    background-color: #3276b1;
    border-color: #285e8e;
}
.y-cord {
	position: absolute;
	width: 60px;
	left: -65px;
	top: 0;
}
.y-item {
	margin: 0;
	padding: 0;
	margin-bottom: 8px;
}
</style>
<div class="select-arr">
	<span class="select-label">
		公司名称:
	</span>
	<select id="" name="">
		<option value="">请选择</option>
		<option value="">众泰</option>
		<option value="">宜兴</option>
	</select>
    <span class="select-label">
        电动汽车:
    </span>
    <select id="" name="">
        <option value="">请选择</option>
    </select>
    <span class="select-label">
        电池组:
    </span>
    <select id="" name="">
        <option value="">请选择</option>
    </select>
    <span class="select-label">
        单体电池:
    </span>
    <select id="" name="">
		<option value="">请选择</option>
    </select>
    <span class="select-label">
        开始日期：
    </span>
    <input type="text" class="date b-date">
    <button id="sub-btn" class="b-btn b-btn-primary">确认提交</button>
</div>
<div class="battery-container">
	<div class="y-cord">
		<p class="y-item">	
			<input type="radio" id="electric" name="yCord" value="电流" checked><label for="electric">电流</label>
		</p>
		<p class="y-item">
			<input type="radio" id="voltage" name="yCord" value="电压"><label for="voltage">电压</label>
		</p>
		<p class="y-item">
			<input type="radio" id="temp" name="yCord" value="温度"><label for="temp">温度</label>
		</p>
		<p class="y-item">
			<input type="radio" id="SOC" name="yCord" value="SOC"><label for="SOC">SOC</label>
		</p>
	</div>
    <img src="" alt="" id="jpgraph">
</div>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js">
</script>
<script type="text/javascript">
    $(function() {
        $.get("battery.js", function(data) {
			console.log(data);
			//alert(data);
		}, 'json');
    });
</script>
</body>
</html>