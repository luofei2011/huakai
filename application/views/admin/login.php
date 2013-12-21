<form action="">
    <label for="username">用户名：</label>
    <input type="text" id="username">
    <label for="pwd">密码：</label>
    <input type="text" id="pwd">
    <input type="submit" value="登录" id="submit">
</form>
<script type="text/javascript">
    $(function() {
        $('#submit').on('click', function() {
            $.ajax({
                url: "<?php echo base_url('login/index');?>",
                method: 'post',
                data: {'username': $('#username').val(), 'password': $('#pwd').val()},
                success: function(msg) {
                    if (msg !== 'false') {
                        window.location.href = "<?php echo base_url();?>" + msg;
                    } else {
                        alert('登录信息有误，请仔细检查！');
                    }
                }
            });
            return false;
        });
    });
</script>
