<?php
include('includes/db.php');

// Get booking_id from URL
$booking_id = $_GET['booking_id'];

// Fetch booking details
$query = "SELECT b.id AS booking_id, b.passenger_name, b.seat_number, b.payment_status, 
                 f.flight_name, f.date, f.origin_id, f.destination_id,
                 c1.city_name AS origin_city, c2.city_name AS destination_city 
          FROM bookings b 
          JOIN flights f ON b.flight_id = f.id 
          JOIN cities c1 ON f.origin_id = c1.id 
          JOIN cities c2 ON f.destination_id = c2.id 
          WHERE b.id = $booking_id";
$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* Header Styling */
        header {
            background-color: #007bff;
            padding: 82px;
            text-align: center;
            color: #fff;
            width: 100%;
        }

        /* Confirmation Section */
        .confirmation-details {
            margin: 20px auto;
            width: 90%;
            max-width: 800px;
            flex-grow: 1; /* Allow content to grow and take available space */
            padding: 20px; /* Added padding to prevent button overflow */
        }


        /* Passenger Box Styling */
        .passenger-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .passenger-box h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .passenger-box p {
            font-size: 1rem;
            margin: 8px 0;
        }

        .passenger-box .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .passenger-box .row > div {
            flex: 1;
            padding: 10px;
        }

        .passenger-box .row > div:first-child {
            font-weight: bold;
        }

        /* Styling for the Go to Home Page button */
        .btn-home {
            display: inline-block;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            width: 100%; /* Ensure button takes full width of the container */
            box-sizing: border-box; /* Prevents overflow */
            margin-top: 20px;
        }

        .btn-home:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }

        /* Center the "Thank You" and other text */
        .thank-you-message, .confirmation-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.4rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Booking Confirmation</h1>
    </header>

    <section class="confirmation-details">
        <!-- Center the Thank You and Info Text -->
        <div class="thank-you-message">
            <h2>Thank You for Booking with Us!</h2>
        </div>
        
        <div class="confirmation-info">
            <p>Your booking has been confirmed. Below are the details:</p>
        </div>

        <!-- Passenger Details Box -->
        <div class="passenger-box">
            
            <div class="row">
                <div>Ticket Number:</div>
                <div><?php echo $booking['booking_id']; ?></div>
            </div>
            <div class="row">
                <div>Passenger Name:</div>
                <div><?php echo $booking['passenger_name']; ?></div>
            </div>
            <div class="row">
                <div>Flight Name:</div>
                <div><?php echo $booking['flight_name']; ?></div>
            </div>
            <div class="row">
                <div>Flight Date:</div>
                <div><?php echo $booking['date']; ?></div>
            </div>
            <div class="row">
                <div>From:</div>
                <div><?php echo $booking['origin_city']; ?></div>
            </div>
            <div class="row">
                <div>To:</div>
                <div><?php echo $booking['destination_city']; ?></div>
            </div>
            <div class="row">
                <div>Seat Number:</div>
                <div><?php echo $booking['seat_number']; ?></div>
            </div>
        </div>

        <!-- Go to Home Page Button -->
        <a href="index.php" class="btn-home">Go to Home Page</a>
    </section>

    <footer>
        <p>&copy; 2024 Fly With Us. All rights reserved.</p>
    </footer>
</body>
</html>
