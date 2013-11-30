<style>
body {
	width: 100%;
}
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
   position: relative;
   width: 900px; 
   height: 400px;
   margin: 40px auto;
}
.select-arr {
    margin: 20px 0;
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
	left: -35px;
	top: 0;
    display: none;
}
.tab-t {
    position: absolute;
    width: 60px;
    right: -45px;
    top: 0;
    display: none;
}
.y-item {
	margin: 0;
	padding: 0;
	margin-bottom: 8px;
    height: 18px;
    line-height: 18px;
}
.y-item input, .y-item label {
    cursor: pointer;
}
.s-item {
    display: inline-block;
    width: 251px;
}
.s-item-last {
    width: 240px;
}
.s-gap {
    margin-bottom: 10px;
}
.label-line {
    display: inline-block;
    font-weight: bold;
}
.label-line-r {
    color: #FF0000;
}
.label-line-g {
    color: #00FF00;
}
.label-line-b {
    color: #0000FF;
}
</style>
<div class="select-arr">
    <form id="battery-query" action="<?php echo base_url('battery/index');?>" method="post">
        <div class="s-gap">
            <div class="s-item">
                <span class="select-label">
                    公司名称:
                </span>
                <select id="company-name" name="companyName">
                    <option value="">请选择</option>
                    <option value="众泰">众泰</option>
                    <option value="沂星">沂星</option>
                </select>
            </div>
            <div class="s-item">
                <span class="select-label">
                    电动汽车:
                </span>
                <select id="ele-car" name="eleCar">
                    <option value="">请选择</option>
                </select>
            </div>
            <div class="s-item">
                <span class="select-label">
                    电池组:
                </span>
                <select id="battery-arr" name="batteryArr">
                    <option value="">请选择</option>
                </select>
            </div>
            <div class="s-item s-item-last">
                <span class="select-label">
                    单体电池:
                </span>
                <select id="signal-battery" name="signalBattery">
                    <option value="">请选择</option>
                </select>
            </div>
        </div>
        <div class="s-gap">
            <div class="s-item">
                <span class="select-label">
                    采集日期:
                </span>
                <select name="colDate">
                    <option value="">请选择</option>
                    <option value="2013-11-27">2013-11-27</option>
                    <option value="2013-11-28">2013-11-28</option>
                    <option value="2013-11-29">2013-11-29</option>
                </select>
            </div>
            <div class="s-item">
                <span class="select-label">
                    采集间隔:
                </span>
                <input id="time-gap" type="text" readonly class="date b-date" value="10分钟" name="timeGap">
            </div>
            <div class="s-item"></div>
            <button id="sub-btn" class="b-btn b-btn-primary" >确认提交</button>
        </div>
    </form>
</div>
<div class="battery-container">
	<div class="y-cord">
		<p class="y-item">	
			<span>总电压</span>
            <span class="label-line label-line-r">-----</span>
		</p>
		<p class="y-item">
			<span>SOC</span>
            <span class="label-line label-line-g">-----</span>
		</p>
		<p class="y-item">
			<span>温度</span>
            <span class="label-line label-line-b">-----</span>
		</p>
	</div>
    <div class="tab-t">
        <p class="y-item">
            <input type="radio" value="放电" checked><label for="">放电</label>
        </p>
        <p class="y-item">
            <input type="radio" value="充电" disabled><label for="">充电</label>
        </p>
    </div>
    <img src="<?php echo base_url('jpgraph/1.png');?>" alt="" id="jpgraph">
</div>
<script type="text/javascript" src="<?php echo base_url('static/js/ajax_query.js');?>"></script>
<script type="text/javascript">
$(function() {
    hk.autoQueryInfo("company-name", "ele-car", "battery-arr", "signal-battery");
    $('#sub-btn').on('click', function(e) {
        var obj = hk.formCollect('battery-query'),
            flag = false;
        console.log(obj);

        if (!$('#ele-car').val()) {
            alert('电动汽车不能为空！');
            return false;
        }

        // 是否显示旁边的标识
        $("div.y-cord").show();
        $("div.tab-t").show();
        if ($('#battery-arr').val()) {
            $("div.y-cord").hide();
            $("div.tab-t").hide();
            flag = true;
        } 
        
        if ($('#time-gap').val() !== "10分钟") {
            alert('请采用默认的10分钟间隔!')
            return false;
        }

        if ($('#jpgraph_t'))
            $('#jpgraph_t').remove();

        $.ajax({
            url: '<?php echo base_url("battery/index");?>',
            data: obj,
            method: 'post',
            success: function(msg) {
                var obj = JSON.parse(msg);
                if (obj.code == 100) {
                    alert('获取信息失败,请重新获取!');
                    return;
                } else if (obj.code == 200) {
                    document.getElementById('jpgraph').src = "<?php echo base_url();?>" + obj.msg;

                    if (flag)
                        $('#jpgraph').after("<img src='<?php echo base_url();?>"+ obj.msg2 +"' id='jpgraph_t'>");
                }
            },
            error: function(msg) {
                console.log('error:' + msg);
            }
        });

        return false;
    });
});
</script>
