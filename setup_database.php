<?php
// setup_database.php
include 'db.php';

// 1. Create Users Table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_users) === TRUE) {
    echo "Users table created or exists.\n";
} else {
    echo "Error creating users table: " . $conn->error . "\n";
}

// 2. Add user_id column to bookings if it doesn't exist
$check_column = "SHOW COLUMNS FROM bookings LIKE 'user_id'";
$result = $conn->query($check_column);

if ($result->num_rows == 0) {
    $sql_alter = "ALTER TABLE bookings ADD COLUMN user_id INT AFTER bus_id";
    if ($conn->query($sql_alter) === TRUE) {
        echo "Added user_id column to bookings.\n";
    } else {
        echo "Error adding user_id column: " . $conn->error . "\n";
    }
} else {
    echo "user_id column already exists.\n";
}

// 3. Create a default admin account
$admin_pass = password_hash('admin123', PASSWORD_BCRYPT);
$sql_admin = "INSERT IGNORE INTO users (username, password, role) VALUES ('admin', '$admin_pass', 'admin')";
if ($conn->query($sql_admin) === TRUE) {
    echo "Default admin account ensured.\n";
} else {
    echo "Error creating admin account: " . $conn->error . "\n";
}

$conn->close();
echo "Database setup complete!\n";
?>
