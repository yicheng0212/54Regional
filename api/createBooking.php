<?php
include "db.php";


$selectedRooms = $data['selectedRooms'] ?? null;
$name = $data['name'] ?? null;
$email = $data['email'] ?? null;
$phone = $data['phone'] ?? null;
$checkInDate = $data['selectedDates'][0] ?? null;
$checkOutDate = $data['selectedDates'][1] ?? $checkInDate;
$remarks = $data['remarks'] ?? '';

$totalPrice = calculateTotalPrice($checkInDate, $checkOutDate);
$deposit = $totalPrice * 0.3;
$bookingNumber = generateBookingNumber($conn, $checkInDate);


foreach ($selectedRooms as $selectedRoom) {
    $sql = "INSERT INTO bookings (roomNumber, name, email, phone, checkInDate, checkOutDate, totalPrice, deposit, remarks, bookingNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssddss", $selectedRoom, $name, $email, $phone, $checkInDate, $checkOutDate, $totalPrice, $deposit, $remarks, $bookingNumber);
        $stmt->execute();
        $stmt->close();
    }
}
echo json_encode(['success' => true, 'message' => '预订成功，预订编号：' . $bookingNumber]);


function calculateTotalPrice($checkInDate, $checkOutDate) {
    $date1 = new DateTime($checkInDate);
    $date2 = new DateTime($checkOutDate);
    $interval = $date1->diff($date2);
    return $interval->days * 5000;
}

function generateBookingNumber($conn, $checkInDate) {
    $datePart = str_replace('-', '', $checkInDate);
    $query = "SELECT COUNT(*) as totalBookings FROM bookings WHERE DATE(checkInDate) = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $checkInDate);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $sequence = $row['totalBookings'] + 1;
            $stmt->close();
            return $datePart . sprintf('%04d', $sequence);
        }
    }
    return $datePart . '0001';
}