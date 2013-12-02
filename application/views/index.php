<script type="text/javascript">
$(function() {
    var _timer = setTimeout(function() {
        if ($('img.photo-1').css('display') == "block") {
            $('img.photo-1').fadeOut('slow');
            $('img.photo-2').fadeIn('slow');
            $('div.slidebar a').removeClass('active');
            $('div.slidebar a')[1].className = "active";
        } else {
            $('img.photo-1').fadeIn('slow');
            $('img.photo-2').fadeOut('slow');
            $('div.slidebar a').removeClass('active');
            $('div.slidebar a')[0].className = "active";
        }
        setTimeout(arguments.callee, 5000);
    }, 5000);

    $('div.slidebar a').on('click', function() {
        if ($('img.photo-1').css('display') == "block") {
            $('img.photo-1').fadeOut('slow');
            $('img.photo-2').fadeIn('slow');
            $('div.slidebar a').removeClass('active');
            $('div.slidebar a')[1].className = "active";
        } else {
            $('img.photo-1').fadeIn('slow');
            $('img.photo-2').fadeOut('slow');
            $('div.slidebar a').removeClass('active');
            $('div.slidebar a')[0].className = "active";
        }
    });
});
</script>
<div class="photo-show">
	<img class="photo-1" src="<?php echo base_url('static/img/1.jpg');?>">
    <img class="photo-2" style="display:none;" src="<?php echo base_url('static/img/2.jpg');?>" alt="" />
    <div class="slidebar">
        <a href="javascript:void(0);" class="active">1</a>
        <a href="javascript:void(0);">2</a>
    </div>
</div>
<div class="intro-container clearfix">
    <div class="i-left">
        <div class="intro-title">
            <a href="#">公司动态 >></a>
            <hr/>
        </div>
    </div>
    <div class="i-right">
        <div class="intro-title">
            <a href="#">产品中心 >></a>
            <hr/>
        </div>
    </div>
</div>
<div class="intro-container clearfix">
    <div class="i-left">
        <div class="intro-title">
            <a href="#">工程案例 >></a>
            <hr/>
        </div>
    </div>
    <div class="i-right">
        <div class="intro-title">
            <a href="#">合作伙伴 >></a>
            <hr/>
        </div>
    </div>
</div>
