<style type="text/css">
    .content {
        margin: 0;
    }
    .c-slider {
        width: 12%;
    }
    .main {
        margin: 20px 0;
        padding: 5px 0;
        font-size: 14px;
    }
    .c-content {
        width: 85%;
        margin-left: 28px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .main-inner {
        margin-top: -4px;
    }
    .c-content-main {
        padding: 10px;
    }
    .s-item {
        line-height: 25px;
        margin-bottom: 5px;
    }
    .s-item a {
        display: inline-block;
        width: 100px;
        background: #1f4e7c;
        color: #fff;
    }
    .s-item-hover a {
        background: #417bb6;
    }
    .icon-triangle {
        width: 0;
        height: 0;
        font-size: 0;
        border-left: 12px solid #1f4e7c;
        border-bottom: 12px solid #fff;
        border-top: 12px solid #fff;
        vertical-align: top;
    }
    .s-item-hover .icon-triangle {
        border-left: 12px solid #417bb5;
    }
</style>
<script type="text/javascript">
    $(function() {
        $('div.c-content-main').load($('.s-item a')[0].href);
        $('.s-item').hover(function() {
            $(this).addClass('s-item-hover');
        }, function() {
            $(this).removeClass('s-item-hover');
        });
        $('.s-item a').on('click', function() {
            $('div.c-content-main').load(this.href);
            return false;
        });
    });
</script>
<div class="main grid-1004">
    <!--左侧导航条-->
    <div class="main-inner clearfix">
        <div class="c-slider left">
            <?php
            foreach($nav as $item) {
            ?>
            <div class="s-item"><a href="<?php echo $item['url'];?>"><?php echo $item['name'];?></a><b class="icon-triangle"></b></div>
            <?php
            }
            ?>
        </div>
        <!--右侧具体信息-->
        <div class="c-content left">
            <div class="c-content-main"></div>
        </div>
    </div>
</div>
