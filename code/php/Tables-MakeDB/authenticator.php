<!-- Initializing the db connection -->
<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "Cinemax";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
