<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include 'db.php';

// Fetch bookings.id as well
$sql = "
SELECT 
    bookings.id,
    bookings.passenger_name,
    bookings.seat_number,
    bookings.booking_time,
    buses.bus_name,
    buses.source,
    buses.destination
FROM bookings
JOIN buses ON bookings.bus_id = buses.id
ORDER BY bookings.booking_time DESC
";

$result = $conn->query($sql);

$totalBookings = $result ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Bookings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>

    <style>
        body {
            background: #f0f4ff;
            font-family: Arial;
        }

        .header {
            background: #0a1628;
            color: white;
            padding: 20px;
            border-radius: 0 0 20px 20px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #1a56db;
        }

        .booking-card {
            background: white;
            border-radius: 15px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.06);
            transition: 0.2s;
        }

        .booking-card:hover {
            transform: translateY(-3px);
        }

        .badge-route {
            background: #e0ecff;
            color: #1a56db;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .seat-badge {
            background: #10b981;
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            font-weight: bold;
        }

        .time {
            font-size: 12px;
            color: gray;
        }
    </style>
</head>

<body>

<div class="header text-center position-relative">
    <a href="admin.php" class="btn btn-outline-light position-absolute top-50 start-0 translate-middle-y ms-3">← Back to Admin</a>
    <h2><i class="bi bi-clipboard-data"></i> Admin Booking Dashboard</h2>
</div>

<div class="container mt-4">

    <!-- STATS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?= $totalBookings ?></div>
                <div>Total Bookings</div>
            </div>
        </div>
    </div>

    <!-- BOOKINGS -->
    <h4 class="mb-3">Recent Bookings</h4>

    <?php if ($result && $result->num_rows > 0): ?>

        <?php while($row = $result->fetch_assoc()): ?>

            <div class="booking-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            <?= htmlspecialchars($row['passenger_name']) ?>
                        </h5>

                        <div class="badge-route">
                            <?= htmlspecialchars($row['source']) ?> → <?= htmlspecialchars($row['destination']) ?>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="seat-badge">
                            Seat <?= (int)$row['seat_number'] ?>
                        </div>
                        <div class="time mt-1">
                            <?= $row['booking_time'] ?>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-end mt-3">
                    <small class="text-muted d-block pb-2">
                        Bus: <?= htmlspecialchars($row['bus_name']) ?>
                    </small>
                    <div>
                        <a href="edit_booking.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary px-3 me-2">Edit</a>
                        <a href="delete_booking.php?id=<?= $row['id'] ?>" 
                           onclick="return confirm('Are you sure you want to cancel this booking?')"
                           class="btn btn-sm btn-danger px-3">
                           Cancel
                        </a>
                    </div>
                </div>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="alert alert-warning text-center">
            No bookings found.
        </div>

    <?php endif; ?>

</div>

</body>
</html>

<?php $conn->close(); ?>