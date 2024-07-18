<?php

require "../Tables-MakeDB/makeDBConnection.php";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movieTitle = test_input($_POST["movie_title"]);
    $description = test_input($_POST["description"]);
    $movie_price = test_input($_POST["movie_price"]);
    $room_name = test_input($_POST["room_name"]);

    // Checking if file is uploaded successfully
    if (!empty($_FILES['fileToUpload']['name']) && $_FILES['fileToUpload']['error'] === 0) {
        // setting the directory where we want to save the uploaded file
        $uploadDir = '../../images/';

        // accesing the filename and moving the file to the mentioned directory
        $imgPath = $uploadDir . basename($_FILES['fileToUpload']['name']);

        // testing if the uploaded file is an image
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo "Error: File is not an image.";
            $uploadOk = 0;
        }

        // Checking if file already exists
        if (file_exists($imgPath)) {
            echo "Error: Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Checking file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo "Error: Sorry, your file is too large.";
            $uploadOk = 0;
        }

        //  Only specific image file types can be uploaded
        $allowedImageTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedImageTypes)) {
            echo "Error: Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // If $uploadOk is still 1, move the file to the specified directory
        if ($uploadOk == 1) {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imgPath);

            // making the database insertion
            if (!empty($movieTitle) && !empty($description) && !empty($_POST['showtimes']) && !empty($_POST["room_name"])  && !empty($_POST["movie_price"])) {

                $sqlInsertMovie = "INSERT INTO movies (title, description, image_path, movie_price) VALUES ('$movieTitle', '$description', '$imgPath','$movie_price')";

                if ($conn->query($sqlInsertMovie) === TRUE) {
                    $movieID = $conn->insert_id;

                    $sqlInsertRooms = "INSERT INTO rooms (movie_id,Roomname) VALUES ($movieID,'$room_name')";

                    if ($conn->query($sqlInsertRooms) === TRUE) {
                        $room_id = $conn->insert_id;
                    }


                    //if multiple showtimes, we use explode to differeciate each
                    $showtimes = explode(',', $_POST["showtimes"]);
                    foreach ($showtimes as $time) {
                        $sqlInsertShowtime = "INSERT INTO showtimes (movie_id, room_id ,showtime ) VALUES ($movieID, $room_id,'$time')";
                        $conn->query($sqlInsertShowtime);
                    }


                    echo "Data inserted successfully";
                } else {
                    echo "Error inserting data: " . $conn->error;
                }
            } else {
                echo "All form fields are required. Please fill in all the fields.";
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Error uploading file or file not selected.";
    }
}
