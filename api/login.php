<?php
session_start();
require './db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];

    if (isset($_SESSION['captcha']) && strtolower($captcha) === strtolower($_SESSION['captcha'])) {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userId = (int)$row['user_id'];

            // 設置用戶的會話信息
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;

            echo 'success';
        } else {
            echo "帳號或密碼錯誤";
        }
        $stmt->close();
    } else {
        echo "圖形驗證碼錯誤";
    }
} else {
    echo "invalid_request";
}

$conn->close();