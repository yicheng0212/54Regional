<?php
include "db.php";
header('Content-Type: Application/json');

$data = json_decode(file_get_contents('php://input'), true);
$selectedRooms = $data['selectedRooms'] ?? [];
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';
$selectedDates = $data['selectedDates'] ?? [];
$checkInDate = $selectedDates[0] ?? date('Y-m-d');
$checkOutDate = $selectedDates[1] ?? $checkInDate;
$remarks = $data['remarks'] ?? '';

$totalPrice = calculateTotalPrice($checkInDate, $checkOutDate);
$deposit = $totalPrice * 0.3;
$bookingNumber = getBookingNumber($conn, $checkInDate);

$sql = "INSERT INTO bookings (roomNumber, name, email, phone, checkInDate, checkOutDate, totalPrice, deposit, remarks, bookingNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

foreach ($selectedRooms as $selectedRoom) {
    $stmt->bind_param("ssssssddss", $selectedRoom, $name, $email, $phone, $checkInDate, $checkOutDate, $totalPrice, $deposit, $remarks, $bookingNumber);
    $stmt->execute();
}

$stmt->close();
echo json_encode(['success' => true, 'message' => '預訂成功，預訂編號：' . $bookingNumber]);

function calculateTotalPrice($checkInDate, $checkOutDate) {
    $date1 = new DateTime($checkInDate);
    $date2 = new DateTime($checkOutDate);
    $interval = $date1->diff($date2);
    return max(1, $interval->days) * 5000;
}

function getBookingNumber($conn, $checkInDate) {
    $stmt = $conn->prepare("SELECT COUNT(*) + 1 AS number FROM bookings WHERE DATE(checkInDate) = ?");
    $stmt->bind_param("s", $checkInDate);
    $stmt->execute();
    $sequence = $stmt->get_result()->fetch_assoc()['number'];
    $stmt->close();
    return date('Ymd', strtotime($checkInDate)) . sprintf('%04d', $sequence);
}
