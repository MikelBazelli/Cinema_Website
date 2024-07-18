<?php session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name
require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/navBar.php";
require "../../html/index.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../../css/navBar.css">
    <link rel="stylesheet" href="../../css/imagesStyle.css">
    <link rel="stylesheet" type="text/css" href="../../css/style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinemax</title>

</head>

<body style="background-color: black;">

    <?php
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //First 8 movies from database
    $sql = "SELECT movie_id, title, image_path FROM movies LIMIT 8";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Starting the container for all movies
        echo "<div class='grid-container container'>";

        while ($row = $result->fetch_assoc()) {
            // Each movie gets its own card sepately
            echo "<div class='movie-card'>";

            // Creating forms for each movie
            echo "<form action='details.php' method='get'>";
            echo "<input type='hidden' name='title' value='" . htmlspecialchars($row["title"]) . "'>";

            // wraping the image and title in a container so we edit them better
            echo "<div class='movie-img-container'>";
            echo "<button type='submit' class='movie_img'>";
            echo "<img src='" . htmlspecialchars($row["image_path"]) . "' alt='Movie Image'>";
            echo "</button>";
            echo "<div>" . htmlspecialchars($row["title"]) . "</div>";
            echo "</div>";

            echo "</form>";

            // Closing the movie card div
            echo "</div>";
        }


        // Closing the grid-container div
        echo "</div>";
    } else {
        echo "<div>No results found</div>";
    }
    ?>


    <div class="num-container">
        <form action="userViewMovies.php" method="GET">
            <button type="submit" class="num-button current" id="Page1">1</button>
        </form>

        <form action="moreMovies2.php" method="GET">
            <button type="submit" class="num-button" id="Page2">2</button>
        </form>

        <form action="moreMovies3.php" method="GET">
            <button type="submit" class="num-button" id="Page3">3</button>
        </form>
    </div>




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



    <?php

    // Checking if the search form is submitted and the movie_name field is not empty
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["movie_name"])) {
        $movieName = $_GET["movie_name"];

        if (!empty($movieName)) {
            header("Location: search.php?movie_name=" . urlencode($movieName));
            exit();
        } else {

            echo 'not valid';
        }
    }
    ?>




</body>

</html>