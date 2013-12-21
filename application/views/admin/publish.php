<link rel="stylesheet" href="<?php echo base_url('static/css/manage.css');?>" />
<form action="" id="publish_news">
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
    <textarea id="n-content" name="content"></textarea>
    <button id="n-sub">发 布</button>
</form>
<script type="text/javascript">
$(function() {
    $('#n-sub').on('click', function() {
        var arr = $('input[type=text], select, textarea', $('#publish_news')),
            i = 0, len = arr.length;

        for (; i < len; i++) {
            if (!$(arr[i]).val()) {
                alert($(arr[i]).prev().html().replace(':', '') + '不能为空！');
                return false;
            }
        }

        // 能到这里说明内容符合要求
        $.ajax({
            url: "<?php echo base_url('admin/save_news');?>",
            method: 'post',
            data: hk.formCollect('publish_news'),
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
