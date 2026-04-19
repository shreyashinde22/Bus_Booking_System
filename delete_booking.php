<?php
include 'db.php';

$booking_id = $_GET['id'];

$sql = "DELETE FROM bookings WHERE id = $booking_id";

if ($conn->query($sql) === TRUE) {
    echo "Booking cancelled successfully";
    echo "<br><a href='view_bookings.php'>Go Back</a>";
} else {
    echo "Error: " . $conn->error;
}
?>