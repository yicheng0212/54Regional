<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登入</title>
    <?php include_once "link.php";?>
</head>
<body class="bg-warning">
<div class="container">
<?php include_once "header.php"; ?>
<div class="col-6 offset-3 mt-5">
<div class="card d-flex justify-content-center align-items-center p-3 shadow bg-light">
    <div class="container">
        <form id="loginForm">
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
                        window.location.href = 'admin.php';
                    } else {
                        alert(response);
                    }
                },
            });
        });

        $('#refresh_captcha').click(function() {
            $('#captchaImage').attr('src', 'captcha.php?' + new Date().getTime());
        });
    });
</script>
</body>
</html>