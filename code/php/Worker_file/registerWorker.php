<?php
// REGISTERING A WORKER INTO DATABASE
session_start();
if (isset($_SESSION["workeremail"]) && strpos($_SESSION["workeremail"], '@admin.com') !== false) {
    require "../Tables-MakeDB/makeDBConnection.php";
    require "../../html/worker1.php";


    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $table = 'workers';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!empty($_POST["name"]) && !empty($_POST["lname"]) && !empty($_POST["email"]) && !empty($_POST["pw"]) && !empty($_POST["cpw"])) {

            $name = test_input($_POST["name"]);
            $lname = test_input($_POST["lname"]);
            $email = test_input($_POST["email"]);
            $pw = test_input($_POST["pw"]);
            $cpw = test_input($_POST["cpw"]);

            $hashedPassword = password_hash($pw, PASSWORD_DEFAULT); // Hashing the password

            try {
                $sql = "INSERT INTO $table (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $name, $lname, $email, $hashedPassword);
                $stmt->execute();

                $added = "INSERTED SUCCESSFULLY";
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $exists = "User with this email already exists.";
                } else {
                    echo "Error: " . $e->getMessage();
                }
            }
            $stmt->close();
            $conn->close();
        } else {
            $provide = "Please provide all required information for insertion.";
        }
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../../css/woker1.css">
        <style>
            .outer-container {
                background-color: #f2f2f2;
                margin: 30px auto;
                max-width: 600px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .form-container {
                max-width: 550px;
                margin: auto;
                padding: 20px;
                border-radius: 10px;
            }

            .bttn {
                color: black;
                background-color: #d9d9d9;
                border: 2px solid #c9c9c9;
                border-radius: 8px;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                font-size: 16px;
                margin: 10px 2px;
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

        <title>Register</title>
    </head>

    <body>

        <div class="outer-container ">
            <div class="form-container" style=" margin-top:70px; ">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-container w3-card-4 w3-light-grey w3-round-large" style="max-width:500px; margin:auto">
                    <h3 style="text-align: center;"><b>Register Your Workers</b>
                        <h3>
                            <br>
                            <p>
                                <label for="InputName" class="w3-large"> Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name">
                            </p>
                            <p>
                                <label for="InputLastName" class="w3-large">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter your last name">

                            </p>
                            <p>
                                <label for="InputEmail2" class="w3-large">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder=" Enter your Email">

                            </p>
                            <p>
                                <label for="InputPassword2" class="w3-large">Password</label>
                                <input type="password" class="form-control" id="pw" placeholder="Create a password" name="pw" onkeyup="checkPasswordStrength(this.value)">
                                <span id="passwordStrength" style="font-size: small;"></span>

                            </p>
                            <p><label for="InputConfirm" class="w3-large">Confirm your password</label>
                                <input type="password" class="form-control" id="cpw" name="cpw" placeholder="Enter your password again" oninput="validatePassword()">
                            <h6>
                                <div id="passwordError" style="color: red;"></div>
                            </h6>
                            </p>
                            <input type="submit" value="submit" class="bttn">

                            <?php
                            if (isset($provide)) {
                                echo "<p style='color: red;'>$provide</p>";
                            }
                            if (isset($exists)) {
                                echo "<p style='color: red;'>$exists</p>";
                            }
                            if (isset($error_message)) {
                                echo "<p style='color: red;'>$error_message</p>";
                            }
                            if (isset($added)) {
                                echo "<p style='color: green;'>$added</p>";
                            }
                            ?>
                </form>


                <script>
                    // JavaScript function to validate password match
                    function validatePassword() {
                        var password = document.getElementById("pw").value;
                        var confirmPassword = document.getElementById("cpw").value;
                        var errorDiv = document.getElementById("passwordError");

                        if (password !== confirmPassword) {
                            errorDiv.innerHTML = "Passwords do not match!";
                        } else {
                            errorDiv.innerHTML = "";
                        }
                    }

                    function checkPasswordStrength(password) {
                        var passwordStrengthElement = document.getElementById("passwordStrength");

                        if (password.length < 5) {
                            passwordStrengthElement.innerText = "weak";
                            passwordStrengthElement.style.color = "red";
                        } else if (password.length === 5) {
                            passwordStrengthElement.innerText = "medium";
                            passwordStrengthElement.style.color = "orange";
                        } else {
                            passwordStrengthElement.innerText = "strong";
                            passwordStrengthElement.style.color = "green";
                        }
                    }

                    // js function to validate password match
                    function validatePassword() {
                        var password = document.getElementById("pw").value;
                        var confirmPassword = document.getElementById("cpw").value;
                        var errorDiv = document.getElementById("passwordError");

                        if (password !== confirmPassword) {
                            errorDiv.innerHTML = "Passwords do not match!";
                            return false;
                        } else {
                            errorDiv.innerHTML = "";
                            return true;
                        }
                    }
                </script>

    </body>

    </html>
<?php } else {
    // Redirect if not logged in
    echo "<script>alert('Only admin can view this page!'); window.location.href = 'worker.php';</script>";
} ?>