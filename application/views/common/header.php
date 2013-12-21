<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" href="<?php echo base_url('static/css/common.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('static/css/main.css');?>" />
    <script type="text/javascript" src="<?php echo base_url('static/js/jquery.js');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('static/js/core.js');?>"></script>
</head>
<body>
<div class="wrapper">
    <div class="grid-1004">
        <div class="header">
            <?php
            if (!$header) {
            ?>
            <div class="header-top">
                <div class="company-name">
                    <p>哈尔滨华凯电能科技有限公司</p>
                </div>
				<div class="header-title">
				Better air quality, better life with eCars
				</div>
            </div>
            <?php } ?>
            <div class="header-item">
                <ul class="header-ul">
                  <li>
                    <a href="<?php echo base_url();?>">首页</a>
                  </li>
                  <li>
                    <a href="">走进华凯</a>
                    <ul>
                        <li>
                        <a href="<?php echo base_url('intro/index/company_profile');?>">公司简介</a>
                        </li>
                        <li>
                        <a href="<?php echo base_url('intro/index/company_event');?>">公司大事记</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('intro/index/company_strategy');?>">发展战略</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('intro/partners');?>">合作伙伴</a>
                        </li>
                    </ul>
                  </li>
                  <li>
                    <a href="">新闻动态</a>
                    <ul>
                        <li>
                            <a href="#">行业新闻</a>
                        </li>
                        <li>
                            <a href="#">公司动态</a>
                        </li>
                    </ul>
                  </li>
                  <li>
                    <a href="">技术与产品</a>
                    <ul>
                        <li>
                        <a href="<?php echo base_url('intro/server_info');?>">S-BMS</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('intro/car_net');?>">车联网</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('battery');?>">电池数据分析</a>
                        </li>
                    </ul>
                  </li>
                  <li>
                    <a href="https://huakaienergy.com/mail" target="_blank">服务支持</a>
                    <ul>
                        <li>
                            <a href="#">售后</a>
                        </li>
                        <li>
                        <a href="<?php echo base_url('intro/down');?>">下载中心</a>
                        </li>
                        <li>
                            <a href="#">联系我们</a>
                        </li>
                    </ul>
                  </li>
                </ul>
                <div class="search">
                    <form action="">
                        <input type="text" name="keyword" id="keyword" placeholder="请输入搜索的关键字">
                        <input type="submit" id="query-btn">
                    </form>
                </div>
            </div>
            <!--header-item end!!!-->
        </div>
        <!--header end-->
