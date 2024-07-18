<!-- THIS PAGE HAS THE NAVIGATION BAR, WHRERE IN EVERY PAGE OF OUR PAGE WE REQUIRE IT, SO USER CAN VIEW IT -->


<!DOCTYPE html>
<html lang="en">

<!-- ALL LINKS ENTER BETWEEN HEAD TAGS -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineMax</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.png"><!--Favicon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/navBar.css">
</head>


<body>

    <!-- MAKING THE NAVIGATION BAR -->
    <nav class="navbar navbar-expand-md">
        <div class="container-fluid cf1">
            <div class="logo">
                <a class="navbar-brand" href=""> <a href="../User_file/userViewMovies.php">
                        <img src="../../images/logocine.png" alt="logo" width="150px;" style="display: flex;">
                    </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="nav-container">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="home">
                        <ul class="nav-list">
                            <a href="./userViewMovies.php" class="link left">
                                <li>HOME</li>
                            </a>
                        </ul>
                    </div>

                    <div class="about">
                        <ul class="nav-list">
                            <a href="./about.php" class="link leftT">
                                <li>ABOUT</li>
                            </a>
                        </ul>
                    </div>

                    <!-- SEARCH BAR -->
                    <div class="wrapper">
                        <form action="search.php" method="get">
                            <input type="text" name="movie_name" placeholder="Search for a movie...">
                            <button class="searchbtn" type="submit"><i class="fa fa-search"></i></button>

                        </form>
                    </div>

                    <div class="contact">
                        <ul class="nav-list">
                            <a href="./contact.php" class="link rightT">
                                <li>CONTACT</li>
                            </a>
                        </ul>
                    </div>

                    <!--
                         Here we have a session where we hold user name and last name and it's id,
                    If user is logged in, on top of nav bar he will view link called profile in order to enter and view ticket or log out
                    If user isn't logged i, he will view link to register and from there he can either log in or create an account
                     -->
                    <div class="register">
                        <ul class="nav-list">
                            <?php
                            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                                echo "<li class='right'><a href='profile.php' class='link'>PROFILE</a></li>";
                            } else {
                                echo "<li class='right'><a href='register.php' class='link'>REGISTER</a></li>";
                            }
                            ?>
                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </nav>
    <?php
    /*
    Checking if the search form is submitted and the movie_name field is not empty, 
    if it's valid, user gets redirected to page called details through the search page. 
    Search page has code that checks if movie exists in database or not, if yes, 
    user will be redirected to details page with movie he/she searched 
    */

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

</body>

</html>