<?php
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