<?php 
include 'db.php';
$bus_id = $_GET['bus_id'];

// Fetch total seats for this bus
$busResult = $conn->query("SELECT seats, bus_name FROM buses WHERE id='$bus_id'");
$busData = $busResult->fetch_assoc();
$total_seats = $busData ? $busData['seats'] : 40; // Default to 40 if not found
$bus_name = $busData ? $busData['bus_name'] : "Unknown Bus";

// Fetch booked seats
$bookedSeats = [];
$result = $conn->query("SELECT seat_number FROM bookings WHERE bus_id='$bus_id'");
while($row = $result->fetch_assoc()) {
    $bookedSeats[] = $row['seat_number'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Ticket - <?php echo $bus_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Steering Wheel Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bus-layout {
            max-width: 400px;
            margin: 0 auto;
            border: 4px solid #adb5bd;
            border-radius: 30px;
            padding: 20px;
            background: #f8f9fa;
            position: relative;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .steering-wheel {
            text-align: right;
            padding-right: 30px;
            font-size: 28px;
            color: #6c757d;
            margin-bottom: 20px;
            border-bottom: 2px dashed #dee2e6;
            padding-bottom: 10px;
        }
        .seat-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .seat-group {
            display: flex;
            gap: 12px;
        }
        .seat {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            border: 2px solid #ccc;
            font-size: 14px;
        }
        /* Seat states */
        .seat.available { background: white; color: #333; }
        .seat.available:hover { background: #e2e3e5; border-color: #6c757d; }
        
        .seat.booked { background: #dc3545; color: white; cursor: not-allowed; border-color: #dc3545; }
        
        .seat.selected { background: #198754; color: white; border-color: #198754; box-shadow: 0 0 10px rgba(25, 135, 84, 0.5); }
        
        /* Legend */
        .legend-box { width: 20px; height: 20px; display: inline-block; border-radius: 4px; margin-right: 5px; vertical-align: middle;}
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- SEAT SELECTION LAYOUT -->
        <div class="col-md-6 text-center">
            <h2>Select Your Seat</h2>
            <p class="text-muted"><?php echo $bus_name; ?> - Total Seats: <?php echo $total_seats; ?></p>
            
            <div class="d-flex justify-content-center mb-4 gap-3">
                <div><span class="legend-box" style="background: white; border: 1px solid #ccc;"></span> Available</div>
                <div><span class="legend-box" style="background: #dc3545;"></span> Booked</div>
                <div><span class="legend-box" style="background: #198754;"></span> Selected</div>
            </div>

            <div class="bus-layout">
                <div class="steering-wheel">
                    <i class="fa-solid fa-dharmachakra"></i>
                </div>

                <?php
                $seatNo = 1;
                $rows = ceil($total_seats / 4);
                
                for ($row = 1; $row <= $rows; $row++) {
                    echo '<div class="seat-row">';
                    
                    // Left Side (2 seats)
                    echo '<div class="seat-group">';
                    for ($col = 1; $col <= 2; $col++) {
                        if ($seatNo <= $total_seats) {
                            $isBooked = in_array($seatNo, $bookedSeats);
                            $class = $isBooked ? "booked" : "available";
                            $onclick = $isBooked ? "" : "onclick='selectSeat($seatNo, this)'";
                            
                            echo "<div id='seat-$seatNo' class='seat $class' $onclick>$seatNo</div>";
                            $seatNo++;
                        }
                    }
                    echo '</div>'; // End Left Side
                    
                    // Aisle Space
                    echo '<div style="width: 40px;"></div>';
                    
                    // Right Side (2 seats)
                    echo '<div class="seat-group">';
                    for ($col = 1; $col <= 2; $col++) {
                        if ($seatNo <= $total_seats) {
                            $isBooked = in_array($seatNo, $bookedSeats);
                            $class = $isBooked ? "booked" : "available";
                            $onclick = $isBooked ? "" : "onclick='selectSeat($seatNo, this)'";
                            
                            echo "<div id='seat-$seatNo' class='seat $class' $onclick>$seatNo</div>";
                            $seatNo++;
                        }
                    }
                    echo '</div>'; // End Right Side

                    echo '</div>'; // End Row
                }
                ?>
            </div>
        </div>

        <!-- BOOKING FORM -->
        <div class="col-md-5 mt-5">
            <div class="card shadow-sm p-4">
                <h3 class="mb-4">Passenger Details</h3>
                <form action="confirm.php" method="POST">
                    <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Selected Seat:</label>
                        <input type="text" name="seat" id="seatInput" class="form-control" placeholder="Please select a seat from the graphic" readonly required style="background: #e9ecef; font-weight: bold;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Passenger Name:</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">Proceed to Book</button>
                    <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 🔥 JAVASCRIPT LOGIC -->
<script>
    let currentSelectedSeat = null;

    function selectSeat(seatNo, element) {
        // If seat is already booked, do nothing (handled by not having onclick, but just in case)
        if (element.classList.contains('booked')) return;

        // Remove selection from previously selected seat
        if (currentSelectedSeat !== null) {
            document.getElementById('seat-' + currentSelectedSeat).classList.remove('selected');
        }

        // Apply selection to new seat
        element.classList.add('selected');
        currentSelectedSeat = seatNo;

        // Update the form input
        document.getElementById("seatInput").value = seatNo;
    }
</script>

</body>
</html>