<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $messageNumber = $_POST['messageNumber'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $content = $_POST['content'];

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/image/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }


        $filename = uniqid() . "_" . basename($_FILES['image']['name']);
        $image_path = $target_dir . $filename;

        // 移動文件
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            echo "錯誤：無法移動上傳的文件。";
        } else {
            $image_path = "/image/" . $filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO messages (name, messageNumber, email, phone, content, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $messageNumber, $email, $phone, $content, $image_path);

    if ($stmt->execute()) {
        echo "新增留言成功";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
