<?php
include 'db.php';
header('Content-Type: application/json');

    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "留言已成功删除。"]);
    }
$stmt->close();
$conn->close();