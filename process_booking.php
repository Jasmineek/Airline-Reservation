<?php
include('includes/db.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $flight_id = $_POST['flight_id'];
    $date = $_POST['date'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $seat = $_POST['seat'];
    $additional_passengers = $_POST['additional_passengers'];

    // Retrieve the number of available seats for the selected flight
    $query = "SELECT seats_available FROM flights WHERE id = $flight_id AND date = '$date'";
    $result = mysqli_query($conn, $query);
    $flight = mysqli_fetch_assoc($result);

    if ($flight['seats_available'] > 0) {
        // Decrease available seats by 1 for the current passenger
        $new_seats_available = $flight['seats_available'] - 1;
        $update_query = "UPDATE flights SET seats_available = $new_seats_available WHERE id = $flight_id AND date = '$date'";
        mysqli_query($conn, $update_query);

        // Insert booking into bookings table
        $insert_booking_query = "INSERT INTO bookings (flight_id, passenger_name, seat_number, payment_status) 
                                 VALUES ($flight_id, '$name', '$seat', 'Pending')";
        mysqli_query($conn, $insert_booking_query);

        // Optionally, insert details for additional passengers
        for ($i = 1; $i <= $additional_passengers; $i++) {
            $additional_name = $_POST['additional_name_' . $i];
            // Insert additional passenger details (can be extended)
            $insert_additional_passenger_query = "INSERT INTO passengers (name) VALUES ('$additional_name')";
            mysqli_query($conn, $insert_additional_passenger_query);
        }

        // Redirect to a confirmation page or display a success message
        header("Location: confirmation.php?booking_id=" . mysqli_insert_id($conn));
        exit();
    } else {
        // If no seats are available, redirect back to booking page with a message
        header("Location: booking.php?flight_id=$flight_id&date=$date&error=Seats%20Unavailable");
        exit();
    }
}
?>
