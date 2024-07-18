<?php session_start(); // Starting the session at the beginning of the script
require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/worker1.php";



if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlentities($data);
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_title'])) {

        if (!empty($_POST["movie_id"]) && !empty($_POST["movie_title"])) {
            $movie_id = test_input($_POST["movie_id"]);
            $movie_title = test_input($_POST["movie_title"]);

            $table = "movies";

            $stmt = $conn->prepare("UPDATE $table SET title = ?  WHERE movie_id = ?");

            // Bind parameters to the prepared statement
            $kurac = $stmt->execute([$movie_title, $movie_id]);

            // Execute the statement
            if ($kurac) {
                $added = "Record updated successfully";
            } else {
                // Print an error message if execution fails
                $error = "Error updating record: " . $stmt->error;
            }
        } else {
            $required = "Error: Movie ID and Title are required.";
        }
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_description'])) {

        if (!empty($_POST["movie_id2"]) && !empty($_POST["movie_description"])) {
            $movie_id = test_input($_POST["movie_id2"]);
            $movie_description = test_input($_POST["movie_description"]);
            $table = "movies";

            $stmt = $conn->prepare("UPDATE $table SET description = ?  WHERE movie_id = ?");

            // Bind parameters to the prepared statement
            $kurac = $stmt->execute([$movie_description, $movie_id]);

            // Execute the statement
            if ($kurac) {
                $added2 = "Record updated successfully";
            } else {
                // Print an error message if execution fails
                $error2 = "Error updating record: " . $stmt->error;
            }
        } else {
            $required2 = "Error: Movie ID and description are required.";
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_image'])) {

        if (!empty($_POST["movie_id3"]) && !empty($_FILES["movie_file"]["name"])) {
            $movie_id = test_input($_POST["movie_id3"]);

            // Check if a file was uploaded without errors
            if (!empty($_FILES['movie_file']['name']) && $_FILES['movie_file']['error'] === 0) {
                // Specify the directory where you want to save the uploaded file
                $uploadDir = '../../images/';

                // Get the filename and move the file to the specified directory
                $movie_file = test_input($_FILES['movie_file']['name']);
                $file_path = $uploadDir . basename($movie_file);

                // Check if the uploaded file is an image
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["movie_file"]["tmp_name"]);
                if ($check === false) {
                    $imgError1 = "Error: File is not an image.";
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($file_path)) {
                    $imgError2 = "Error: Sorry, file already exists.";
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["movie_file"]["size"] > 5000000) {
                    $imgError3 = "Error: Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // Allow only specific image file types
                $allowedImageTypes = ["jpg", "jpeg", "png", "gif"];
                if (!in_array($imageFileType, $allowedImageTypes)) {
                    $imgError4 = "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // If $uploadOk is still 1, move the file to the specified directory and update the database
                if ($uploadOk == 1) {
                    move_uploaded_file($_FILES['movie_file']['tmp_name'], $file_path);

                    $table = "movies";
                    $stmt = $conn->prepare("UPDATE $table SET image_path = ?  WHERE movie_id = ?");
                    $kurac = $stmt->execute([$file_path, $movie_id]);

                    if ($kurac) {
                        $added3 = "Record updated successfully";
                    } else {
                        $error3 = "Error updating record: " . $stmt->error;
                    }
                } else {
                    $error_files = "Error uploading file.";
                }
            }
        } else {
            $required3 = "Error: file and id are required.";
        }
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_time'])) {

        if (!empty($_POST["movie_id4"]) && !empty($_POST["movie_time"])) {
            $movie_id = test_input($_POST["movie_id4"]);
            $movie_time = test_input($_POST["movie_time"]);
            $table = "showtimes";

            $stmt = $conn->prepare("UPDATE $table SET showtime = ? WHERE movie_id = ?");

            // Bind parameters to the prepared statement
            $kurac = $stmt->execute([$movie_time, $movie_id]);

            // Execute the statement
            if ($kurac) {
                $added4 = "Record updated successfully";
            } else {
                // Print an error message if execution fails
                $error4 = "Error updating record: " . $stmt->error;
            }
        } else {
            $required4 = "Error: Movie time and id are required.";
        }
    }
}






