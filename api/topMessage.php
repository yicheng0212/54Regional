<?php
include "db.php";
// 管理者置頂留言
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'top') {
    $id = $conn->real_escape_string($_POST['id']);

    // 首先，將所有留言設置為非置頂狀態，以確保只有一條留言被置頂
    $conn->query("UPDATE messages SET is_top = FALSE");

    // 然後，將指定 ID 的留言置頂
    if ($conn->query("UPDATE messages SET is_top = TRUE WHERE id = $id") === TRUE) {
        echo json_encode(['message' => '成功置頂']);
    } else {
        echo json_encode(['error' => '置頂失敗']);
    }
}
$conn->close();