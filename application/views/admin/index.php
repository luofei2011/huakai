<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理员界面</title>
    <link rel="stylesheet" href="<?php echo base_url('static/css/common.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('static/css/admin/bootstrap-responsive.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('static/css/admin/bootstrap-cerulean.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('static/css/admin/admin.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('static/css/admin/charisma-app.css');?>" />
</head>
<body>
<header>
    <div class="header">
        <span class="head-title">哈尔滨华凯电能科技有限公司管理后台</span>
        <div class="head-logout">
            <a href="<?php echo base_url('admin/logout');?>">安全退出</a>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2 main-menu-span">
            <div class="well nav-collapse sidebar-nav">
                <ul class="nav nav-tabs nav-stacked main-menu" id="navbar">
                    <li>
                    <a href="<?php echo base_url();?>" class="ajax-link">
                        <i class="icon-home"></i>
                        <span>主页</span>
                    </a>
                    </li>
                    <li>
                    <a href="<?php echo base_url('admin/manage/nav_edit');?>" class="ajax-link">
                        <i class="icon-list-alt"></i>
                        <span>导航条编辑</span>
                    </a>
                    </li>
                    <li>
                    <a href="<?php echo base_url('admin/publish');?>" class="ajax-link">
                        <i class="icon-font"></i>
                        <span>新闻发布</span>
                    </a>
                    </li>
                    <li>
                    <a href="" class="ajax-link">
                        <i class="icon-edit"></i>
                        <span>新闻管理</span>
                    </a>
                    </li>
                    <li>
                    <a href="<?php echo base_url('admin/show_manage_category');?>" class="ajax-link">
                        <i class="icon-calendar"></i>
                        <span>新闻类别管理</span>
                    </a>
                    </li>
                    <li>
                    <a href="" class="ajax-link">
                        <i class="icon-folder-open"></i>
                        <span>回收站</span>
                    </a>
                    </li>
                    <li>
                    <a href="" class="ajax-link">
                        <i class="icon-lock"></i>
                        <span>密码管理</span>
                    </a>
                    </li>
                    <li>
                    <a href="" class="ajax-link">
                        <i class="icon-home"></i>
                        <span>管理界面一</span>
                    </a>
                    </li>
                    <li>
                    <a href="" class="ajax-link">
                        <i class="icon-home"></i>
                        <span>管理界面一</span>
                    </a>
                    </li>
                    <li>
                    <a href="" class="ajax-link">
                        <i class="icon-home"></i>
                        <span>管理界面一</span>
                    </a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="content" class="span10">
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('static/js/jquery.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/js/admin.js');?>"></script>
<script type="text/javascript">
    $(function() {
        var content = $('#content');
        $('#navbar a').click(function() {
            content.load(this.href);
            return false;
        });
    });
</script>
