<?php
include "db.php";
header('Content-Type: application/json');

// 确保请求方法是POST，并且包含id参数
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id']; // 从POST请求中获取id值

    // 将所有留言设置为非置顶状态
    $conn->query("UPDATE messages SET is_top = FALSE");

    // 准备预处理语句
    $updateStmt = $conn->prepare("UPDATE messages SET is_top = TRUE WHERE id = ?");
    $updateStmt->bind_param("i", $id); // 绑定$id到预处理语句
    $updateStmt->execute();

    // 检查是否有行被影响，即是否成功更新
    if ($updateStmt->affected_rows > 0) {
        echo json_encode(['message' => '成功置頂']);
    } else {
        echo json_encode(['error' => '置頂失败或未找到指定的留言']);
    }

    $updateStmt->close();
} else {
    // 如果请求方法不是POST或缺少id参数，则返回错误
    echo json_encode(['error' => '请求无效']);
}

$conn->close();