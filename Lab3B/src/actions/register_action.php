<?php
session_start();
// ./actions/register_action.php

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
$register_sql = "INSERT INTO user (username, password, logged_in) VALUES(?,?,?)";
$user_check_sql = "SELECT * FROM user WHERE username = ?";
$hashed_password = password_hash($_POST['Password'], PASSWORD_BCRYPT);
$logged_in = 1;

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// TODO: Register a new user
if (($_POST['Password'] !== $_POST['ConfirmPassword'])) {
	header("Location: /views/register.php?nomatch");
	exit;
}

// Check if user exists
// Redirect to /view/register.php?baduser

$check_user = $conn->prepare($user_check_sql);
$check_user->bind_param('s', $_POST['Username']);
$check_user->execute();
$user_checked = $check_user->get_result();
if ($user_checked->num_rows > 0) {
	header("Location: /views/register.php?baduser");
	exit;
}

$add_user = $conn->prepare($register_sql);
$add_user->bind_param('ssi', $_POST['Username'], $hashed_password, $logged_in);
$add_user->execute();
session_regenerate_id();
$_SESSION['username'] = $_POST['Username'];
header("Location: /")
// Create user in database
?>
