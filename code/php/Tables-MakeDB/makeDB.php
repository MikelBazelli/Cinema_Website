<?php
require "makeConnection.php";

$sql = "CREATE DATABASE IF NOT EXISTS Cinemax";
if ($conn->query($sql) === TRUE) {
	echo "Database created successfully<br>";
} else {
	echo "Error creating database: " . $conn->error;
}
$conn->close();
