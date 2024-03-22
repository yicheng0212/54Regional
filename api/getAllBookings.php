<?php
include 'db.php';
header('Content-Type: Application/json');

$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);

$bookings = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
echo json_encode($bookings);

$conn->close();