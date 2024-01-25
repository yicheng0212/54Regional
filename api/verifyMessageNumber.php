<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $message_number = $_POST['message_number'];

    $stmt = $conn->prepare("SELECT message_number FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($db_message_number);
    $stmt->fetch();
    $stmt->close();

    if ($db_message_number === $message_number) {
        echo "valid";
    } else {
        echo "invalid";
    }
}

$conn->close();