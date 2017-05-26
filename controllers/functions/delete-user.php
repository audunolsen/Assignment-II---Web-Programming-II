<?php

// Call session_start function to access global variables
session_start();

// Get database functions
require_once ('../Database.php');

// New instance of Database class
$db = new Database();

// Only allow if user is admin, return error if not
if (!($_SESSION['admin'] == 1))
	die(json_encode(['error' => 'Must be admin']));

// Run SQL statement which deletes user from users table where id is equal to id in url
$db->run('DELETE FROM users WHERE id = ?', [$_GET['id']]);
	
// Redirect to admin.php
header('location: ../../admin.php');