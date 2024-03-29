<?php
include "db.php";
header('Content-Type: application/json');

    $id = $_POST['id'];

    $conn->query("UPDATE messages SET is_top = FALSE");

    $stmt = $conn->prepare("UPDATE messages SET is_top = TRUE WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => '成功置頂']);
    }
$stmt->close();
$conn->close();