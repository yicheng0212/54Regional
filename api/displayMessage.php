<?php
include 'db.php';

// 修改SQL語句，先按is_top降序排列，然後按created_at降序排列
$sql = "SELECT id, name, messageNumber, email, phone, content, image_path, created_at, updated_at, deleted_at, display_email, display_phone, is_top, admin_response FROM messages ORDER BY is_top DESC, created_at DESC";
$result = $conn->query($sql);

$messages = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['content'] = htmlspecialchars($row['content']);
        $row['name'] = htmlspecialchars($row['name']);
        $row['email'] = htmlspecialchars($row['email']);
        $row['phone'] = htmlspecialchars($row['phone']);
        $row['image_path'] = htmlspecialchars($row['image_path']);
        $row['admin_response'] = htmlspecialchars($row['admin_response']);
        $messages[] = $row;
    }
}

echo json_encode($messages);
$conn->close();
