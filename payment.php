<?php
include('includes/db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if flight_id, date, and booking_id are set via GET parameters
if (!isset($_GET['flight_id']) || !isset($_GET['date']) || !isset($_GET['booking_id'])) {
    die("Flight ID, Date, or Booking ID parameter missing!");
}

$flight_id = $_GET['flight_id'];
$date = $_GET['date'];
$booking_id = $_GET['booking_id'];
$amount = 1000; // Set a fixed amount or retrieve based on booking/flight details if available

// Query to get booking details
$query_booking = "SELECT * FROM bookings WHERE id = $booking_id";
$result_booking = mysqli_query($conn, $query_booking);

if (!$result_booking) {
    die("Query failed: " . mysqli_error($conn));
}

$booking = mysqli_fetch_assoc($result_booking);

// Check if booking exists
if (!$booking) {
    die("Booking not found.");
}

// Handle form submission for payment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = 'Paid'; // Payment status
    $payment_date = date("Y-m-d H:i:s"); // Current timestamp

    // Insert payment data into payments table
    $query_payment = "INSERT INTO payments (booking_id, amount, status, payment_date) VALUES ($booking_id, $amount, '$status', '$payment_date')";
    if (!mysqli_query($conn, $query_payment)) {
        die("Payment insertion failed: " . mysqli_error($conn));
    }

    // Update payment status in the bookings table
    $query_update = "UPDATE bookings SET payment_status = 'Paid' WHERE id = $booking_id";
    if (!mysqli_query($conn, $query_update)) {
        die("Booking update failed: " . mysqli_error($conn));
    }

    // Redirect to confirmation page after payment
    header("Location: confirmation.php?booking_id=" . $booking_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Flight Booking</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            padding: 80px 20px;
            color: white;
            width: 100%;
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 2rem;
        }

        .payment-form {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 40px;
        }

        .payment-form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        .btn {
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #467da2;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
            position: relative;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Complete Your Payment</h1>
        <p>Booking ID: <?php echo $booking['id']; ?></p>
    </header>

    <section class="payment-form">
        <h2>Payment Information</h2>
        <p>Amount to be paid: ₹<?php echo number_format($amount, 2); ?></p>
        <form action="payment.php?flight_id=<?php echo $flight_id; ?>&date=<?php echo $date; ?>&booking_id=<?php echo $booking_id; ?>" method="POST">
            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
            </div>

            <div class="form-group">
                <label for="expiry_date">Expiry Date</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
            </div>

            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" required>
            </div>

            <button type="submit" class="btn">Complete Payment</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Fly With Us. All rights reserved.</p>
    </footer>
</body>
</html>
