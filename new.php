<?php

// Call session_start function to access global variables
session_start();

// Get database functions
require_once ('controllers/Database.php');

// Get Article functionality
require_once ('controllers/Article.php');

// New instance of authenticate
$article = new Article();

// Every error returned by new_article function will overwrite default string in below variable
$article_feedback = '';

// If new is set (button clicked), run if block
if(isset($_POST['new']))
	// Call new_article function and provide form input as arguments
	$article_feedback = $article->new_article($_POST['title'], $_POST['content'], $_POST['category']);

?>

<!DOCTYPE html>
<html>

	<?php include 'head.php'; //Include head tag ?>
	
	<body>
		
		<?php include 'nav.php'; //Include head tag ?>
		
		<main>
			
			<form class='article' action='new.php' method='post'>
				
				<h1>Post new article</h1>
				
				<!-- Returned strings from new_article function -->
				<div><?= $article_feedback ?></div>
				
				<table>
					<tr>
						<th><span>Title:</span></th>
						<!-- Input with value as either empty, or newly typed value in the input field -->
						<td><input class='full-width' type='text' name='title' value='<?= isset($_POST['title']) ? strip_tags($_POST['title']) : '' ?>'></td>
					</tr>
					<tr>
						<th><span>Content:</span></th>
						<td>
							<!-- Input with value as either empty, or newly typed value in the input field -->
							<textarea rows='6' cols='50' name='content'><?= isset($_POST['content']) ? strip_tags($_POST['content']) : '' ?></textarea>
						</td>
					</tr>
					<tr>
						<th><span>Category:</span></th>
						<td>
							<select name='category'>
								<?php
									// Database db is a static function so I can call it without inisiting a new instance of database
									foreach (Database::db('SELECT * FROM categories')->fetchAll() as $key => $cat) { // Output rows from categories table as option tags for the select tag ?>
										<option value='<?= $cat["id"] ?>'><?= $cat['name'] ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th></th>
						<td><input type='submit' name='new' value='Submit'></td>
					</tr>
				</table>
				
			</form>
		
		</main>	
		
	</body>

</html>