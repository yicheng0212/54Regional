<?php
include 'db.php';

if(isset($data['id'])) { // 现在我们根据id来定位记录
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["message" => "訂單刪除成功"], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["message" => "訂單刪除失敗"], JSON_UNESCAPED_UNICODE);
    }
    $stmt->close();
} else {
    echo json_encode(["message" => "需要訂單編號"], JSON_UNESCAPED_UNICODE);
}

$conn->close();
