<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

<h2 class="text-center">Admin Panel - Add Bus</h2>

<form action="add_bus.php" method="POST">

    <div class="mb-3">
        <input type="text" name="bus_name" class="form-control" placeholder="Bus Name" required>
    </div>

    <div class="mb-3">
        <input type="text" name="source" class="form-control" placeholder="Source" required>
    </div>

    <div class="mb-3">
        <input type="text" name="destination" class="form-control" placeholder="Destination" required>
    </div>

    <div class="mb-3">
        <input type="number" name="seats" class="form-control" placeholder="Total Seats" required>
    </div>

    <button type="submit" class="btn btn-success">Add Bus</button>
    <a href="view_booking.php" class="btn btn-primary">
    View Bookings
</a>
<a href="delete_bus.php?id=<?= $row['id'] ?>" 
   onclick="return confirm('Are you sure you want to delete this bus?')"
   class="btn btn-danger btn-sm">
   Delete Bus
</a>
</form>

</div>

</body>
</html>