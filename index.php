<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fly With Us</title>
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

        /* Header Styling */
        header {
            background-color: transparent;
            padding: 0 20px;
            text-align: center;
            color: #fff;
            width: 100%;
            margin-bottom: 40px;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        header p {
            margin: 10px 0 0;
            font-size: 1rem;
        }

        /* Main Content (Search Form) Styling */
        .search-form {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .search-form label {
            display: block;
            font-size: 1rem;
            margin: 10px 0 5px;
            color: #333;
        }

        .search-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .button-container {
            display: flex;
            gap: 15px; /* Space between the buttons */
            justify-content: center;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 20px;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 200px; /* Set a fixed width for uniformity */
            text-decoration: none;
            text-align: center;
        }

        .search-btn {
            background-color: #007bff;
        }

        .search-btn:hover {
            background-color: #0056b3;
        }

        .cancel-btn {
            background-color: #dc3545;
        }

        .cancel-btn:hover {
            background-color: #c82333;
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
        <h1>Welcome to Fly With Us</h1>
        <p>Choose your destination and start your journey!</p>
    </header>

    <section class="search-form">
        <form action="search_results.php" method="POST">
            <label for="origin">Select Origin</label>
            <select name="origin" id="origin" required>
                <option value="">Select City</option>
                <?php
                $query = "SELECT * FROM cities";
                $result = mysqli_query($conn, $query);
                while ($city = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $city['id'] . "'>" . $city['city_name'] . "</option>";
                }
                ?>
            </select>

            <label for="destination">Select Destination</label>
            <select name="destination" id="destination" required>
                <option value="">Select City</option>
                <?php
                mysqli_data_seek($result, 0); // Rewind the result pointer to start
                while ($city = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $city['id'] . "'>" . $city['city_name'] . "</option>";
                }
                ?>
            </select>

            <div class="button-container">
                <button type="submit" class="btn search-btn">Search for Flight</button>
                <a href="cancel_request.php" class="btn cancel-btn">Cancel a Booking</a>
            </div>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Fly With Us. All rights reserved.</p>
    </footer>
</body>
</html>
