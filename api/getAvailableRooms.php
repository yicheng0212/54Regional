<?php
include "db.php";
header('Content-Type:Application/json');
$data = json_decode(file_get_contents('php://input'),true);
$checkInDate = $data['checkInDate'] ?? null;
$checkOutDate = $data['checkOutDate'] ?? null;

$allRooms = range(1, 8);
$availableRooms = [];

foreach ($allRooms as $roomNumber) {
    $availableRooms[$roomNumber] = ['roomNumber' => $roomNumber, 'available' => true];
}

$sql = "SELECT DISTINCT roomNumber FROM bookings WHERE (checkInDate < ? AND checkOutDate > ?) OR (checkInDate BETWEEN ? AND ?) OR (checkOutDate BETWEEN ? AND ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $checkInDate, $checkOutDate, $checkInDate, $checkOutDate, $checkInDate, $checkOutDate);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $availableRooms[$row['roomNumber']]['available'] = false;
    }
}

echo json_encode(['rooms' => array_values($availableRooms)]);

$stmt->close();
$conn->close();