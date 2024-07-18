<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name
require "../Tables-MakeDB/makeDBConnection.php";
require "makePayment.php";
require "../../html/navBar.php";

//this page will be displayed to user only if they are logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

?>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "GET") {

        //setting variables for each movie details, we have accesed them by using get method because in previous pages, user clicks on a form and all those information are being send to that page
        $title = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '';
        $showtime = isset($_GET['showtime']) ? htmlspecialchars($_GET['showtime']) : '';
        $room = isset($_GET['room']) ? htmlspecialchars($_GET['room']) : '';
        $seat = isset($_GET['seat']) ? htmlspecialchars($_GET['seat']) : '';

        //making a div to modify them
        echo "<div class='info'>";

        echo "<h2>Your Booking:</h2>"; //big heading title
        echo "<p>Movie: $title</p>";
        echo "<p>Showtime: $showtime</p>";
        echo "<p>Room: $room</p>";
        echo "<p>Seat: $seat</p>";
        echo "</div>";
    }

    ?>
    <!-- Html 5 -->
    <!DOCTYPE html>
    <html>

    <head>
        <title>Payment Page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/navBar.css">

        <style>
            .checkoutBtn {
                width: 120px;
                background-color: grey;
                color: white;
                margin: auto;
                display: flex;
                justify-content: center;
                align-items: center;

            }

            /*using !important to override bootstrap default styling*/
            .checkoutBtn:hover {
                background-color: #555 !important;
            }

            .checkoutBtn:active {
                background-color: #545254 !important;
            }

            .form-control {
                width: 300px;
            }



            .paymentbox {
                margin-top: 30px;
                background-color: #efefef;
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                /*giving a shadow to the box*/
            }


            .split {
                display: inline-block;
                margin-right: 50px;
                padding-left: 15px;
            }

            .split label {
                display: block;
            }

            .info {
                color: white;
                margin-left: 150px;
            }

            .w-container {
                padding-bottom: 20px;
                background-color: #efefef;
                padding-top: 10px;
                border-radius: 10px;
            }

            /* > means direct child. So here we access the .split class that comes right after(bellow) the .st class*/
            .st>.split {
                margin-right: 55px;
            }
        </style>
    </head>

    <body style="background-color:black">

        <div class="form-container" style="margin-top:10px;">
            <div class="w-container" style="max-width:750px;; margin:auto">


                <h3 style="text-align: center; margin-bottom:40px"><b>Place your personal information to proceed for the purchase</b></h3>
                <!-- Payment is an important process, so we display to the user imidiatelly -->
                <p>Please fill all fields</p>
                <form action="" method="GET">

                    <!-- Hidden inputs used so when user submits form, for all those extra hidden values to be submited too -->
                    <!-- They are not viisble to user -->
                    <input type="hidden" name="title" value="<?php echo $title; ?>">
                    <input type="hidden" name="showtime" value="<?php echo $showtime; ?>">
                    <input type="hidden" name="room" value="<?php echo $room; ?>">
                    <input type="hidden" name="seat" value="<?php echo $seat; ?>">

                    <div class="st">
                        <div class="split">
                            <p>
                                <label>First Name</label>
                                <input type="text" class="form-control" id="FirstName" name="FirstName">
                            </p>
                        </div>
                        <div class="split">
                            <p>
                                <label>Last Name</label>
                                <input type="text" class="form-control" id="LastName" name="LastName">
                            </p>
                        </div>
                        <div class="split">
                            <p>
                                <label>Email</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" name="Email">
                            </p>
                        </div>
                        <div class="split">
                            <p>
                                <label>House Address</label>
                                <input type="text" class="form-control" id="HouseAddress" name="HouseAddress">
                            </p>
                        </div>
                        <div class="split">
                            <p>
                                <label>Zip Code</label>
                                <input type="text" class="form-control" id="ZipCode" name="ZipCode">
                            </p>
                        </div>
                        <div class="split">
                            <p>
                                <label>Phone Number</label>
                                <input type="text" class="form-control" id="PhoneNum" name="PhoneNum">
                            </p>
                        </div>

                    </div>
                    <div class="paymentbox ">
                        <div class="w-container paybox">

                            <h3 style="text-align:center; margin-bottom:40px;"><b>Add your Credit or Debit card information</b></h3>

                            <div class="split">
                                <p>
                                    <label>Card Number</label>
                                    <input type="text" class="form-control" placeholder="1234 5678 9012 3456" id="CardNum" name="CardNum">
                                </p>
                            </div>
                            <div class="split">
                                <p>
                                    <label>Expire Date</label>
                                    <input type="text" class="form-control" placeholder="MM/YY" id="ExpireDate" name="ExpireDate">
                                </p>
                            </div>
                            <div class="split">
                                <p>
                                    <label>Cardholder Name</label>
                                    <input type="text" class="form-control" id="NameOnCard" name="NameOnCard">
                                </p>
                            </div>
                            <div class="split">
                                <p>
                                    <label>Security Code (CVV/CVC)</label>
                                    <input type="text" class="form-control" placeholder="123" id="SecCode" name="SecCode">
                                </p>
                            </div>
                            <br><br>

                            <button type="submit" name="submit" class="btn btn-outline-secondary checkoutBtn" style="border-radius: 10px;">
                                Buy product
                            </button>


                            <br>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>


        <section style="margin-top: 70px;">


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
        </section>

    </body>
<?php

    ///if someone tries to access the page throught URL and is not registred, he will be redirected to register page
    // this is used for safety

} else {

    echo "<p>Welcome, guest! <a href='signIn.php'>Log in</a> or <a href='register.php'>register</a> to enjoy more features.</p>";
}
