<?php
include 'db.php';
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT id, name, messageNumber, email, phone, content, display_email, display_phone, is_top, admin_response FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(["message" => "沒有留言"]);
    }

    $stmt->close();
}

$conn->close();
