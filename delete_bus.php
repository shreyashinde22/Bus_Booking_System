<?php
include 'db.php';

$bus_id = $_GET['id'];

// Step 1: delete bookings first (IMPORTANT for FK)
$conn->query("DELETE FROM bookings WHERE bus_id = $bus_id");

// Step 2: delete bus
$sql = "DELETE FROM buses WHERE id = $bus_id";

if ($conn->query($sql) === TRUE) {
    echo "Bus deleted successfully";
    echo "<br><a href='index.php'>Go Back</a>";
} else {
    echo "Error: " . $conn->error;
}
?>