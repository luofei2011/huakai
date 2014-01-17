<div class="row-fluid">
    <div class="span8">
        <div class="row-fluid">
            <div class="span5 hk-nav">
                <div class="hk-con">
                    <?php
                    foreach($category as $item) {
                    ?>
                    <p>
                    <?php echo $item['name'];?>
                    </p>
                    <?php
                    }
                    ?>
                    <button type="button" class="btn btn-primary btn-add" data-click="0">添加</button>
                </div>
            </div>
        </div>
    </div>
    <div class="save-container">
        <input type="hidden" value="<?php echo base_url('admin/save_manage_category');?>" id="url">
        <input type="text" class="form-controll" id="add-text">
        <button type="button" class="btn btn-primary" id="btn-save">保存</button>
    </div>
</div>
<script type="text/javascript">
$(function() {
    $('#btn-save').on('click', function() {
        $.ajax({
            url: $('#url').val(),
            method: 'post',
            data: {data: $('#add-text').val()},
            success: function(msg) {
                if (msg === "OK") {
                    remove_cover();
                    $('#content').load("<?php echo base_url('admin/show_manage_category');?>");
                } else {
                    alert('添加失败，请重新添加！');
                }
            }
        });
    });
});
</script>
