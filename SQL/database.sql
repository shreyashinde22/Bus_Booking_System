CREATE DATABASE IF NOT EXISTS bus_booking;

USE bus_booking;

-- ==========================
-- 1. Users Table (NEW)
-- ==========================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================
-- 2. Buses Table (UPDATED)
-- ==========================
CREATE TABLE IF NOT EXISTS buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_name VARCHAR(100),
    source VARCHAR(100),
    destination VARCHAR(100),
    seats INT
);

-- ==========================
-- 3. Bookings Table (UPDATED)
-- ==========================
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, 
    bus_id INT,
    passenger_name VARCHAR(100),
    seat_number INT,
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (bus_id) REFERENCES buses(id) ON DELETE CASCADE
);

-- ==========================
-- INITIAL DATA DUMP
-- ==========================

-- Insert Default Admin (Username: admin | Password: admin123)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$6venaxKSYTLCfKOUXMMzI.J5qyzudWg6jMDYuOItV.ldaBjT7cg5i', 'admin');

-- Insert Sample Buses
INSERT INTO buses (bus_name, source, destination, seats) VALUES
('Express 101', 'Mumbai', 'Pune', 40),
('City Rider', 'Thane', 'Nashik', 35),
('Night Line', 'Mumbai', 'Goa', 30);