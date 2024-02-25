<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

function calculateTotalPrice($checkInDate, $checkOutDate) {
    $date1 = new DateTime($checkInDate);
    $date2 = new DateTime($checkOutDate);
    $interval = $date1->diff($date2);
    // 假设每天费用为5000
    return max($interval->days, 1) * 5000; // 至少计算一天的费用
}

if(isset($data['id'], $data['checkInDate'], $data['checkOutDate'])) {
    $id = $data['id'];
    $checkInDate = $data['checkInDate'];
    $checkOutDate = $data['checkOutDate'];
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $remarks = $data['remarks'];

    $totalPrice = calculateTotalPrice($checkInDate, $checkOutDate);
    $deposit = $totalPrice * 0.3; // 订金为总价格的30%

    // 准备更新语句
    $stmt = $conn->prepare("UPDATE bookings SET name=?, email=?, phone=?, checkInDate=?, checkOutDate=?, totalPrice=?, deposit=?, remarks=? WHERE id=?");
    if (!$stmt->bind_param("ssssssdss", $name, $email, $phone, $checkInDate, $checkOutDate, $totalPrice, $deposit, $remarks, $id)) {
        error_log("参数绑定失败: " . $stmt->error);
        echo json_encode(["message" => "参数绑定失败"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!$stmt->execute()) {
        error_log("执行更新失败: " . $stmt->error);
        echo json_encode(["message" => "訂單更新失敗", "error" => $stmt->error], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        echo json_encode(["message" => "訂單更新成功"], JSON_UNESCAPED_UNICODE);
    }
    $stmt->close();
} else {
    echo json_encode(["message" => "缺少必要的信息"], JSON_UNESCAPED_UNICODE);
}

$conn->close();