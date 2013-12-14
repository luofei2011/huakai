<style type="text/css">
    .sbms-logo {
        height: 80px;
    }
    .sbms-step {
        margin: 10px 0;
        width: 100%;
    }
    .car-list {
        margin-top: 20px;
    }
    .car-item-left, .car-item-right {
        float: left;
        width: 48%;
    }
    .car-item-right {
        float: right;
    }
    .car-list-header {
        display: inline-block;
        color: #0089CF;
        background: #E1F8FE;
        width: 100%;
        padding: 5px 0;
        text-indent: 6px;
        margin-bottom: 15px;
    }
    .car-list table {
        float: left;
        width: 48%;
    }
    .car-list thead {
        font-size: 14px;
        font-weight: bold;
        color: #000;
    }
</style>
<div class="content">
    <div class="inner">
        <img class="sbms-logo" src="<?php echo base_url('static/img/logo/sbms.jpg');?>">
        <p>华凯公司SMBS系统，区别于市面上传统的电池管理系统，强大的主动均衡模块功能以及BI支撑的故障诊断功能在对电池系统的监控和管理方面更加智能和强大，能更加有效的延长电池的使用期。公司产品采用模块化设计的方式，更加灵活方便，可以按照客户的实际需求提供定制化的解决方案。目前该产品主要应用在新能源汽车领域，移动通信领域和分布式储能领域。</br>
特点：
        </p>
        <ul>
            <li>● 电压、电流、温度等参数采集精度高；</li>
            <li>● 具备过放、过充、过流、温度等告警及保护功能；</li>
            <li>● 具备二次下电功能，电池单体电压或者总电压过低时自动下电保护；</li>
            <li>● 支持远程监控和报警；</li>
            <li>● 客户可以通过上位机软件自行设置报警和保护门阈值；</li>
            <li>● 静态的双向主动均衡技术，减少了电池“短板现象”，延长其使用寿命。</li>
            <li>● 先进的内阻检测方法，保障了SOC估算准确和故障诊断的精确度。</li>
            <li>● 丰富诊断机制支撑，数据智能分析，保障系统安全运行。</li>
        </ul>
        <img class="sbms-step" src="<?php echo base_url('static/img/logo/sbms_step.png');?>">
        <div class="car-list clearfix">
            <div class="car-item-left">
                <span class="car-list-header">01 电动公交车</span>
                <table>
                    <thead>
                        <tr>
                            <td>电动公交车</td>
                        </tr>
                    </thead>
                    <tr>
                        <td>电池规格：180AH</td>
                    </tr>
                    <tr>
                        <td>串联方式：2并180串</td>
                    </tr>
                    <tr>
                        <td>续驶里程：160公里</td>
                    </tr>
                </table>
                <img src="<?php echo base_url('static/img/logo/car_01.png');?>" class="car-label">
            </div>
            <div class="car-item-right">
                <span class="car-list-header">02 电动轿车</span>
                <table>
                    <thead>
                        <tr>
                            <td>电动轿车</td>
                        </tr>
                    </thead>
                    <tr>
                        <td>电池规格：100AH</td>
                    </tr>
                    <tr>
                        <td>串联方式：100串</td>
                    </tr>
                    <tr>
                        <td>续驶里程：230公里</td>
                    </tr>
                </table>
                <img src="<?php echo base_url('static/img/logo/car_02.png');?>" class="car-label">
            </div>
        </div>
    </div>
</div>
