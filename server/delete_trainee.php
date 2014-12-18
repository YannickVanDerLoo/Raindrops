<?php

$php_config = 'config.php';

if (!file_exists($php_config)) {
	header("Location: setup.php");
}

include_once 'config.php';
include_once 'includes/functions.php';

$mysqli = new mysqli($db_host, $db_username, $db_password, $db_database);
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

sec_session_start();
 
if (!login_check($mysqli)) {
	header("Location: login");
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if (isset($_GET['id'])) {
	$id_user = $_GET['id'];
	$id_user = mysqli_real_escape_string($mysqli, $id_user);
	
	$children = findTrainees($mysqli, $user_id);
	// Security check: Is user allowed to access this trainee's data?
	if (!in_array($id_user, $children)) {
		printf("Invalid permissions.\n");
		exit();
	}

	deleteAccount($mysqli, $id_user);
}

header("Location: index.php");

?>