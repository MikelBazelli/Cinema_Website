<?php 
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name

?>


<!-- CODE WHERE WE FUCNTION THE SEARCHBAR -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navBar.css">
    <link rel="icon" type="image/x-icon" href="../images/logo.png"><!--Favicon-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .Error {
            color: white;
            text-align: center;
            font-size: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 20vh;
            /* this is used to align items verticaly and hozirontally*/
        }

        .return {
            float: right;
            margin-right: 30%;
            font-size: 20px;
            margin-top: -6px;
        }
    </style>

    <title>Redirect</title>
</head>




<body style="background-color: black;">

    <?php
    require "../Tables-MakeDB/makeDBConnection.php";


    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['movie_name'])) {
        $movieName = trim($_GET['movie_name']);

        if (!empty($movieName)) {
            // Protecting against sql injection
            $movieName = $conn->real_escape_string($movieName);

            // Accesing movie details using movie name from the submited search bar
            $sql = "SELECT movie_id, title FROM movies WHERE title LIKE ?";
            $stmt = $conn->prepare($sql);
            $searchTerm = '%' . $movieName . '%'; // % is used in sql. We use it to track movie names
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // redirecting to the first matched movie
                $row = $result->fetch_assoc();
                //by adding these info into the url, we can view specific info into the details page. We can view them based on the movie user has searched
                header("Location: details.php?title=" . urlencode($row['title']));
                exit();
            } else {
                echo "<div class='Error'>No movie found with that name.</div>";
            }
        } else {
            // Redirect to userViewMovies.php if movie_name is empty
            header("Location: userViewMovies.php");
            exit();
        }
    }
    ?>
    <!-- Search bar -->
    <div class="nav-container">

        <div class="wrapper">
            <form action="search.php" method="get">
                <input type="text" name="movie_name" placeholder="Search for a movie...">
                <button class="searchbtn" type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>

    </div>


    <!-- When user types a movie that doesnt exit, they will stay in this page and not go to details -->
    <a href="UserViewMovies.php" class="return">Go Back</a>

    <section style="margin-top:60px">

    </section>
    <?php
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT movie_id, title, image_path FROM movies ORDER BY RAND() LIMIT 8";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='grid-container container'>";

        while ($row = $result->fetch_assoc()) {
            echo "<div class='movie-card'>";

            // Create form for each movie
            echo "<form action='details.php' method='get'>";
            echo "<input type='hidden' name='title' value='" . htmlspecialchars($row["title"]) . "'>";
            echo "<button type='submit' class='movie_img'>";
            echo "<img src='" . htmlspecialchars($row["image_path"]) . "' alt='Movie Image'>";
            echo "<div>" . htmlspecialchars($row["title"]) . "</div>";
            echo "</button>";
            echo "</form>";

            echo "</div>";
        }

        echo "</div>";
    } else {
        echo "<div>No results found</div>";
    }

    ?>
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

</html>