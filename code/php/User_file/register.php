<?php
session_start(); // Starting the session at the beginning of the script, it holds user id, and and full name
require "../../html/navBar.php";
require "../Tables-MakeDB/makeDBConnection.php";

function test_input($data)
{
	// Triming any extra whitespace from the data. trim(  JOHN  )  -> (JOHN)
	$data = trim($data);

	// Remove any backslashes (\) from the data. This is commonly used to striplashes(JO/HN) -> (JOHN)
	$data = stripslashes($data);

	/*
	 Convert special characters to HTML entities. This helps prevent cross site scripting attacks that can be harful to the database
	 <script>alert("Hacked");</script> ->  &lt;script&gt;alert("Hacked");&lt;/script&gt;
	*/
	$data = htmlspecialchars($data);

	// Return the sanitized data
	return $data;
}


$table = 'users';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (!empty($_POST["name"]) && !empty($_POST["lname"]) && !empty($_POST["email"]) && !empty($_POST["pw"]) && !empty($_POST["cpw"])) {

		$name = test_input($_POST["name"]);
		$lname = test_input($_POST["lname"]);
		$email = test_input($_POST["email"]);
		$pw = test_input($_POST["pw"]);
		$cpw = test_input($_POST["cpw"]);

		// Check ing if email ends with @worker.com
		if (stripos($email, "@worker.com") !== false) {
			$error_message = "Registration with your email is not allowed."; //user can't make account if his email has @worker.
		} else {
			$hashedPassword = password_hash($pw, PASSWORD_DEFAULT); // Hashing the password

			try {
				//try catch to see if user is alredy registred because email is a unique value
				$sql = "INSERT INTO $table (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("ssss", $name, $lname, $email, $hashedPassword);
				$stmt->execute();

				header("Location: signIn.php");
			} catch (mysqli_sql_exception $e) {
				if ($e->getCode() == 1062) { //1062 is duplicate entry error
					$exists = "User with this email already exists.";
				} else {
					echo "Error: " . $e->getMessage();
				}
			}
			$stmt->close();
			$conn->close();
		}
	} else {
		$provide = "Please provide all required information for insertion.";
	}
}
?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link rel="stylesheet" href="../../css/navBar.css">
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
			padding-bottom: 20px;
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

<body style="background-color: black;">

	<div class="outer-container ">
		<div class="form-container" style=" margin-top:70px; ">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-container w3-card-4 w3-light-grey w3-round-large" style="max-width:500px; margin:auto">
				<h3 style="text-align: center;"><b>Register your account</b>
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

						</p>
						<input type="submit" value="submit" class="bttn">

						<p>
						<h6 style="color:#4d4d4d;"><b>Already have an accound? <a href="signIn.php" style="color: #3982D8;">Sing in</b></a> </h6>
						</p>
						<br>


						<?php
						//printing the errors under the form because we stored it into these variables

						if (isset($provide)) {
							echo "<p style='color: red;'>$provide</p>";
						}
						if (isset($exists)) {
							echo "<p style='color: red;'>$exists</p>";
						}
						if (isset($error_message)) {
							echo "<p style='color: red;'>$error_message</p>";
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


</body>

</html>


<script>
	// Java Script function to validate password match
	function validatePassword() {
		var password = document.getElementById("pw").value; //takes element with these id's
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

	function checkPasswordStrength(password) { //checking if pw is strong enough
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
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>