<?php

// Call session_start function to access global variables
session_start();

// Get database functions
require_once ('../Database.php');

// Only allow if session exists, return error if not
if (!isset($_SESSION['username']))
	die(json_encode(['error' => 'Not logged in']));

// New instance of Database class
$db = new Database();

// Failsafe, if voted, assign 1 to vote, -1 if not
$vote = $_POST['vote'] == 1 ? 1 : -1;
// Which article vote is cast to
$id = $_POST['id'];

// SQL statement which insert into ratings table. updates it if user votes again on same article
$db->run('INSERT INTO ratings 
			(user_id, article_id, vote) 
			VALUES(?, ?, ?)
			
			ON DUPLICATE KEY
				UPDATE vote = 
					CASE WHEN vote = ? THEN
					2
					ELSE
					?
					END;
					
					DELETE FROM ratings WHERE vote = 2;', 
	[$_SESSION['id'], $id, $vote, $vote, $vote]);

// Assign vote data to variable
$article = $db->run('SELECT
	 (SUM(r.vote = 1) / COUNT(r.vote) * 100) AS percent,
	 SUM(r.vote = 1) As vote
	 
	 FROM articles AS a
	 
	 LEFT JOIN users AS u ON u.id = a.user_id
	 LEFT JOIN ratings AS r ON r.article_id = a.id
	 
	 WHERE a.id = ?
	 
	 GROUP BY a.id', [$id])->fetch();

// Return vote data as json, important due to ajax (scripts/main.js)
echo json_encode($article);