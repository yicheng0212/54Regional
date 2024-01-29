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

    // 使用数组返回结果，包括验证状态和id
    $response = array();
    if ($db_message_number === $message_number) {
        $response['status'] = "valid";
        $response['id'] = $id;  // 包含id以供前端使用
    } else {
        $response['status'] = "invalid";
    }

    // 将数组转换为JSON格式返回
    echo json_encode($response);
}

$conn->close();
?>