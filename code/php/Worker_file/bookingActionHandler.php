<?php session_start(); // Starting the session at the beginning of the script

require "../Tables-MakeDB/makeDBConnection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <style>
        * {
            font-size: 35px;
            text-align: center;
        }
    </style>
</head>

<body>

    </br>
    <b><strong><a href="request.php">VIEW REQUESTS</a></strong></b>
    </br>

</body>

</html>

<?php
// from the request file
if (isset($_POST['booking_id']) && isset($_POST['action'])) {

    $booking_id = $conn->real_escape_string($_POST['booking_id']);
    $action = $_POST['action'];

    if ($action == 'Accept') {
        // Updating booking status and seat status making the ticket to be booked!
        $updateBooking = "UPDATE bookings SET status = 0 WHERE booking_id = $booking_id";
        $updateSeat = "UPDATE seats JOIN bookings ON seats.seat_id = bookings.seat_id SET seats.status = 'booked' WHERE bookings.booking_id = $booking_id";

        if ($conn->query($updateBooking) === TRUE && $conn->query($updateSeat) === TRUE) {
            echo "Booking accepted successfully.";
        } else {
            echo "Error in accepting booking: " . $conn->error;
        }
    } elseif ($action == 'Decline') {
        //if decline we identify the booking request and then delete it
        $getSeatId = "SELECT seat_id FROM bookings WHERE booking_id = $booking_id";
        $seatResult = $conn->query($getSeatId);
        if ($seatResult->num_rows > 0) {
            $seatRow = $seatResult->fetch_assoc();
            $seatId = $seatRow['seat_id'];

            //we have many sql operations happening same time, so we use transaction and error handing with try catch
            $conn->begin_transaction();

            try {
                // Deleting ticket
                $deleteTicket = "DELETE FROM tickets WHERE booking_id = $booking_id";
                if (!$conn->query($deleteTicket)) {
                    throw new Exception($conn->error);
                }

                // Deleting booking
                $deleteBooking = "DELETE FROM bookings WHERE booking_id = $booking_id";
                if (!$conn->query($deleteBooking)) {
                    throw new Exception($conn->error);
                }
                // Deleting seat
                $deleteSeat = "DELETE FROM seats WHERE seat_id = $seatId";
                if (!$conn->query($deleteSeat)) {
                    throw new Exception($conn->error);
                }

                $conn->commit();
                echo "Booking DELETED, ticket and seat deleted successfully.";
            } catch (Exception $e) {
                $conn->rollback();
                echo "ERROR declining booking: " . $e->getMessage();
            }
        } else {
            echo "ERROR fetching seat ID.";
        }
    }
}


?>