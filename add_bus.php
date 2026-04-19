<?php
include 'db.php';

$name = $_POST['bus_name'];
$source = $_POST['source'];
$destination = $_POST['destination'];
$seats = $_POST['seats'];

$sql = "INSERT INTO buses (bus_name, source, destination, seats)
        VALUES ('$name', '$source', '$destination', '$seats')";

if ($conn->query($sql) === TRUE) {
    echo "✅ Bus Added Successfully!";
    echo "<br><a href='admin.php'>Go Back</a>";
} else {
    echo "Error: " . $conn->error;
}
?>