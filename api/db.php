<?php
$servername = "localhost";
$db_username = "root";
$db_password = "20080212";
$dbname = "54regional";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);