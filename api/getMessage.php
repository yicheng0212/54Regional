<?php
include 'db.php';
header('Content-Type: application/json');
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT id, name, messageNumber, email, phone, content, displayEmail, displayPhone, is_top, admin_response FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    }
$stmt->close();
$conn->close();