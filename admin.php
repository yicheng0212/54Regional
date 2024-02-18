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
    <div class="d-flex align-items-center justify-content-center">
        <div class="col-md-auto">
            <a href="admin.php?page=messages" class="text-decoration-none text-dark">
                <h2 class="mb-0">留言管理</h2>
            </a>
        </div>
        <div class="col-md-auto">
            <a href="admin.php?page=bookings" class="text-decoration-none text-dark">
                <h2 class="mb-0">訂房管理</h2>
            </a>
        </div>
    </div>

    <div class="card p-3 mt-4 shadow bg-light">
    <?php
    $page = $_GET['page'] ?? 'messages';
    switch ($page) {
        case 'messages':
            include 'AdminMessages.php';
            break;
        case 'bookings':
        default:
            include 'AdminBookings.php';
            break;
    }
    ?>
</div>
</div>
</body>
</html>