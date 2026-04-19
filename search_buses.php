<?php
header('Content-Type: application/json');
include 'db.php';

$from = isset($_GET['from']) ? $conn->real_escape_string(trim($_GET['from'])) : '';
$to   = isset($_GET['to']) ? $conn->real_escape_string(trim($_GET['to'])) : '';

$sql = "SELECT * FROM buses WHERE 1=1";

if (!empty($from)) {
    $sql .= " AND source LIKE '%$from%'";
}

if (!empty($to)) {
    $sql .= " AND destination LIKE '%$to%'";
}

$result = $conn->query($sql);

$buses = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $buses[] = $row;
    }
}

echo json_encode($buses);
?>