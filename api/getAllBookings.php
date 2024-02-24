<?php
require 'db.php';
header('Content-Type:Application/json');
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

$bookings = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    echo json_encode($bookings);
} else {
    echo json_encode(["message" => "沒有找到任何訂單"], JSON_UNESCAPED_UNICODE);
}

$conn->close();