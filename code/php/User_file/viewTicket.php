<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name

require "../Tables-MakeDB/makeDBConnection.php";

//redirect if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['userid'];
//showing a ticket if exist based on the query. We want a booking where all the fields match with movie name,seat,room,time etc
$stmt = $conn->prepare("SELECT b.*, m.title, s.showtime, r.RoomName 
                        FROM bookings b
                        JOIN movies m ON b.movie_id = m.movie_id
                        JOIN showtimes s ON b.showtime_id = s.showtime_id
                        JOIN rooms r ON b.rooms_id = r.rooms_id
                        WHERE b.user_id = ?
                        ORDER BY b.booking_id DESC
                        LIMIT 1");

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$bookingStatus = '';

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();

    if ($booking['status'] == 0) { //accepted
        header("Location: profile.php");
        exit;
    }

    $bookingStatus = $booking['status'];
} else {
    $bookingStatus = 'declined'; // No record found = declined
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Movie Tickets</title>
</head>

<body>

    <?php
    if ($bookingStatus === 'declined') {
        echo '<h1>Your Ticket was Declined</h1>';
        echo '<p>Unfortunately, your movie ticket booking has been declined. Please visit your profile for more details or to make another booking.</p>';
        echo "<a href='UserViewMovies.php'>Browese Movies</a>";
    } else {
        echo '<h1>Pending...</h1>';
        echo '<h2>You will be redirected to your profile with your ticket as soon as it\'s accepted</h2>';
    }
    ?>
</body>

</html>