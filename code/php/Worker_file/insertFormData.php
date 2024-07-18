<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        h3 {
            background-color: darkgray;
            text-align: center;
        }
    </style>
</head>

<body>

</body>

</html>

<?php
require "../Tables-MakeDB/makeDBConnection.php";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Deleting workers
    if (isset($_POST["delete1"]) && !empty($_POST["worker_id"])) {
        $workerId = test_input($_POST["worker_id"]);

        $sqlDeletew = "DELETE FROM workers WHERE id = ?";
        $stmt = $conn->prepare($sqlDeletew);
        $stmt->bind_param("i", $workerId);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<h3>Worker deleted successfully</h3>";
            } else {
                echo "<h3>Invalid worker ID</h3>";
            }
        } else {
            echo "<h3>Error deleting Worker: </h3>" . $stmt->error;
        }
        $stmt->close();
    }

    // Deleting users
    if (isset($_POST["delete2"]) && !empty($_POST["id"])) {
        $id = test_input($_POST["id"]);

        $sqlDelete = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sqlDelete);
        $stmt->bind_param("i", $id);

        try {
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                echo "<h3>User deleted successfully</h3>";
            } else {
                echo "<h3>Invalid user ID</h3>";
            }

            //error of not being able to delete a foreign key contrain. Because user has booked a ticket
        } catch (mysqli_sql_exception $ex) {
            echo "<h3>Error: Cannot delete user with ID $id. Please delete the user's bookings first. <a href='request.php'>CLICK to view bookings</a> </h3>";
        } catch (Exception $e) {
            echo "<h3>Error deleting user: " . $e->getMessage() . "</h3>";
        }

        $stmt->close();
    }
}

$conn->close();
