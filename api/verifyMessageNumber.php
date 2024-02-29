<?php
include 'db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$messageNumber = $_POST['messageNumber'] ?? '';

$stmt = $conn->prepare("SELECT messageNumber FROM messages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dbMessageNumber = $result->fetch_assoc()['messageNumber'] ?? null;
$stmt->close();

$response = [
    'status' => $dbMessageNumber === $messageNumber ? "valid" : "invalid",
    'id' => $id
];

echo json_encode($response);
$conn->close();