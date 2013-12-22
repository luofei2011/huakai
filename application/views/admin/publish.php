<script type="text/javascript" src="<?php echo base_url('static/js/plugin/ueditor/ueditor.config.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/js/plugin/ueditor/ueditor.all.min.js');?>"></script>
<link rel="stylesheet" href="<?php echo base_url('static/css/manage.css');?>" />
<form action="<?php echo base_url('admin/save_news');?>" id="publish_news" method="post">
    <p>新闻标题:</p>
    <input type="text" placeholder="请在这里输入你的新闻标题" id="n-title" name="title">
    <p>新闻类别:</p>
    <select id="n-category" name="category">
    <?php 
    foreach($category as $item) {
    ?>
        <option value="<?php echo $item['name']?>"><?php echo $item['name']?></option>
    <?php
    }
    ?>
    </select>
    <p>新闻内容:</p>
    <textarea id="n-content" name="content">hey</textarea>
    <button id="n-sub" class="btn btn-primary">发 布</button>
    <button id="n-save" class="btn btn-primary">保 存</button>
</form>
<script type="text/javascript">
var editor = new UE.ui.Editor();
editor.render('n-content');
$(function() {
    $('#n-save').on('click', function() {
        alert('保存功能还不能用，请谨慎选择！');
        var content = editor.getContent();
        console.log(content);
        return false;
    });
    $('#n-sub').on('click', function() {
        var arr = $('input[type=text], select, textarea', $('#publish_news')),
            i = 0, len = arr.length,data = {};

        for (; i < len; i++) {
            if (!$(arr[i]).val()) {
                alert($(arr[i]).prev().html().replace(':', '') + '不能为空！');
                return false;
            }
        }
        data = hk.formCollect('publish_news');
        data.content = editor.getContent();

        // 能到这里说明内容符合要求
        $.ajax({
            url: "<?php echo base_url('admin/save_news');?>",
            method: 'post',
            data: data,
            success: function(msg) {
                if (msg !== 'false') {
                    // TODO 添加保存不发布功能
                    alert('发布成功！');
                    window.location.reload();
                } else {
                    alert('发布失败，请重新发布！');
                }
            }
        });

        return false;
    });
});
</script>
