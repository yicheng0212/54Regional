<?php
include 'db.php';
header('Content-Type: application/json');

    // 從 POST 數據中提取信息
    $name = $_POST['name'] ?? '';
    $messageNumber = $_POST['messageNumber'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $content = $_POST['content'] ?? '';
    $image_path = isset($_POST['image']) ? "./image/" . $_POST['image'] : '';

    $stmt = $conn->prepare("INSERT INTO messages (name, messageNumber, email, phone, content, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $messageNumber, $email, $phone, $content, $image_path);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "新增留言成功"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }
    $stmt->close();
$conn->close();