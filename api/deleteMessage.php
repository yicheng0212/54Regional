<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(["message" => "留言已成功删除。"]);
    } else {
        echo json_encode(["error" => "删除留言时发生错误。"]);
    }
}
$stmt->close();
$conn->close();