<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bus_name = $conn->real_escape_string($_POST['bus_name']);
    $source = $conn->real_escape_string($_POST['source']);
    $destination = $conn->real_escape_string($_POST['destination']);
    $seats = intval($_POST['seats']);

    $sql = "UPDATE buses SET bus_name='$bus_name', source='$source', destination='$destination', seats=$seats WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        $error = "Error updating database.";
    }
}

$bus = $conn->query("SELECT * FROM buses WHERE id=$id")->fetch_assoc();
if (!$bus) {
    echo "Bus not found!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Bus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Edit Bus Details</h5>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label>Bus Name</label>
                    <input type="text" name="bus_name" class="form-control" value="<?= htmlspecialchars($bus['bus_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Source City</label>
                    <input type="text" name="source" class="form-control" value="<?= htmlspecialchars($bus['source']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Destination City</label>
                    <input type="text" name="destination" class="form-control" value="<?= htmlspecialchars($bus['destination']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Total Seats Capacity</label>
                    <input type="number" name="seats" class="form-control" value="<?= $bus['seats'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">Save Changes</button>
                <a href="admin.php" class="btn btn-outline-secondary w-100">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
