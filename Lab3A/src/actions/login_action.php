<?php
session_start();
// ./actions/login_action.php

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);
$password_hash_sql = "SELECT * FROM user WHERE username = ?";
$login_sql = "UPDATE user SET logged_in = 1 WHERE username = ?";

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$get_password = $conn->prepare($password_hash_sql);
$get_password->bind_param('s', $_POST['Username']);
$get_password->execute();
$password_hash_rows = $get_password->get_result();
if ($password_hash_rows->num_rows == 0) {
	header("Location: /views/login.php?failed");
	exit;
}
$row = $password_hash_rows->fetch_assoc();
$password_hash = $row['password'];

if (password_verify($_POST['Password'], $password_hash)) {
	$log_in = $conn->prepare($login_sql);
	$log_in->bind_param('s', $_POST['Username']);
	$log_in->execute();
	session_regenerate_id();
	$_SESSION['username'] = $_POST['Username'];
	header("Location: /");
	exit;
}
header("Location: /views/login.php?failed");
exit;


// TODO: Log the user in

?>