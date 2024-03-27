<?php
include 'db.php';
header('Content-Type:Application/json');
$data = json_decode(file_get_contents('php://input'), true);

function calculateTotalPrice($checkInDate, $checkOutDate) {
    $date1 = new DateTime($checkInDate);
    $date2 = new DateTime($checkOutDate);
    $interval = $date1->diff($date2);
    return max($interval->days, 1) * 5000;
}

    $id = $data['id'];
    $checkInDate = $data['checkInDate'];
    $checkOutDate = $data['checkOutDate'];
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $remarks = $data['remarks'];

    $totalPrice = calculateTotalPrice($checkInDate, $checkOutDate);
    $deposit = $totalPrice * 0.3;

    $stmt = $conn->prepare("UPDATE bookings SET name=?, email=?, phone=?, checkInDate=?, checkOutDate=?, totalPrice=?, deposit=?, remarks=? WHERE id=?");
    $stmt->bind_param("ssssssdss", $name, $email, $phone, $checkInDate, $checkOutDate, $totalPrice, $deposit, $remarks, $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "訂單更新成功"], JSON_UNESCAPED_UNICODE);
    }
    $stmt->close();
$conn->close();