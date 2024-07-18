<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name

require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/navBar.php";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = test_input($_POST["email"]);
        $password = test_input($_POST["password"]);

        // Tables
        $workerTable = "workers";
        $userTable = "users";

        // Fetching user data from both tables based on the provided email
        $sql = "SELECT id, firstname, lastname, email, password FROM $workerTable WHERE email = ? 
            UNION 
            SELECT id, firstname, lastname, email, password FROM $userTable WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // checking if pw is correct based on the hashed already exiting pw
            if (password_verify($password, $row["password"])) {

                // Seting session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["userid"] = $row["id"];
                $_SESSION["username"] = $row["firstname"] . " " . $row["lastname"];
                $_SESSION["workeremail"] = $row["email"]; //worker email session


                // Redirecting based on the mail
                if (stripos($row["email"], "@worker.com") !== false) {

                    $_SESSION["workeremail"] = $row["email"];
                    // Redirecting to worker.php
                    header("Location: ../Worker_file/worker.php");
                } else if (stripos($row["email"], "@admin.com") !== false) {

                    // Redirecting to admin.php
                    header("Location: ../Worker_file/worker.php");
                } else {
                    // Redirecting to userViewMovies.php if user signed in
                    header("Location: userViewMovies.php");
                }
                exit();
            } else {
                $invalid = "Invalid password. Please try again.";
            }
        } else {
            $wrongMail = "User with the provided email not found. Please check your email and try again.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $provide = "Please provide both email and password for login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/navBar.css">


    <title>Login</title>
    <style>
        .outer-container {
            background-color: #f2f2f2;
            padding: 20px;
            margin: 30px auto;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            top: 200px;
        }

        .form-container {
            max-width: 450px;
            margin: auto;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .bttn {
            color: black;
            background-color: #d9d9d9;
            border: 2px solid #c9c9c9;
            border-radius: 8px;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            font-size: 15px;
            margin-top: 10px;
            transition: all 0.2s;
            cursor: pointer;

        }

        .bttn:hover {
            background-color: #cccccc;
            border-color: #bbbbbb;
        }

        .bttn:active {
            background-color: #bdbdbd;
            border-color: #acacac;
        }

        .bttn:focus {
            outline: none;
        }

        label {
            font-size: 18px;
            color: #212121;

        }
    </style>

</head>

<body style="background-color: black;">


    <div class="outer-container ">

        <div class="form-container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-container w3-card-4 w3-light-grey w3-round-large" style="max-width:500px; margin:auto">
                <h3>Sign in<h3>
                        <h5 style="color: black;">Welcome back. Please sign in to your account</h5> <br>

                        <p>
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email">
                        </p>
                        <p>
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">

                        </p>
                        <input type="submit" value="Sign in" class="bttn">

                        <br><br>
                        <p>
                        <h6 style="color:#4d4d4d;float: left;"><b>New here? <a href="register.php" style="color: #3982D8;">
                                    Register</b></a> </h6>
                        </p>
                        <?php
                        // printing the errors inside the form
                        if (isset($invalid)) {
                            echo "<p style='color: red;'>$invalid</p>";
                        }
                        if (isset($wrongMail)) {
                            echo "<p style='color: red;'>$wrongMail</p>";
                        }
                        if (isset($provide)) {
                            echo "<p style='color: red;'>$provide</p>";
                        }
                        ?>
            </form>
        </div>
    </div>


    <div class="container" style="margin-top: 70px;">
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>