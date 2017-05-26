<?php

// Call session_start function to access global variables
session_start();

// Get database functions
require_once ('../Database.php');

// New instance of Database class
$db = new Database();

// Assign SQL statement to variable selecting articles tablerow with same id as id in url
$post = $db->run('SELECT * FROM articles WHERE id = ?', [$_GET['id']])->fetch();

// Only allow if the user who wants to delete is author of article, or if user is admin, return error if not
if (!($_SESSION['admin'] == 1 || $_SESSION['id'] == $post['user_id']))
	die(json_encode(['error' => 'Not logged in']));

// Run SQL statement which deletes article from articles table where id is equal to id in url
$db->run('DELETE FROM articles WHERE id = ?', [$_GET['id']]);

// If there is redirect key, redirect to value of get key
if(isset($_GET['redirect']))
	die(header('location: ../../'.$_GET['redirect']));
	
// Redirect to posts.php. PS! The above statement is used to overwrite the below redirect function, see admin.php delete article href for example
header('location: ../../posts.php');