if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_room'])) {

        if (!empty($_POST["movie_id5"]) && !empty($_POST["movie_room"])) {
            $movie_id = test_input($_POST["movie_id5"]);
            $movie_room = test_input($_POST["movie_room"]);
            $table = "rooms";

            $stmt = $conn->prepare("UPDATE $table SET RoomName = ? WHERE movie_id = ?");

            // Bind parameters to the prepared statement
            $kurac = $stmt->execute([$movie_room, $movie_id]);

            // Execute the statement
            if ($kurac) {
                $added5 = "Record updated successfully";
            } else {
                // Print an error message if execution fails
                $error5 = "Error updating record: " . $stmt->error;
            }
        } else {
            $required5 = "Error: Movie room and id are required.";
        }
    }
}








if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_price'])) {

        if (!empty($_POST["movie_id6"]) && !empty($_POST["movie_price"])) {
            $movie_id = test_input($_POST["movie_id6"]);
            $movie_price = test_input($_POST["movie_price"]);
            $table = "movies";

            $stmt = $conn->prepare("UPDATE $table SET movie_price = ? WHERE movie_id = ?");

            // Bind parameters to the prepared statement
            $kurac = $stmt->execute([$movie_price, $movie_id]);

            // Execute the statement
            if ($kurac) {
                $added6 = "Record updated successfully";
            } else {
                // Print an error message if execution fails
                $error6 = "Error updating record: " . $stmt->error;
            }
        } else {
            $required6 = "Error: Movie price and id are required.";
        }
    }
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" type="text/css" href="../../css/woker1.css">


    <style>
        .form-grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 70px;
            padding: 20px;
            margin: auto;
            background-color: #c9c9c9;
            width: 85%;

        }

        .form-container {
            margin-top: 20px;
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            width: 75%;
            height: 495px;
            margin-bottom: 60px;

        }

        @media (max-width: 1100px) {
            .form-grid-container {
                grid-template-columns: 1fr;
            }


        }
    </style>

