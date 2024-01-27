<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $message_number = $_POST['message_number'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $content = $_POST['content'];

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // 確保目標文件夾存在
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/image/"; // 這裡添加了 $_SERVER['DOCUMENT_ROOT']
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // 安全地處理文件名
        $filename = uniqid() . "_" . basename($_FILES['image']['name']);
        $image_path = $target_dir . $filename; // 使用完整路徑

        // 移動文件
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo "錯誤：無法移動上傳的文件。";
        } else {
            $image_path = "/image/" . $filename; // 這是保存到數據庫的路徑
        }
    }

    $stmt = $conn->prepare("INSERT INTO messages (name, message_number, email, phone, content, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $message_number, $email, $phone, $content, $image_path);

    if ($stmt->execute()) {
        echo "新增留言成功";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
