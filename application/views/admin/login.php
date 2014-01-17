<style type="text/css">
.login-container {
    width: 200px;
    margin: 20px auto;
    padding: 15px 20px;
    border: 1px solid #bbb;
}
.login-container input {
    width: auto;
}
.login-item {
    margin: 10px 0;
}
.text-center {
    text-align: center;
}
.login-title {
    text-align: center;
    font-size: 14px;
    font-weight: bold;
}
</style>
<div class="login-container">
    <div class="login-title">
        <span>管理员登录</span>
    </div>
    <form action="<?php echo base_url('login/index');?>">
        <div class="login-item">
            <label for="username">用户名：</label>
            <input type="text" id="username" placeholder="请输入用户名">
        </div>
        <div class="login-item">
            <label for="pwd">密码：</label>
            <input type="password" id="pwd">
        </div>
        <div class="login-item text-center">
            <input class="btn btn-primary" type="submit" value="登 录" id="submit">
        </div>
    </form>
</div>
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
