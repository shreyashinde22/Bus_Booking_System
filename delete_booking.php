<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include 'db.php';

$booking_id = intval($_GET['id']); // Validate ID to prevent basic injection

$sql = "DELETE FROM bookings WHERE id = $booking_id";

if ($conn->query($sql) === TRUE) {
    // Redirect cleanly
    header("Location: view_booking.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>