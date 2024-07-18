<?php session_start(); // Starting the session at the beginning of the script

require "../Tables-MakeDB/makeDBConnection.php";
require "../../html/worker1.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../../css/woker1.css">
	<title>Delete</title>
	<style>
		.delete {

			width: 100%;
			margin: auto;
		}

		.inner {
			margin: auto;
			text-align: center;
			padding: 20px;
		}

		.deletebttn {
			background-color: #f44336;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		.deletebttn:hover {
			background-color: #d32f2f;
		}


		.movie-card {
			width: 78%;

		}

		#movie_id1 {
			padding: 5px;
			font-size: 14px;
		}

		label {
			font-size: 20px;
		}


		.table-container {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 20px;
			padding: 10px;
			margin-top: 17px;
		}

		@media (max-width: 1310px) {
			.table-container {
				grid-template-columns: repeat(3, 1fr);
			}

			.movie-card {
				width: 370px;
			}

			.movie-card img {
				width: 180px;
				height: 270px;
			}

		}

		@media (max-width: 1195px) {
			.table-container {
				grid-template-columns: repeat(3, 1fr);
			}

			.movie-card {
				width: 330px;
			}

			.movie-card img {
				width: 160px;
				height: 250px;
			}

		}

		@media (max-width: 1100px) {
			.table-container {
				grid-template-columns: 1fr 1fr;
			}

			.movie-card {
				width: 400px;
			}

			.movie-card img {
				width: 170px;
				height: 250px;
			}
		}



		@media (max-width: 900px) {
			.table-container {
				grid-template-columns: 1fr;
			}

			.movie-card {
				width: 500px;
			}

			.movie-card img {
				width: 180px;
				height: 250px;
			}

		}

		@media (max-width: 600px) {
			.table-container {
				grid-template-columns: 1fr;
			}

			.movie-card {
				width: 350px;
			}


			.movie-card img {
				width: 180px;
				height: 250px;
			}

		}
	</style>
</head>

<body>

	<!-- workers uses the id of each movie and delets it -->
	<div class="delete">
		<div class="inner">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<label for="movie_id1">Movie ID:</label>
				<input type="text" id="movie_id1" name="movie_id1">
				<input type="submit" value="Delete" class="deletebttn">
			</form>
		</div>
	</div>
</body>

</html>


<?php
// sanitizing the input
function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if (!empty($_POST["movie_id1"])) {
	$id = test_input($_POST["movie_id1"]);
}

if (isset($id)) {
	$id = mysqli_real_escape_string($conn, $id);

	$tables = ['showtimes', 'rooms', 'movies']; // array of all tables because many statements to delete

	foreach ($tables as $table) {
		// Using prepared statements for security
		$stmt = $conn->prepare("DELETE FROM $table WHERE movie_id = ?");
		$stmt->bind_param("i", $id);

		try {
			if (!$stmt->execute()) {
				throw new Exception($stmt->error);
			}
			echo "Record deleted successfully from $table<br>";
		} catch (mysqli_sql_exception $ex) {
			// Checking if the error is due to a foreign key constraint
			if ($ex->getCode() == 1451) {
				echo "Error: You must delete the user's bookings before deleting the movie<br>";
				break;
			} else {
				echo "Error deleting record: " . $ex->getMessage() . "<br>";
			}
		} catch (Exception $e) {
			echo "Error deleting record: " . $e->getMessage() . "<br>";
		}
		$stmt->close();
	}
}
?>


<!-- worker can view all movies database -->
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
                movies.image_path
            FROM movies ";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				echo "<div class='movie-card'>";

				//using from the loop the @row to iterate over all elements each by each
				echo "<img src='" . $row["image_path"] . "' alt='Movie Image'>";

				echo "<div class='movie-info'><strong>Movie ID:</strong> " . $row["movie_id"];
				echo "</div>";

				echo "<div class='movie-info'><strong>Title:</strong> " . $row["title"];
				echo "</div>";


				echo "</div>";
			}
		} else {
			echo "<p>No movies found.</p>";
		}
		?>
	</div>




</table>