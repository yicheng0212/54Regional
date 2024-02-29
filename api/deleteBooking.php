<?php
include 'db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);

    $success = $stmt->execute();
    $message = $success ? "訂單刪除成功" : "訂單刪除失敗";
    echo json_encode(["message" => $message], JSON_UNESCAPED_UNICODE);
    $stmt->close();
}
$conn->close();