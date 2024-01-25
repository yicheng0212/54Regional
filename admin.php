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
<body>
<?php include_once "header.php";
?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-auto">
            <a href="admin.php?page=messages" class="text-decoration-none">
                <h3>留言管理</h3>
            </a>
        </div>
        <div class="col-md-auto">
            <a href="admin.php?page=bookings" class="text-decoration-none">
                <h3>訂房管理</h3>
            </a>
        </div>
    </div>
    <?php
    include "./api/db.php";
    $page = $_GET['page'] ?? 'messages';
    switch ($page) {
        case 'bookings':
            include 'AdminBookings.php';
            break;
        case 'messages':
        default:
            include 'AdminMessages.php';
            break;
    }
    ?>
</div>
<script>

</script>
</body>
</html>