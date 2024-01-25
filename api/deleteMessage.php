<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $message_number = $_POST['message_number'];

    // 驗證留言編號
    $stmt = $conn->prepare("SELECT message_number FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($db_message_number);
    $stmt->fetch();
    $stmt->close();

    if ($db_message_number === $message_number) {
        $stmt = $conn->prepare("UPDATE messages SET content = '此留言已被刪除', deleted_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo "留言已刪除";
    } else {
        echo "留言編號不正確";
    }
}

$conn->close();