<?php
include "db.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $conn->query("UPDATE messages SET is_top = FALSE");
    
    $stmt = $conn->prepare("UPDATE messages SET is_top = TRUE WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => '成功置頂']);
    } else {
        echo json_encode(['error' => '置頂失败或未找到指定的留言']);
    }

    $stmt->close();
}
$conn->close();
