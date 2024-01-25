<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $message_number = $_POST['message_number'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $content = $_POST['content'];

    // 驗證留言編號
    $stmt = $conn->prepare("SELECT message_number FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($db_message_number);
    $stmt->fetch();
    $stmt->close();

    if ($db_message_number === $message_number) {
        $stmt = $conn->prepare("UPDATE messages SET name = ?, email = ?, phone = ?, content = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $content, $id);
        $stmt->execute();
        $stmt->close();
        echo "留言已成功編輯";
    } else {
        echo "留言編號不正確";
    }
}

$conn->close();