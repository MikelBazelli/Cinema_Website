<?php
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// try catch is used to modify the error of duplicate entries. You can't book a ticket for same seat in same room same time as someone else, each seat is unique for each movie
try {



    $title = test_input($_GET['title']); // using title to find its movie
    // Query to fetch movie ID so that we can store it and match it with user
    $stmt = $conn->prepare("SELECT movie_id FROM movies WHERE title = ?");
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) { //checking output, if 0 rows are affected which means no result, then error will display
        $row = $result->fetch_assoc();
        $movieId = $row['movie_id'];
    } else {
        echo "error with movie_id";
    }
    $stmt->close();



    $showtime = test_input($_GET['showtime']);
    // Query to fetch showtime ID from the movie's time
    $stmt = $conn->prepare("SELECT showtime_id FROM showtimes WHERE showtime = ?");
    $stmt->bind_param("s", $showtime);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $showtimeId = $row['showtime_id'];
    } else {
        echo "error with showtime_id";
    }
    $stmt->close();


    $room = test_input($_GET['room']);
    // Query to fetch room ID from room number
    $stmt = $conn->prepare("SELECT rooms_id FROM rooms WHERE RoomName = ?");
    $stmt->bind_param("s", $room);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $roomId = $row['rooms_id'];
    } else {
        echo "error with rooms_id";
    }
    $stmt->close();

    $seat = test_input($_GET['seat']);
    // Query to fetch seat ID from seat number
    $stmt = $conn->prepare("SELECT seat_id FROM seats WHERE seat_number = ?");
    $stmt->bind_param("s", $seat);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $seatId = $row['seat_id'];
    } else {
    }
    $stmt->close();




    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['submit'])) {

        // SETTING ALL VARIABLES, we found them from code above where we fetch info
        //Checking if each one set(clicked) or not, if not we have just gap''
        //If form is set then it sanitizes the input, if empty they get assigned an empty variable
        $title = isset($_GET['title']) ? test_input($_GET['title']) : '';
        $showtime = isset($_GET['showtime']) ? test_input($_GET['showtime']) : '';
        $room = isset($_GET['room']) ? test_input($_GET['room']) : '';
        $seat = isset($_GET['seat']) ? test_input($_GET['seat']) : '';

        //From the payment form
        $firstName = isset($_GET['FirstName']) ? test_input($_GET['FirstName']) : '';
        $lastName = isset($_GET['LastName']) ? test_input($_GET['LastName']) : '';
        $email = isset($_GET['Email']) ? test_input($_GET['Email']) : '';
        $houseAddress = isset($_GET['HouseAddress']) ? test_input($_GET['HouseAddress']) : '';
        $zipCode = isset($_GET['ZipCode']) ? test_input($_GET['ZipCode']) : '';
        $phoneNum = isset($_GET['PhoneNum']) ? test_input($_GET['PhoneNum']) : '';
        $cardNum = isset($_GET['CardNum']) ? test_input($_GET['CardNum']) : '';
        $expireDate = isset($_GET['ExpireDate']) ? test_input($_GET['ExpireDate']) : '';
        $nameOnCard = isset($_GET['NameOnCard']) ? test_input($_GET['NameOnCard']) : '';
        $SecCode = isset($_GET['SecCode']) ? test_input($_GET['SecCode']) : '';

        //    || logail OR operator
        if (
            empty($firstName) ||
            empty($lastName) ||
            empty($email) ||
            empty($houseAddress) ||
            empty($zipCode) ||
            empty($phoneNum) ||
            empty($cardNum) ||
            empty($expireDate) ||
            empty($nameOnCard) ||
            empty($SecCode)
        ) {

            $fillAll = "Fill In All Fields"; //putting error inside a variable
        } else {
            if (
                !empty($firstName) || // not empty
                !empty($lastName) ||
                !empty($email) ||
                !empty($houseAddress) ||
                !empty($zipCode) ||
                !empty($phoneNum) ||
                !empty($cardNum) ||
                !empty($expireDate) ||
                !empty($nameOnCard) ||
                !empty($SecCode)

            ) {


                $stmt = $conn->prepare("SELECT seat_id FROM seats WHERE seat_number = ? AND room_id = ?"); // selecting the seat from a particular room where used has chosen

                $stmt->bind_param("si", $seat, $roomId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 0) {

                    $status = 'available'; // Default value, seat is set to available when inserted automaticaly
                    $stmt = $conn->prepare("INSERT INTO seats (room_id, seat_number, status) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $roomId, $seat, $status);
                    $stmt->execute();
                    $seatId = $stmt->insert_id; //checking if seat is already booked, if not a new seat will be inserted based on that room_id
                } else {
                    // Using existing seat_id that was seat previously
                    $row = $result->fetch_assoc();
                    $seatId = $row['seat_id'];
                }
                $stmt->close();

                $userId = $_SESSION["userid"]; //adding the user id into a variable in order to manipulate the info
                $stmt = $conn->prepare("INSERT INTO bookings (user_id, movie_id, rooms_id, seat_id, showtime_id, booking_time, status) VALUES (?, ?, ?, ?, ?, NOW(), TRUE)"); //NOW() is for current date and time
                $stmt->bind_param("iiiii", $userId, $movieId, $roomId, $seatId, $showtimeId);

                if ($stmt->execute()) {
                    $booking_id = $stmt->insert_id;

                    // Inserting into tickets table, all those ID's are foreign keys used to track each movie and in what seat movie was booked,its room etc
                    $stmt = $conn->prepare("INSERT INTO tickets (booking_id, customer_name, customer_lname, customer_email, customer_phone, customer_address) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssss", $booking_id, $firstName, $lastName, $email, $phoneNum, $houseAddress);

                    if ($stmt->execute()) {
                        header("Location: viewTicket.php"); //if insertion occurs, then user will be redirected there
                        exit;
                    } else {
                        echo "Error: " . $stmt->error;
                    }
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
                $conn->close();
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    // Handling the duplicate entry error beacause we used UNIQUE
    if ($e->getCode() == 1062) {
        echo "<script>alert('Error: Seat is already booked. Please choose another seat.');</script>"; //showing user seat is booked, he can then redirect and pick another one!
    } else {
        echo "Error: " . $e->getMessage();
    }
}
