<?php
include "db.php";
header('Content-Type: Application/json');

$data = json_decode(file_get_contents('php://input'), true);
$checkInDate = $data['checkInDate'];
$checkOutDate = $data['checkOutDate'];

$availableRooms = [];
for ($roomNumber = 1; $roomNumber <= 8; $roomNumber++) {
    $availableRooms[$roomNumber] = ['roomNumber' => "Room $roomNumber", 'available' => true];
}

$sql = "SELECT DISTINCT roomNumber FROM bookings WHERE checkOutDate > ? AND checkInDate < ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $checkInDate, $checkOutDate);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $roomNumber = intval(str_replace("Room ", "", $row['roomNumber']));
    if (isset($availableRooms[$roomNumber])) {
        $availableRooms[$roomNumber]['available'] = false;
    }
}

echo json_encode(['rooms' => array_values($availableRooms)]);

$stmt->close();
$conn->close();