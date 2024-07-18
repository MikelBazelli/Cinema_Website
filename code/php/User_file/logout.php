<?php
// Initializing the session
session_start();

// Unseting all the session variables
$_SESSION = array();

// Destroying the session.
session_destroy();

// Redirecting to user page
header("Location: userViewMovies.php");
exit;
