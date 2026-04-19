<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: view_booking.php");
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $passenger_name = $conn->real_escape_string($_POST['passenger_name']);
    $seat_number = intval($_POST['seat_number']);

    $sql = "UPDATE bookings SET passenger_name='$passenger_name', seat_number=$seat_number WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: view_booking.php");
        exit();
    } else {
        $error = "Error updating booking.";
    }
}

$booking = $conn->query("SELECT * FROM bookings WHERE id=$id")->fetch_assoc();
if (!$booking) {
    echo "Booking not found!";
    exit();
}

$bus_id = $booking['bus_id'];
$bus = $conn->query("SELECT bus_name FROM buses WHERE id=$bus_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Edit Booking Details</h5>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <p class="text-muted">Bus: <strong><?= htmlspecialchars($bus['bus_name']) ?></strong></p>
            <form action="" method="POST">
                <div class="mb-3">
                    <label>Passenger Name</label>
                    <input type="text" name="passenger_name" class="form-control" value="<?= htmlspecialchars($booking['passenger_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Seat Number</label>
                    <input type="number" name="seat_number" class="form-control" value="<?= $booking['seat_number'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">Save Changes</button>
                <a href="view_booking.php" class="btn btn-outline-secondary w-100">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
