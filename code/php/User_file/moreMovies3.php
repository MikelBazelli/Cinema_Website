<!-- Similar code to moreMovies2, displaying more variations of movies to user -->
<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name
require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/navBar.php";



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../../css/navBar.css">
    <link rel="stylesheet" href="../../css/imagesStyle.css">
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
</head>


<body style="background-color: black;">
    <!-- <div class="movie-grid"> -->
    <?php
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //showing till 100 movies(a cinema website will never have 100 movies, this is used for safety just in case new movies come and worker wants to display)
    $sql = "SELECT movie_id, title, image_path FROM movies LIMIT 100 OFFSET 24";
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
            <button type="submit" class="num-button " id="Page1">1</button>
        </form>

        <form action="moreMovies2.php" method="GET">
            <button type="submit" class="num-button " id="Page2">2</button>
        </form>

        <form action="moreMovies3.php" method="GET">
            <button type="submit" class="num-button current" id="Page3">3</button>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>