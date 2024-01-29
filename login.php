<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登入</title>
    <?php include_once "link.php";?>
</head>
<body>
<?php include_once "header.php"; ?>
<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="container">
        <form id="loginForm" class="col-4 offset-4">
            <h2 class="text-center">網站管理--登入</h2>
            <div class="form-group">
                <label for="username">帳號:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">密碼:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="captcha">圖形驗證碼:</label>
                <img src="captcha.php" id="captchaImage" alt="驗證碼" />
                <button class="btn btn-sm btn-secondary" type="button" id="refresh_captcha">重新產生</button>
                <input type="text" class="form-control" id="captcha" name="captcha" required>
            </div>
            <button type="reset" class="btn btn-outline-secondary btn-lg btn-block">重設</button>
            <button type="submit" class="btn btn-secondary btn-lg btn-block">送出</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#loginForm').submit(function(event) {
            event.preventDefault();
            var username = $('#username').val();
            var password = $('#password').val();
            var captcha = $('#captcha').val();

            $.ajax({
                url: './api/login.php',
                type: 'POST',
                data: { username: username, password: password, captcha: captcha },
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'admin.php'; // 登入成功，導向管理頁面
                    } else {
                        alert(response); // 顯示錯誤信息
                    }
                },
                error: function() {
                    alert('登入請求失敗，請稍後再試。');
                }
            });
        });

        $('#refresh_captcha').click(function() {
            $('#captchaImage').attr('src', 'captcha.php?' + new Date().getTime());
        });
    });
</script>
</body>
</html>