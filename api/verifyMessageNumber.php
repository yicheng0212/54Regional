<?php
include 'db.php';
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $messageNumber = $_POST['messageNumber'];

    $stmt = $conn->prepare("SELECT messageNumber FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($dbMessageMumber);
    $stmt->fetch();
    $stmt->close();

    // 使用数组返回结果，包括验证状态和id
    $response = array();
    if ($dbMessageMumber === $messageNumber) {
        $response['status'] = "valid";
        $response['id'] = $id;
    }

    echo json_encode($response);
}

$conn->close();