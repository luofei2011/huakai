<style type="text/css">
</style>
<div class="content">
    <div class="inner">
        <?php
        for($i = 1; $i <= 11; $i++) {
        ?>
        <a href="#">
        <?php
        if ($i < 10) {
        ?>
        <img src="<?php echo base_url('static/img/logo/');?>/logo_0<?php echo $i;?>.jpg">
        <?php
        } else {
        ?>
        <img src="<?php echo base_url('static/img/logo/');?>/logo_<?php echo $i;?>.jpg">
        <?php
        }
        ?>
        </a>
        <?php
        }
        ?>
    </div>
</div>
