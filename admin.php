<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include 'db.php'; 

// Fetch Stats
$total_buses = $conn->query("SELECT COUNT(*) as c FROM buses")->fetch_assoc()['c'];
$total_users = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch_assoc()['c'];
$total_bookings = $conn->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];

// Fetch All Buses
$buses = $conn->query("SELECT * FROM buses ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1"><i class="bi bi-shield-lock"></i> Admin Dashboard</span>
        <div>
            <a href="index.php" class="btn btn-sm btn-outline-light me-2">Go to Website</a>
            <a href="view_booking.php" class="btn btn-sm btn-primary me-2">View Bookings</a>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">

    <!-- Analytics -->
    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Active Buses</h5>
                    <h2><?= $total_buses ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Registered Users</h5>
                    <h2><?= $total_users ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Tickets Sold</h5>
                    <h2><?= $total_bookings ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Add Bus Form -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Add New Bus Route</h5>
                </div>
                <div class="card-body">
                    <form action="add_bus.php" method="POST">
                        <div class="mb-3">
                            <label>Bus Name</label>
                            <input type="text" name="bus_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Source City</label>
                            <input type="text" name="source" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Destination City</label>
                            <input type="text" name="destination" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Total Seats</label>
                            <input type="number" name="seats" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Add Bus</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bus List -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage Active Buses</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Bus Name</th>
                                <th>Route</th>
                                <th>Capacity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $buses->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><strong><?= htmlspecialchars($row['bus_name']) ?></strong></td>
                                <td><?= htmlspecialchars($row['source']) ?> &rarr; <?= htmlspecialchars($row['destination']) ?></td>
                                <td><?= $row['seats'] ?> Seats</td>
                                <td>
                                    <a href="edit_bus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete_bus.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this bus?')" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if($buses->num_rows == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No buses active yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>