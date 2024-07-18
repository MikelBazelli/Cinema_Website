<!-- USER PROFILE -->
<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name
require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/navBar.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navBar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/profile.css">

    <title>Profile</title>
</head>

<body>


    <?php


    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

        // Taking the user id from the session and storing it
        $userId = $_SESSION["userid"];



        echo "<h2 class='profile'><b>PROFILE</b></h2>";

        // Prepared statement to prevent sql injection
        $stmt = $conn->prepare("SELECT firstname, lastname, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId); // "i" indicates the parameter is an integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // outputting the result
            while ($row = $result->fetch_assoc()) {
                echo "<div class='profile-container'>";
                echo "<p><b>Name:</b> " . htmlspecialchars($row["firstname"]) . " " . htmlspecialchars($row["lastname"]) . "</p>";
                echo "<p><b>Email:</b> " . htmlspecialchars($row["email"]) . "</p>";
                echo "<a href='logout.php' class='logout'>Logout</a>";
                echo "</div>";
            }
        } else {
            echo "0 results";
        }
        $stmt->close();
        // On the first place user is ineligible to access profile because they can't view it, they only view REGISTER
        //But we use the else for more safety 
    } else {
        // Redirect if not logged in
        echo "<script>alert('You must be logged in to view this page.'); window.location.href = 'signIn.php';</script>";
    }


    ?>

    <div class="container">
        <div class="ticket-wrapper">
            <?php

            $userId = $_SESSION['userid'];

            $stmt = $conn->prepare("SELECT u.firstname, u.lastname, m.title, s.showtime, r.RoomName, st.seat_number 
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN movies m ON b.movie_id = m.movie_id
            JOIN showtimes s ON b.showtime_id = s.showtime_id
            JOIN rooms r ON b.rooms_id = r.rooms_id
            JOIN seats st ON b.seat_id = st.seat_id
            WHERE b.user_id = ? AND b.status = 0"); //status 0 means booked
            //We want used to view all info of the ticket


            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();


            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Displaing ticket information with modal functionality with the help of w3css and modifications
                    echo "<div class='ticket' onclick='openModal(\"" . htmlspecialchars($row['title']) . "\", \"" . htmlspecialchars($row['RoomName']) . "\", \"" . htmlspecialchars($row['showtime']) . "\", \"" . htmlspecialchars($row['firstname']) . "\", \"" . htmlspecialchars($row['lastname']) . "\", \"" . htmlspecialchars($row['seat_number']) . "\")'>";

                    echo "<h3>Movie Title: " . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p>Showtime: " . htmlspecialchars($row['showtime']) . "</p>";
                    echo "<p>Room: " . htmlspecialchars($row['RoomName']) . "</p>";
                    echo "<p>Seat: " . htmlspecialchars($row['seat_number']) . "</p>";
                    echo "<p><b> " . htmlspecialchars($row['firstname']) . " " . htmlspecialchars($row['lastname']) . "</b></p>";

                    echo "</div>";
                }
            } else {
                echo "No bookings found.";
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modalContent" class="modal-content"></div>
    </div>
    <!-- Making modal fucntionable with w3css -->
    <script>
        function openModal(title, room, showtime, firstname, lastname, seatNumber) {
            var modal = document.getElementById('myModal');
            var modalContent = document.getElementById("modalContent");
            modal.style.display = "block";
            modalContent.innerHTML = "<div class='modal-ticket'>" + "<h3>" + title + "</h3>" + "<p>" + showtime + "</p>" + "<p>" + room + "</p>" + "<p>Seat: " + seatNumber + "</p>" + "<h3 style='color:black'><b>" + firstname + " " + lastname + "</b></h3>" + "</div>";
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        }
        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            closeModal();
        }
    </script>

</body>

</html>








<div class="container" style="margin-top: 150px;">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="userViewMovies.php" class="nav-link px-2 text-white">Home</a></li>
            <li class="nav-item"><a href="moreMovies2.php" class="nav-link px-2 text-white">Movies</a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link px-2 text-white">Contact us</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link px-2 text-white">About</a></li>
        </ul>
        <p class="text-center text-white">© <?php echo date("Y"); ?> Cinemax, Inc</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>