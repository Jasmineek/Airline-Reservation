<?php
// Include the database connection
include('includes/db.php');

// Check if booking_id is set in the URL
if (isset($_GET['booking_id'])) {
    // Get the booking_id from the query string
    $booking_id = $_GET['booking_id'];

    // Query to fetch the booking details using the booking_id
    $query = "SELECT * FROM bookings WHERE id = '$booking_id'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the booking exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the booking data
        $booking = mysqli_fetch_assoc($result);

        // Check if the booking status is 'Cancelled' or not
        if ($booking['payment_status'] == 'Cancelled') {
            // Booking is successfully cancelled, show confirmation message
            //echo "<h2>Booking Cancellation Confirmed</h2>";
            echo "<p>Booking ID: " . $booking['id'] . "</p>";
            echo "<p>Passenger Name: " . $booking['passenger_name'] . "</p>";
            echo "<p>Refund Status: Refund Pending</p>";
        } else {
            // If payment_status is not 'Cancelled', show error message
            echo "<p class='error-msg'>This booking has not been cancelled yet or is invalid.</p>";
        }
    } else {
        // If no booking found with that ID, show an error message
        echo "<p class='error-msg'>We couldn't find a booking with that ID. Please check your booking ID and try again.</p>";
    }
} else {
    // If booking_id is not set in the URL
    echo "<p class='error-msg'>No booking ID found. Please try again.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancellation Status - Fly With Us</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(to bottom, #007bff 50%, #f4f4f4 50%);
        }

        header {
            background-color: transparent;
            padding: 0;
            text-align: center;
            color: #fff;
            width: 100%;
            margin-bottom: 40px;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .status-message {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .status-message p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .success-msg {
            color: #28a745;
            font-size: 1.2rem;
        }

        .error-msg {
            color: #dc3545;
            font-size: 1.2rem;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Booking Cancellation Status</h1>
    </header>

    <section class="status-message">
        <?php
        // Display status message based on the cancellation result
        if (isset($booking)) {
            if ($booking['payment_status'] == 'Cancelled') {
                echo "<p class='success-msg'>Your booking has been successfully cancelled.</p>";
                echo "<p>Refund will be processed soon. Thank you for choosing Fly With Us!</p>";
            } else {
                echo "<p class='error-msg'>This booking has not been cancelled or does not match the entered details.</p>";
                echo "<p>Please check your booking ID and passenger name.</p>";
            }
        } else {
            echo "<p class='error-msg'>We couldn't process your cancellation request. Please try again later or contact support.</p>";
        }
        ?>
        <a href="index.php" class="button">Go Back to Homepage</a>
    </section>

    <footer>
        <p>&copy; 2024 Fly With Us. All rights reserved.</p>
    </footer>
</body>
</html>
