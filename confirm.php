<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$bus_id = $_POST['bus_id'];
$name   = $_POST['name'];
$seat   = $_POST['seat'];

// 1. Check if the specific seat is already booked
$check_seat = $conn->query("SELECT id FROM bookings WHERE bus_id=$bus_id AND seat_number=$seat");

if ($check_seat->num_rows > 0) {
    die("Error: Seat $seat is already booked! <br><br><a href='book.php?bus_id=$bus_id'>Go back to select another seat</a>");
}

$user_id = $_SESSION['user_id'];

// 2. Insert booking
$conn->query("
    INSERT INTO bookings (bus_id, user_id, passenger_name, seat_number)
    VALUES ($bus_id, $user_id, '$name', $seat)
");

// (We removed the logic that tries to subtract from 'available_seats' because that column doesn't exist in your database, and your 'seats' column represents total capacity)

echo "✅ Booking successful!";
echo "<br><br><a href='index.php'>Go back to Home</a>";
?>