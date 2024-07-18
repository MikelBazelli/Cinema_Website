<?php session_start(); // Starting the session at the beginning of the script
require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/worker1.php";

// Requesting to view user info, their accoun/ mail and fullname and info from the form they submited when booking

$sql = "SELECT 
            u.id AS user_id,
            u.firstname, 
            u.lastname, 
            u.email, 
            m.title, 
            s.showtime, 
            t.seat_number, 
            b.booking_time, 
            tk.customer_name, 
            tk.customer_lname, 
            tk.customer_email, 
            tk.customer_phone,
            b.booking_id
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN movies m ON b.movie_id = m.movie_id
        JOIN showtimes s ON b.showtime_id = s.showtime_id
        JOIN seats t ON b.seat_id = t.seat_id
        JOIN tickets tk ON b.booking_id = tk.booking_id";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // styling the output to look like a ticket/request

        echo "<div class='form-div-request'>";

        echo "<form action='bookingActionHandler.php' method='post'>"; //if worker clicks decline or accept, they request will be edit on that page

        // User Account Information
        echo "<strong>Info of User Account:</strong><br>";
        echo "Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
        echo "Email: " . $row["email"] . "<br>";
        echo "<br>";

        // Booking Information
        echo "<strong>Info of Booking:</strong><br>";
        echo "Movie: " . $row["title"] . "<br>";
        echo "Showtime: " . $row["showtime"] . "<br>";
        echo "Seat: " . $row["seat_number"] . "<br>";
        echo "Booking Time: " . $row["booking_time"] . "<br>";
        echo "Customer Name: " . $row["customer_name"] . " " . $row["customer_lname"] . "<br>";
        echo "Customer Email: " . $row["customer_email"] . "<br>";
        echo "Customer Phone: " . $row["customer_phone"] . "<br>";

        // Hidden input to hold the booking id
        echo "<input type='hidden' name='booking_id' value='" . $row['booking_id'] . "'>";

        // Accept and Decline buttons
        echo "<input type='submit' name='action' value='Accept' class='accept'>";
        echo "<input type='submit' name='action' value='Decline' class='decline'>";

        echo "</form>";
        echo "</div>"; // End of the div for each booking form

    }
} else {
    echo "<h2>0 results<h2>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="stylesheet" type="text/css" href="../../css/woker1.css">


    <style>
        .form-div-request {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            width: 90%;
            margin: auto;
        }

        form {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        form strong {
            color: #333;
            font-size: 1.1em;
        }


        form br {
            margin-bottom: 10px;
        }

        .accept,
        .decline {
            padding: 10px 20px;
            border: none;
            color: white;
            cursor: pointer;
            margin-right: 10px;
        }

        .accept {
            background-color: #4CAF50;
        }

        .decline {
            background-color: #f44336;
        }

        .accept:hover,
        .decline:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

</body>

</html>