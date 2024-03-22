<?php
session_start();
if (!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>網站管理</title>
    <?php include 'link.php';?>
</head>
<body class="bg-warning">
<div class="container">
    <?php include 'header.php';?>
    <div class="d-flex justify-content-center">
        <a href="admin.php?page=message" class="text-dark m-1">
            <h2>留言管理</h2>
        </a>
        <a href="admin.php?page=booking" class="text-dark m-1">
            <h2>訂房管理</h2>
        </a>
    </div>
    <div class="card p-3 mt-4 shadow bg-light">
    <?php
    $page = $_GET['page'] ?? 'message';
    include $page ==='message' ? 'AdminMessages.php' : 'AdminBookings.php';
    ?>
    </div>
</div>
</body>
</html>
