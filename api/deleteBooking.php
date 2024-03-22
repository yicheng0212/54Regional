<?php
include 'db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $stmt = $conn->prepare("DELETE FROM `bookings` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(["message" => '訂單刪除成功']);
$stmt->close();
$conn->close();