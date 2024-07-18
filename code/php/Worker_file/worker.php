<?php
session_start(); // Starting the session at the beginning of the script

require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/worker1.php";
require "makeWorker.php";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>upload</title>
    <link rel="stylesheet" type="text/css" href="../../css/woker1.css">

</head>

<body>

    <div class="form-container">
        <h2 style="text-align: center;">Insert Movies</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

            <label for="movie_title" class="form-label">Movie Title:</label>
            <input type="text" id="movie_title" name="movie_title" class="form-input" required><br>

            <label for="description" class="form-label">Description:</label>
            <textarea rows="4" cols="50" name="description" class="form-textarea" required></textarea><br>

            <label for="showtimes" class="form-label">Showtimes (separate with commas if many):</label>
            <input type="text" id="showtimes" name="showtimes" class="form-input" required placeholder="2024-01-26 15:30:00"><br>

            <label for="fileToUpload" class="form-label">Select Image to upload:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" class="form-file" required><br>

            <label for="room_name" class="form-label">Room Name:</label>
            <input type="text" id="room_name" name="room_name" class="form-input" required><br>

            <label for="movie_price" class="form-label">Ticket Price:</label>
            <input type="text" id="movie_price" name="movie_price" class="form-input" required><br>

            <input type="submit" value="Upload" name="button2" class="form-submit">
        </form>
    </div>


    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['movie_title']) && !empty($_POST['description']) && !empty($_POST['showtimes']) && !empty($_FILES['fileToUpload']['name'])) {

            echo "Form submitted successfully!";
        } else {
            echo "All form fields are required. Please fill in all the fields.";
        }
    }

    ?>

    <table style="border:1px; border-style:solid; border-color:black">

        <?php
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        require "../Tables-MakeDB/makeDBConnection.php";


        ?>
        <div class="table-container">
            <?php
            $sql = "SELECT 
            movies.movie_id, 
            movies.title, 
            movies.movie_price, 
            movies.image_path, 
            movies.description, 
            rooms.Roomname,
            GROUP_CONCAT(showtimes.showtime SEPARATOR ', ') AS Showtimes 
        FROM movies 
        JOIN rooms ON movies.movie_id = rooms.movie_id 
        LEFT JOIN showtimes ON movies.movie_id = showtimes.movie_id 
        GROUP BY movies.movie_id";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='movie-card'>";

                    echo "<img src='" . $row["image_path"] . "' alt='Movie Image'>";

                    echo "<div class='movie-info'><strong>Movie ID:</strong> " . $row["movie_id"];
                    echo "</div>";

                    echo "<div class='movie-info'><strong>Title:</strong> " . $row["title"];
                    echo "</div>";

                    echo "<div class='movie-info'><strong>Showtimes:</strong> " . $row["Showtimes"];
                    echo "</div>";

                    echo "<div class='movie-info'><strong>Room:</strong> " . $row["Roomname"];
                    echo "</div>";

                    echo "<div class='movie-info'><strong>Price:</strong> €" . $row["movie_price"];
                    echo "</div>";


                    echo "<div class='movie-info'><strong>Description:</strong> " . $row["description"];
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<p>No movies found.</p>";
            }
            ?>
        </div>




    </table>


</body>

</html>