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
</style>
<div class="select-arr">
    <span class="select-label">
        电动汽车:
    </span>
    <select id="" name="">
        <option value="">电动汽车1</option>
        <option value="">电动汽车2</option>
        <option value="">电动汽车3</option>
        <option value="">电动汽车4</option>
    </select>
    <span class="select-label">
        电池组:
    </span>
    <select id="" name="">
        <option value="">电动汽车1</option>
        <option value="">电动汽车2</option>
        <option value="">电动汽车3</option>
        <option value="">电动汽车4</option>
    </select>
    <span class="select-label">
        单体电池:
    </span>
    <select id="" name="">
        <option value="">电动汽车1</option>
        <option value="">电动汽车2</option>
        <option value="">电动汽车3</option>
        <option value="">电动汽车4</option>
    </select>
    <span class="select-label">
        开始日期：
    </span>
    <input type="text" class="date b-date">
    <button id="sub-btn" class="b-btn b-btn-primary">确认提交</button>
</div>
<div class="battery-container">
    <img src="<?php echo base_url('jpgraph/1.png');?>" alt="" id="jpgraph">
</div>
<script type="text/javascript">
    $(function() {
        $.ajax({
            method: 'post',
            url: '/welcome/'
            success: function(data) {
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
</script>