<body>
    <div class="form-grid-container">

        <div class="form-container">
            <form name="form1" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h2>CHANGE TITLE</h2>

                <label for="movie_id" class="form-label">ADD AN ID</label><br>
                <input type="text" id="movie_id" name="movie_id" class="form-input"><br><br><br>

                <label for="movie_title" class="form-label">UPDATE NAME</label><br>
                <input type="text" id="movie_title" name="movie_title" class="form-input"><br><br>

                <input type="submit" name="update_title" value="UPDATE" class="form-submit">

                <?php
                if (isset($added)) {
                    echo "<p style='color: green;'>$added</p>";
                }
                if (isset($error)) {
                    echo "<p style='color: red;'>$error</p>";
                }
                if (isset($required)) {
                    echo "<p style='color: red;'>$required</p>";
                }

                ?>
            </form>
        </div>



        <div class="form-container">
            <form name="form2" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h2>CHANGE DESCRIPTION</h2>

                <label for="movie_id2" class="form-label">ADD AN ID</label><br>
                <input type="text" id="movie_id2" name="movie_id2" class="form-input"><br><br><br>

                <label for="movie_description" class="form-label">UPDATE DESCRIPTION</label><br>
                <textarea rows="4" cols="50" id="movie_description" name="movie_description" class="form-textarea"></textarea><br><br>
                <input type="submit" name="update_description" value="UPDATE2" class="form-submit">
                <?php
                if (isset($added2)) {
                    echo "<p style='color: green;'>$added2</p>";
                }
                if (isset($error2)) {
                    echo "<p style='color: red;'>$error2</p>";
                }
                if (isset($required2)) {
                    echo "<p style='color: red;'>$required2</p>";
                }

                ?>

            </form>
        </div>



        <div class="form-container">

            <form name="form3" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                <h2>CHANGE IMAGE</h2>

                <label for="movie_id3" class="form-label">ADD AN ID</label><br>
                <input type="text" id="movie_id3" name="movie_id3" class="form-input"><br><br><br>

                <label for="movie_file" class="form-label">Select Image to upload</label>
                <input type="file" id="movie_file" name="movie_file" class="form-file"><br><br>

                <input type="submit" name="update_image" value="UPDATE3" class="form-submit">
                <?php
                if (isset($added3)) {
                    echo "<p style='color: green;'>$added3</p>";
                }
                if (isset($error3)) {
                    echo "<p style='color: red;'>$error3</p>";
                }
                if (isset($required3)) {
                    echo "<p style='color: red;'>$required3</p>";
                }
                if (isset($error_files)) {
                    echo "<p style='color: red;'>$error_files</p>";
                }
                if (isset($imgError1)) {
                    echo "<p style='color: red;'>$imgError1</p>";
                }
                if (isset($imgError2)) {
                    echo "<p style='color: red;'>$imgError2</p>";
                }
                if (isset($imgError3)) {
                    echo "<p style='color: red;'>$imgError3</p>";
                }
                if (isset($imgError4)) {
                    echo "<p style='color: red;'>$imgError4</p>";
                }


                ?>
            </form>
        </div>

        <div class="form-container">

            <form name="form4" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h2>CHANGE TIME</h2>

                <label for="movie_id4" class="form-label">ADD AN ID</label><br>
                <input type="text" id="movie_id4" name="movie_id4" class="form-input"><br><br><br>

                <label for="movie_time" class="form-label">CHANGE TIME</label><br>
                <input type="text" name="movie_time" id="movie_time" class="form-input"><br><br>

                <input type="submit" name="update_time" value="UPDATE4" class="form-submit">
                <?php
                if (isset($added4)) {
                    echo "<p style='color: green;'>$added4</p>";
                }
                if (isset($error4)) {
                    echo "<p style='color: red;'>$error4</p>";
                }
                if (isset($required4)) {
                    echo "<p style='color: red;'>$required4</p>";
                }

                ?>
            </form>
        </div>

        <div class="form-container">

            <form name="form5" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h2>CHANGE ROOM</h2>

                <label for="movie_id5" class="form-label">ADD AN ID</label><br>
                <input type="text" id="movie_id5" name="movie_id5" class="form-input"><br><br><br>

                <label for="movie_room" class="form-label">CHANGE ROOM</label><br>
                <input type="text" name="movie_room" id="movie_room" class="form-input"><br><br>

                <input type="submit" name="update_room" value="UPDATE5" class="form-submit">
                <?php
                if (isset($added5)) {
                    echo "<p style='color: green;'>$added5</p>";
                }
                if (isset($error5)) {
                    echo "<p style='color: red;'>$error5</p>";
                }
                if (isset($required5)) {
                    echo "<p style='color: red;'>$required5</p>";
                }

                ?>
            </form>
        </div>


        <div class="form-container">
            <form name="form6" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <h2>CHANGE PRICE</h2>

                <label for="movie_id6" class="form-label">ADD AN ID</label><br>
                <input type="text" id="movie_id6" name="movie_id6" class="form-input"><br><br><br>

                <label for="movie_price" class="form-label">CHANGE PRICE</label><br>
                <input type="text" name="movie_price" id="movie_price" class="form-input"><br><br>

                <input type="submit" name="update_price" value="UPDATE6" class="form-submit">
                <?php
                if (isset($added6)) {
                    echo "<p style='color: green;'>$added6</p>";
                }
                if (isset($error6)) {
                    echo "<p style='color: red;'>$error6</p>";
                }
                if (isset($required6)) {
                    echo "<p style='color: red;'>$required6</p>";
                }

                ?>
            </form>
        </div>

    </div>

</body>

</html>





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