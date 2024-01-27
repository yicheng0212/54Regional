<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // 直接更新留言為已刪除狀態
    $stmt = $conn->prepare("UPDATE messages SET content = '此留言已被刪除', deleted_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "留言已刪除";
    } else {
        echo "留言刪除失敗：" . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
