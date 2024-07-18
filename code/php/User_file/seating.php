<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name

require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/navBar.php";

?>

<!DOCTYPE html>
<html>

<head>
    <title>Cinema Seating Plan</title>

    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navBar.css">
    <link rel="stylesheet" href="../../css/imagesStyle.css">

<body style="background-color: black;">

    <style>
        .seating-plan {
            display: grid;
            /*code used to be repeat(8,1fr), this worked in chrome and not firfox.So we adjusted it. We have still 8 columns of 1fr but they are minus 70px. To fix the output*/
            grid-template-columns: repeat(8, calc((100% - 70px) / 8));
            gap: 10px;
            max-width: 600px;
            margin: 0 auto;
            background-color: lightgray;
            padding: 10px;
            grid-template-rows: repeat(4, 1fr);
            position: relative;
            margin-top: 80px;
            border-radius: 6px;
        }

        .seat {
            display: none;
        }

        .seat+label {
            appearance: none;
            padding: 10px;
            text-align: center;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            cursor: pointer;
            display: inline-block;
            margin: 0;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .seat+label:hover {
            background-color: #a8a8a8;
        }

        .seat:checked+label {
            background-color: #a0a0f0;
        }


        .Isle {
            visibility: hidden;
            /* cant view the isle */
        }

        .view_title {
            text-align: center;
            margin-bottom: 55px;
            margin-top: 35px;
            color: white;

        }

        .movie-details {
            color: white;
            margin-left: 90px;
        }

        .seating-plan {
            position: relative;
            margin-top: 80px;

        }

        /* Css code, where u can add some styling without making a div. This is used for small changes where we dont want to don't want to modify it a lot with the divs
         ::before means that we will add this styling ABOVE the .seating-plan. Here screen is created
        */
        .seating-plan::before {
            content: 'Screen';
            position: absolute;
            top: -60px;
            left: 0;
            right: 0;
            height: 50px;
            line-height: 40px;
            background-color: #333;
            border-radius: 50%;
            color: #fff;
            text-align: center;
            font-size: 28px;
            z-index: -1;
        }

        @media (max-width: 600px) {
            .seating-plan {
                width: 95%;
            }

            .seat+label {
                padding: 5px;
                font-size: 13px;
            }

        }

        .seatbtn {
            margin-top: 17px;
            margin-bottom: 10px;
            background-color: #333;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .seatbtn:hover {
            background-color: #555;
        }
    </style>

    </head>

    <?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

        if ($_SERVER["REQUEST_METHOD"] == "GET") {

            $title = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '';
            $showtime = isset($_GET['showtime']) ? htmlspecialchars($_GET['showtime']) : '';
            $room = isset($_GET['room']) ? htmlspecialchars($_GET['room']) : '';

            $movieId = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : null;
            $roomId = isset($_GET['room_id']) ? intval($_GET['room_id']) : null;
            $seatId = isset($_GET['seat_id']) ? intval($_GET['seat_id']) : null;
            $showtimeId = isset($_GET['showtime_id']) ? intval($_GET['showtime_id']) : null;


            echo "<div class='movie-details'>";
            echo "<h2 class='movie-title'>Booking for: $title</h2>";
            echo "<p class='movie-showtime'>Showtime: $showtime</p>";
            echo "<p class='movie-room'>Room: $room</p>";
            echo "</div>";
        }
    ?>


        <form action="payment.php" method="get" id="bookingForm" onsubmit="return validateForm()">

            <input type="hidden" name="title" value="<?php echo $title; ?>">
            <input type="hidden" name="showtime" value="<?php echo $showtime; ?>">
            <input type="hidden" name="room" value="<?php echo $room; ?>">


            <div class="seating-plan">

                <!-- using a php array to hold all seats, space saving -->
                <?php
                $seats = array(
                    "Seat 1", "Seat 2", "Seat 3", "Isle", "Isle", "Seat 4", "Seat 5", "Seat 6",
                    "Seat 7", "Seat 8", "Seat 9", "Isle", "Isle", "Seat 10", "Seat 11", "Seat 12",
                    "Seat 13", "Seat 14", "Seat 15", "Isle", "Isle", "Seat 16", "Seat 17", "Seat 18",
                    "Seat 19", "Seat 20", "Seat 21", "Isle", "Isle", "Seat 22", "Seat 23", "Seat 24"
                );

                // printing each seat/isle inside a div and styling it
                foreach ($seats as $index => $seat) {
                    if ($seat == "Isle") {
                        echo "<div class='isle-seat'></div>";
                    } else {
                        echo "<input type='radio' id='seat$index' class='seat' name='seat' value='$seat'>";
                        echo "<label for='seat$index'>$seat</label>";
                    }
                }

                ?>
                <div class="seatbtn-container">
                    <input type="submit" name="submit" class="seatbtn">
                </div>
            </div>
        </form>

    <?php
    } else {
        // Redirect if NOT logged in
        echo "<script>alert('You must be logged in order to order a ticket!'); window.location.href = 'signIn.php';</script>";
        echo "<p>Welcome, guest! <a href='signIn.php'>Log in</a> or <a href='register.php'>register</a> to enjoy more features.</p>";
    }



    echo "<h2 class='view_title'>VIEW ALSO</h2>";

    $sql = "SELECT movie_id, title, image_path
FROM movies
ORDER BY RAND()
LIMIT 8;
";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {

        echo "<div class='grid-container container'>";

        while ($row = $result->fetch_assoc()) {
            echo "<div class='movie-card'>";
            echo "<form action='details.php' method='get'>";
            echo "<input type='hidden' name='title' value='" . htmlspecialchars($row["title"]) . "'>";
            echo "<div class='movie-img-container'>";
            echo "<button type='submit' class='movie_img'>";
            echo "<img src='" . htmlspecialchars($row["image_path"]) . "' alt='Movie Image'>";
            echo "</button>";
            echo "<div class='movie-title'>" . htmlspecialchars($row["title"]) . "</div>"; // Changed div class for title
            echo "</div>";
            echo "</form>";
            echo "</div>";
        }

        echo "</div>";
    } else {
        echo "<div>No results found</div>";
    }

    ?>






    <script>
        // to alert user that he cant submit an empty form
        function validateForm() {
            var seats = document.querySelectorAll('.seat');
            for (var i = 0; i < seats.length; i++) { //iterating over all seats
                if (seats[i].checked) {
                    return true;
                }
            }
            alert('Please select a seat before submitting!');
            return false;
        }
    </script>



    <div class="container">
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

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>