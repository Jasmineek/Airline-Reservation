<?php
include('includes/db.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Retrieve origin and destination from the form
$origin_id = $_POST['origin'];
$destination_id = $_POST['destination'];

// Query to get all available future dates for flights between origin and destination
$query = "SELECT f.id AS flight_id, f.flight_name, f.date, f.seats_available 
          FROM flights f
          WHERE f.origin_id = $origin_id AND f.destination_id = $destination_id
          AND f.date >= CURDATE()  -- Only future flights
          ORDER BY f.date ASC"; // Order by date ascending to show future flights first

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Availability</title>
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
            justify-content: flex-start;
            min-height: 100vh;
            background: #f7f7f7; /* Light gray background */
        }

        /* Header Styling */
        header {
            background-color: #007bff;
            padding: 80px 20px;
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

        /* Available Flights Section */
.available-flights {
    width: 100%;
    max-width: 600px; /* Set max width for container */
    padding: 30px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-bottom: 40px;
    margin-top: 40px;
}

/* Available Flights Section */
.available-flights {
    width: 100%;
    max-width: 600px; /* Set max width for container */
    padding: 30px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-bottom: 40px;
    margin-top: 40px;

    display: flex;
    flex-direction: column;
    align-items: center; /* Center align all children horizontally */
    justify-content: center; /* Center align all children vertically */
}

/* Flight Box Styling */
.flight {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    text-align: center;
    width: 100%;
    max-width: 450px; /* Set max-width for flight box */
}

/* Flight Box Text Styling */
.flight p {
    font-size: 1.1rem;
    margin: 8px 0;
    color: #555;
}

.book-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    font-size: 1.1rem;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 10px;
    align-self: center; /* Ensure button is centered inside the box */
}

.book-btn:hover {
    background-color: #467da2;
}




        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Available Flights</h1>
            <p>Select a flight date from the options below.</p>
        </div>
    </header>

    <section class="available-flights">
        <h2>Available Dates</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            // Loop through each available flight date
            while ($flight = mysqli_fetch_assoc($result)) {
                echo "<div class='flight'>
                        <p><strong>Flight:</strong> " . $flight['flight_name'] . "</p>
                        <p><strong>Date:</strong> " . $flight['date'] . "</p>
                        <p><strong>Available Seats:</strong> " . $flight['seats_available'] . "</p>
                        <!-- Book Now button with flight_id and date parameters -->
                        <a href='booking.php?flight_id=" . $flight['flight_id'] . "&date=" . $flight['date'] . "' class='book-btn'>Book Now</a>
                      </div>";
            }
        } else {
            echo "<p>No available flights for the selected route.</p>";
        }
        ?>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 Fly With Us. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
