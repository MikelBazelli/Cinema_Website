<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name
require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/navBar.php";


?>

<!DOCTYPE html>
<html>

<head>
    <title>Movie Details</title>
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navBar.css">
    <link rel="stylesheet" href="../../css/imagesStyle.css">

    <style>
        .view_title {
            text-align: center;
            margin-bottom: 55px;
            margin-top: 35px;
            color: white;
        }

        .movie-details {
            margin: 2% 10%;
            padding: 20px;
            color: white;
            display: flex;
            gap: 20px;
            background-color: #909090;
            border-radius: 10px;
        }

        .movie-image {
            flex-shrink: 0;
            width: 260px;
            height: 340px;
            margin-top: 2%;
        }

        .movie-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            flex-grow: 1;
            text-align: justify;
        }

        .movie-meta {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 968px) {
            .movie-details {
                flex-direction: column;
                align-items: center;
            }

            .movie-image {
                margin-top: 0;
            }

            .movie-info {
                text-align: center;
                align-items: center;
            }
        }
    </style>

</head>


<?php



// Setting variables
$movieId = null;
$movieTitle = null;

// In most forms where it has to do with movie details, GET method is used.
//Because whenever I'd click button with empty info, all info would be gone
//By research, I used get method and when i refresh with empty inputs, Nothing gets lost beacause the URL has info from the previous submited pages

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $movieTitle = $_GET['title'];

    $sql = "SELECT movies.*, rooms.RoomName, GROUP_CONCAT(DISTINCT showtimes.showtime ORDER BY showtimes.showtime SEPARATOR ', ') AS ShowTimes
                FROM movies
                JOIN rooms ON movies.movie_id = rooms.movie_id
                JOIN showtimes ON movies.movie_id = showtimes.movie_id
                WHERE movies.title = ?
                GROUP BY movies.movie_id";
    $stmt = $conn->prepare($sql);  //Using sql prepared statmets for sequrity to prevent sql injection
    $stmt->bind_param("s", $movieTitle); // blind parameter for the query
}
$stmt->execute(); // executing the query
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $showtimes = explode(', ', $row["ShowTimes"]);

        //use of htmlspecialchars() method to sanitize the output
        echo "<div class='movie-details'>"; // wrapping each movie inside a div in order to style it
        echo "<img src='" . htmlspecialchars($row["image_path"]) . "' alt='Movie Image' class='movie-image'>";
        echo "<div class='movie-info'>";
        echo "<h1>" . htmlspecialchars($row["title"]) . "</h1>";
        echo "<div class='movie-description'>Description: " . htmlspecialchars($row["description"]) . "</div>";

        echo "<div class='movie-meta'>";
        echo "<p>Price: " . htmlspecialchars($row["movie_price"]) . "</p>";
        echo "<p>Room: " . htmlspecialchars($row["RoomName"]) . "</p>";
        echo "<p>Showtimes:</p>";
        foreach ($showtimes as $showtime) {
            echo "<form action='seating.php' method='get' class='showtime-form'>";
            echo "<input type='hidden' name='title' value='" . htmlspecialchars($row['title']) . "'>";
            echo "<input type='hidden' name='showtime' value='" . htmlspecialchars($showtime) . "'>";
            echo "<input type='hidden' name='room' value='" . htmlspecialchars($row['RoomName']) . "'>";
            echo "<input type='hidden' name='image' value='" . htmlspecialchars($row['image_path']) . "'>";
            echo "<button type='submit' class='showtime-button'>" . htmlspecialchars($showtime) . "</button>";
            echo "</form>";
        }
        echo "</div>"; // Closing movie-meta
        echo "</div>"; // Closing movie-info
        echo "<div style='clear:both;'></div>"; // Clearing the float, this makes the text from description appear next to image and not go wrap around it
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<div>No movie found</div>";
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Viewing the movies
echo "<h2 class='view_title'>VIEW ALSO</h2>";

$sql = "SELECT movie_id, title, image_path
FROM movies
ORDER BY RAND() /*here we pick only 8 results from database but are random because of the RAND() sql fucntion*/ 
LIMIT 8;
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // container for all movies
    echo "<div class='grid-container container'>";

    while ($row = $result->fetch_assoc()) {
        // Each movie gets its own card
        echo "<div class='movie-card'>";

        // Creating a form for each movie
        echo "<form action='details.php' method='get'>";
        echo "<input type='hidden' name='title' value='" . htmlspecialchars($row["title"]) . "'>";
        echo "<div class='movie-img-container'>";
        echo "<button type='submit' class='movie_img'>";
        echo "<img src='" . htmlspecialchars($row["image_path"]) . "' alt='Movie Image'>";
        echo "</button>";
        echo "<div>" . htmlspecialchars($row["title"]) . "</div>";
        echo "</div>";

        echo "</form>";


        // Close the movie card div
        echo "</div>";
    }

    // Close the grid-container div
    echo "</div>";
} else {
    echo "<div>No results found</div>";
}
?>


<!-- FOOTER -->

<body style="background-color: black;">

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