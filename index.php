<?php
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
<div class="navbar p-3">
    <a href="#">🚌 Bus Booking System</a>
    <a href="admin.php">Admin Panel</a>
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
                    Seats Available: 
                    <strong><?= (int)$row['available_seats'] ?></strong> / <?= (int)$row['seats'] ?>
                </p>

                <a href="book.php?bus_id=<?= (int)$row['id'] ?>" class="btn-book">
                    Book Now
                </a>

                <a href="delete_bus.php?id=<?= (int)$row['id'] ?>" 
                   onclick="return confirm('Are you sure you want to delete this bus?')"
                   class="btn-delete">
                    Delete Bus
                </a>

            </div>
        </div>

    <?php endforeach; ?>

    </div>
</div>

<script>
function fetchBuses() {

    let from = document.getElementById("from").value;
    let to = document.getElementById("to").value;

    fetch(`search_buses.php?from=${from}&to=${to}`)
        .then(res => res.json())
        .then(data => {

            let container = document.getElementById("busResults");
            container.innerHTML = "";

            if (data.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">
                            No buses found
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

                        <p>Seats: ${bus.seats}</p>

                        <a href="book.php?bus_id=${bus.id}" class="btn-book">
                            Book Now
                        </a>

                        <a href="delete_bus.php?id=${bus.id}" 
                           onclick="return confirm('Delete this bus?')"
                           class="btn-delete">
                            Delete Bus
                        </a>

                    </div>
                </div>`;
            });

        });
}
</script>

</body>
</html>