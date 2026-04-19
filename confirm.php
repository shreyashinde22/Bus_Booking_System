
<?php
include 'db.php';

$bus_id = $_POST['bus_id'];
$name   = $_POST['name'];
$seat   = $_POST['seat'];

// 1. Check availability
$check = $conn->query("SELECT available_seats FROM buses WHERE id=$bus_id");
$row = $check->fetch_assoc();

if ($row['available_seats'] <= 0) {
    die("No seats available!");
}

// 2. Insert booking
$conn->query("
    INSERT INTO bookings (bus_id, passenger_name, seat_number)
    VALUES ($bus_id, '$name', $seat)
");

// 3. Reduce seat count
$conn->query("
    UPDATE buses 
    SET available_seats = available_seats - 1 
    WHERE id = $bus_id
");

echo "Booking successful!";
echo "<br><a href='index.php'>Go back</a>";
?>