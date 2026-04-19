CREATE DATABASE bus_booking;

USE bus_booking;

-- Buses table
CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_name VARCHAR(100),
    source VARCHAR(100),
    destination VARCHAR(100),
    seats INT
);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_id INT,
    passenger_name VARCHAR(100),
    seat_number INT,
    FOREIGN KEY (bus_id) REFERENCES buses(id)
);

-- Insert sample buses
INSERT INTO buses (bus_name, source, destination, seats) VALUES
('Express 101', 'Mumbai', 'Pune', 40),
('City Rider', 'Thane', 'Nashik', 35),
('Night Line', 'Mumbai', 'Goa', 30);