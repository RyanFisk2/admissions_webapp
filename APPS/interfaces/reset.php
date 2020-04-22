<?php
	require_once('../includes/connectvars.php');	
	require_once('../includes/utils.php');

	// from https://w3programmings.com/how-to-execute-sql-file-directly-in-php/
	$host = DB_HOST;
	$name = DB_NAME;
	$db = new PDO("mysql:host=$host;dbname=$name", DB_USER, DB_PASSWORD);
	$query = file_get_contents("../../team100_apps_tables.sql");

	$stmt = $db->prepare($query);
	if ($stmt->execute())
	     echo "Success resetting databas";
	else 
	     echo "Failed resetting database";
?>