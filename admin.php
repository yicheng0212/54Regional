<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>網站管理</title>
    <?php include_once "link.php";?>
</head>
<body class="bg-warning">
<div class="container">
<?php include_once "header.php"; ?>
    <div class="d-flex justify-content-center">
        <a href="admin.php?page=messages" class="text-dark m-1">
            <h2>留言管理</h2>
        </a>
        <a href="admin.php?page=bookings" class="text-dark m-1">
            <h2>訂房管理</h2>
        </a>
    </div>

    <div class="card p-3 mt-4 shadow bg-light">
        <?php
        $page = $_GET['page'] ?? 'messages';
        include $page == 'messages' ? 'AdminMessages.php' : 'AdminBookings.php';
        ?>
</div>
</div>
</body>
</html>