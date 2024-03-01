<?php
include 'db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $stmt = $conn->prepare("DELETE FROM `bookings` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $message = $success ? "訂單刪除成功" : "訂單刪除失敗";
    echo json_encode(["message" => $message]);
$stmt->close();
$conn->close();