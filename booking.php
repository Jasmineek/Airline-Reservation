<?php
include('includes/db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if flight_id and date are set via GET parameters
if (!isset($_GET['flight_id']) || !isset($_GET['date'])) {
    die("Flight ID or Date parameter missing!");
}

$flight_id = $_GET['flight_id'];
$date = $_GET['date'];

// Query to get the flight details based on flight_id and date
$query = "SELECT * FROM flights WHERE id = $flight_id AND date = '$date'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$flight = mysqli_fetch_assoc($result);

// Check if flight exists
if (!$flight) {
    die("Flight not found.");
}

// Query to get the available seat numbers
$query_seats = "SELECT seat_number FROM bookings WHERE flight_id = $flight_id AND booking_date = '$date'";
$result_seats = mysqli_query($conn, $query_seats);

if (!$result_seats) {
    die("Query for seats failed: " . mysqli_error($conn));
}

$booked_seats = [];
while ($row = mysqli_fetch_assoc($result_seats)) {
    $booked_seats[] = $row['seat_number'];
}

// Define total seats available for the flight (assuming 15 seats for all flights)
$total_seats = 15;
$available_seats = [];

for ($i = 1; $i <= $total_seats; $i++) {
    if (!in_array($i, $booked_seats)) {
        $available_seats[] = $i;
    }
}

// Process the booking after form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize POST data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $seats = mysqli_real_escape_string($conn, $_POST['seats']);
    
    // Insert booking into the database
    $query = "INSERT INTO bookings (flight_id, passenger_name, seat_number, booking_date, payment_status) 
              VALUES ($flight_id, '$name', '$seats', '$date', 'Paid')";

    if (mysqli_query($conn, $query)) {
        // Get the booking ID after insertion
        $booking_id = mysqli_insert_id($conn);

        // Insert passenger details into the passengers table
        $passenger_query = "INSERT INTO passengers (booking_id, name, phone, address) 
                            VALUES ($booking_id, '$name', '$phone', '$address')";
        
        if (!mysqli_query($conn, $passenger_query)) {
            die("Passenger insertion failed: " . mysqli_error($conn));
        }

        // Redirect to payment page
        header("Location: payment.php?flight_id=" . $flight_id . "&date=" . $date . "&booking_id=" . $booking_id);
        exit;
    } else {
        die("Booking insertion failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - <?php echo htmlspecialchars($flight['flight_name']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Add your styles here */
        /* General Styling */
/* Body and Container Styling */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: #f7f7f7;
    justify-content: center; /* Vertically center the content */
    align-items: center; /* Horizontally center the content */
}

/* Main content container should expand to push footer to the bottom */
.container {
    flex: 1;
}

/* Payment Form Section */
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

/* Header Styling */
header {
    background-color: #007bff;
    padding: 55px 20px; /* Decreased top padding */
    text-align: center;
    color: white;
    width: 100%;
    margin-bottom: 30px;
}

header h1 {
    font-size: 2rem;
    margin: 0;
}

header p {
    margin: 10px 0 0;
    font-size: 1rem;
}


.payment-form h2 {
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 5px;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-top: 5px;
}

/* Seat Selection */
.seats-group {
    margin-top: 20px;
    text-align: center; /* Align the seat label to the center */
}

.seats-group label {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #555;
    display: block;
}

.seat-options {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center; /* Center the seat options horizontally */
    margin: 0 auto; /* Ensures it's centered */
    max-width: 600px; /* Ensure the seat options don't overflow */
}

.seat-option {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center; /* Center the text within the button */
    display: inline-flex; /* To align input and label in one line */
    align-items: center; /* Vertically align the input and label */
}

.seat-option.disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

/* Button Styling */
.btn {
    display: inline-block;
    padding: 12px 20px;
    background-color: #007bff;
    color: white;
    font-size: 1.1rem;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 20px;
    text-align: center;
    width: 100%; /* Make button fill available space */
    max-width: 200px; /* Limit width */
    margin: 20px auto; /* Center the button horizontally */
}

.btn:hover {
    background-color: #467da2;
}


/* Footer Styling */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px;
    width: 100%;
    margin-top: auto; /* Ensures footer stays at the bottom */
}

    </style>
</head>
<body>
    <header>
        <h1>Booking Information</h1>
        <p>Proceed to booking for flight: <?php echo htmlspecialchars($flight['flight_name']); ?> on <?php echo $date; ?></p>
    </header>

    <section class="payment-form">
        <h2>Booking Details</h2>
        <form action="booking.php?flight_id=<?php echo $flight_id; ?>&date=<?php echo $date; ?>" method="POST">
            <!-- Hidden fields for flight_id and date -->
            <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
            <input type="hidden" name="date" value="<?php echo $date; ?>">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>

            <!-- Seat Selection -->
            <div class="seats-group">
                <label for="seats">Select Seat</label>
                <div class="seat-options">
                    <?php
                    // Display available seats
                    foreach ($available_seats as $seat) {
                        echo "<div class='seat-option " . (in_array($seat, $booked_seats) ? 'disabled' : '') . "'>
                                <input type='radio' id='seat_$seat' name='seats' value='$seat' " . (in_array($seat, $booked_seats) ? 'disabled' : '') . ">
                                <label for='seat_$seat'>Seat $seat</label>
                              </div>";
                    }
                    ?>
                </div>
            </div>

            <button type="submit" class="btn">Proceed to Payment</button>
        </form>
    </section>
</body>
</html>
