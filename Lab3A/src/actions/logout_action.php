<?php
session_start();
// ./actions/logout_action.php

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
$logout_sql = "UPDATE user SET logged_in = 0 WHERE username = ?";

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$log_out = $conn->prepare($logout_sql);
$log_out->bind_param('s', $_SESSION['username']);
$log_out->execute();
session_regenerate_id();
$_SESSION['username'] = "";
header("Location: /views/login.php");

// TODO: Log the user out

?>
