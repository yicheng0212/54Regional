<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $content = $_POST['content'];
    $display_email = isset($_POST['display_email']) ? 1 : 0;
    $display_phone = isset($_POST['display_phone']) ? 1 : 0;

    echo "接收到的数据：ID - $id, Name - $name, Email - $email, Phone - $phone, Content - $content, Display Email - $display_email, Display Phone - $display_phone";
    // 更新留言信息
    $stmt = $conn->prepare("UPDATE messages SET name = ?, email = ?, phone = ?, content = ?, display_email = ?, display_phone = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssssiii", $name, $email, $phone, $content, $display_email, $display_phone, $id);
    if ($stmt->execute()) {
        echo "留言已成功編輯";
    } else {
        echo "錯誤: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
