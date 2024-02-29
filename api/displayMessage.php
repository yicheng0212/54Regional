<?php
include 'db.php';
header('Content-Type: Application/json');

$sql = "SELECT id, name, messageNumber, email, phone, content, image_path, created_at, updated_at, deleted_at, displayEmail, displayPhone, is_top, admin_response FROM messages ORDER BY is_top DESC, created_at DESC";
$result = $conn->query($sql);

$messages = [];
if ($result->num_rows > 0) {
    $messages = $result->fetch_all(MYSQLI_ASSOC);
}
echo json_encode($messages);
$conn->close();