<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('includes/db.php');

// Check if user submitted the cancellation form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get booking details from form input
    $booking_id = $_POST['booking_id'];
    $passenger_name = $_POST['passenger_name'];

    // Verify the booking exists and matches the passenger name
    $query = "SELECT * FROM bookings WHERE id = '$booking_id' AND passenger_name = '$passenger_name'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Booking exists, proceed with cancellation

        // 1. Delete passenger data from passengers table
        $delete_passenger_query = "DELETE FROM passengers WHERE booking_id = '$booking_id'";
        if (!mysqli_query($conn, $delete_passenger_query)) {
            echo "<p class='error-msg'>Error deleting passenger data: " . mysqli_error($conn) . "</p>";
        }

        // 2. Update the payment_status in the bookings table to 'Cancelled'
        $update_booking_query = "UPDATE bookings SET payment_status = 'Cancelled' WHERE id = '$booking_id'";
        if (mysqli_query($conn, $update_booking_query)) {
            // 3. Update the status in the payments table to 'Refund Pending'
            $refund_query = "UPDATE payments SET status = 'Refund Pending' WHERE booking_id = '$booking_id'";
            if (mysqli_query($conn, $refund_query)) {
                // Redirect to confirmation page after successful cancellation
                header("Location: cancel_confirmation.php?booking_id=" . urlencode($booking_id));
                exit(); // Always use exit after header redirect to stop further code execution
            } else {
                echo "<p class='error-msg'>Error updating refund status in payments table: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p class='error-msg'>Error updating booking status: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p class='error-msg'>Booking not found or passenger name does not match.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking - Fly With Us</title>
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

        .cancel-form {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .cancel-form label {
            display: block;
            font-size: 1rem;
            margin: 10px 0 5px;
            color: #333;
        }

        .cancel-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .cancel-form button {
            padding: 12px 20px;
            background-color: #ff4c4c;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; /* Make button full-width for consistency */
        }

        .cancel-form button:hover {
            background-color: #d94343;
        }

        .success-msg {
            color: #28a745;
            font-size: 1.1rem;
            margin: 20px 0;
        }

        .error-msg {
            color: #dc3545;
            font-size: 1.1rem;
            margin: 20px 0;
        }

        /* Smaller Go to Home Page Button */
        .btn-home {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            width: auto; /* Adjust to fit the button content */
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
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Cancel Your Booking</h1>
        <p>Enter your booking details below to proceed with cancellation.</p>
    </header>

    <section class="cancel-form">
        <form action="cancel_request.php" method="POST">
            <label for="booking_id">Booking ID:</label>
            <input type="text" id="booking_id" name="booking_id" required>

            <label for="passenger_name">Passenger Name:</label>
            <input type="text" id="passenger_name" name="passenger_name" required>

            <button type="submit">Cancel Booking</button>
        </form>

        <!-- Smaller Go to Home Page Button -->
        <a href="index.php" class="btn-home">Go to Home Page</a>
    </section>

    <footer>
        <p>&copy; 2024 Fly With Us. All rights reserved.</p>
    </footer>
</body>
</html>
