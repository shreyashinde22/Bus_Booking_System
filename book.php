<?php 
include 'db.php';
$bus_id = $_GET['bus_id'];

// Fetch booked seats
$bookedSeats = [];
$result = $conn->query("SELECT seat_number FROM bookings WHERE bus_id='$bus_id'");
while($row = $result->fetch_assoc()) {
    $bookedSeats[] = $row['seat_number'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 text-center">

<h2>Book Seat</h2>

<h4>Booked Seats:</h4>
<p>
<?php
foreach($bookedSeats as $seat) {
    echo "Seat $seat ";
}
?>
</p>

<!-- 🔥 SEAT GRID -->
<h4>Select Seat:</h4>

<div style="display:grid;grid-template-columns:repeat(5,60px);gap:10px;justify-content:center;">

<?php
for ($i = 1; $i <= 40; $i++) {
    if (in_array($i, $bookedSeats)) {
        echo "<div style='background:red;color:white;padding:10px;'>$i</div>";
    } else {
        echo "<div onclick='selectSeat($i)' 
              style='background:green;color:white;padding:10px;cursor:pointer;'>$i</div>";
    }
}
?>

</div>

<br>

<!-- FORM -->
<form action="confirm.php" method="POST">
    <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">

    <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
    </div>

    <div class="mb-3">
        <input type="number" name="seat" id="seatInput" class="form-control" placeholder="Select Seat" required>
    </div>

    <button type="submit" class="btn btn-primary">Confirm Booking</button>
</form>

</div>

<!-- 🔥 SCRIPT -->
<script>
function selectSeat(seat) {
    document.getElementById("seatInput").value = seat;
}
</script>

</body>
</html>