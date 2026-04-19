<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bus_booking";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM buses";
$result = $conn->query($query);

$buses = [];

if ($result && $result->num_rows > 0) {
    $buses = $result->fetch_all(MYSQLI_ASSOC);
}

$totalBuses = count($buses);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bus Booking System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial;
    background: #f0f4ff;
}

.navbar {
    background: #0a1628;
}

.navbar a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

.hero {
    background: linear-gradient(135deg,#0a1628,#1e3a8a);
    color: white;
    text-align: center;
    padding: 40px;
}

.bus-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.bus-card:hover {
    transform: translateY(-5px);
}

.route {
    display: flex;
    justify-content: space-between;
    background: #f1f5ff;
    padding: 10px;
    border-radius: 10px;
    margin: 10px 0;
}

.btn-book {
    background: #1a56db;
    color: white;
    padding: 10px;
    display: block;
    text-align: center;
    border-radius: 10px;
    text-decoration: none;
    margin-top: 10px;
}

.btn-book:hover {
    background: #0f3fb5;
    color: white;
}

.btn-delete {
    background: #dc3545;
    color: white;
    padding: 8px;
    display: block;
    text-align: center;
    border-radius: 10px;
    text-decoration: none;
    margin-top: 8px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar p-3 d-flex justify-content-between">
    <a href="index.php" class="fs-4">🚌 Bus Booking System</a>
    <div>
        <?php if(isset($_SESSION['user_id'])): ?>
            <span class="text-white me-3">Welcome, <b><?= htmlspecialchars($_SESSION['username']) ?></b></span>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" class="btn btn-sm btn-outline-light me-2">Admin Panel</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        <?php else: ?>
            <a href="login.php?role=admin" class="btn btn-sm btn-warning me-2 fw-bold text-dark">Admin Login</a>
            <a href="login.php" class="btn btn-sm btn-success me-2">User Login</a>
            <a href="register.php" class="btn btn-sm btn-primary">Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Book Your Bus Tickets Easily</h1>
    <p>Fast & Simple Booking System</p>
    <h4><?= $totalBuses ?> Buses Available</h4>
</div>

<!-- SEARCH -->
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" id="from" class="form-control" placeholder="From">
        </div>
        <div class="col-md-5">
            <input type="text" id="to" class="form-control" placeholder="To">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" onclick="fetchBuses()">Search</button>
        </div>
    </div>

    <!-- RESULTS -->
    <div class="row g-4" id="busResults">

    <?php foreach ($buses as $row): ?>

        <div class="col-md-4">
            <div class="bus-card">

                <h5><?= htmlspecialchars($row['bus_name']) ?></h5>

                <div class="route">
                    <span><b><?= htmlspecialchars($row['source']) ?></b></span>
                    ➝
                    <span><b><?= htmlspecialchars($row['destination']) ?></b></span>
                </div>

                <p>
                    Total Seats: <strong><?= (int)$row['seats'] ?></strong>
                </p>

                <a href="book.php?bus_id=<?= (int)$row['id'] ?>" class="btn-book">
                    Book Now
                </a>

            </div>
        </div>

    <?php endforeach; ?>

    </div>
</div>

<script>
function fetchBuses() {

    let fromInput = document.getElementById("from").value.trim();
    let toInput = document.getElementById("to").value.trim();

    let from = encodeURIComponent(fromInput);
    let to = encodeURIComponent(toInput);

    fetch(`search_buses.php?from=${from}&to=${to}`)
        .then(res => res.text())
        .then(text => {
            try {
                let data = JSON.parse(text);
                let container = document.getElementById("busResults");
                container.innerHTML = "";

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center">
                            <div class="alert alert-warning">
                                No buses found matching that route.
                            </div>
                        </div>`;
                    return;
                }

                data.forEach(bus => {
                    container.innerHTML += `
                    <div class="col-md-4">
                        <div class="bus-card">

                            <h5>${bus.bus_name}</h5>

                            <div class="route">
                                <b>${bus.source}</b> ➝ <b>${bus.destination}</b>
                            </div>

                            <p>Total Seats: ${bus.seats}</p>

                            <a href="book.php?bus_id=${bus.id}" class="btn-book">
                                Book Now
                            </a>

                        </div>
                    </div>`;
                });
            } catch (error) {
                console.error("Invalid JSON response:", text);
                alert("Sorry, an error occurred while searching for buses.");
            }
        });
}
</script>

</body>
</html>