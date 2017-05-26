<?php

// Call session_start function to access global variables
session_start();

// Get database functions
require_once ('controllers/Database.php');

// New instance of database
$db = new Database();

// Get Article functionality
require_once ('controllers/Article.php');

// New instance of authenticate class
$article = new Article();

// Every error returned by edit_article function will overwrite default string in below variable
$edit_feedback = '';

// SQL statement to get article with same id as id in url and assign to variable
$edit = $db->run('SELECT * FROM articles WHERE id = ?', [$_GET['id']])->fetch();

// If edit is set (button clicked), run if block
if(isset($_POST['edit']))
	// Call edit_function function and provide form input as arguments
	$edit_feedback = $article->edit_article($edit['id'], $_POST['title'], $_POST['content'], $_POST['category']);

?>

<!DOCTYPE html>
<html>

	<?php include 'head.php'; //Include head tag ?>
	
	<body>
		
		<?php include 'nav.php'; //Include head tag ?>
		
		<main>
			
			<form class='article' method='post'>
				
				<h1>Edit article</h1>
				
				<!-- Returned strings from edit_article function -->
				<div><?= $edit_feedback ?></div>
				
				<table>
					<tr>
						<th><span>Title:</span></th>
						<!-- Input with value as corresponding title row from article table, or newly typed value in the input field -->
						<td><input class="full-width" type="text" name="title" value='<?= isset($_POST['title']) ? strip_tags($_POST['title']) : $edit['title'] ?>'></td>
					</tr>
					<tr>
						<th><span>Content:</span></th>
						<td>
							<!-- Input with value as corresponding content row from article table, or newly typed value in the input field -->
							<textarea rows="6" cols="50" name="content"><?= isset($_POST['content']) ? strip_tags($_POST['content']) : $edit['content'] ?></textarea>
						</td>
					</tr>
					<tr>
						<th><span>Category:</span></th>
						<td>
							<select name='category'>
								<?php // Database db is a static function so I can call it without inisiting a new instance of database
									foreach (Database::db('SELECT * FROM categories')->fetchAll() as $key => $cat) { // Output rows from categories table as option tags for the select tag ?>
										<option value="<?= $cat['id'] ?>" <?= $edit['category_id'] == $cat['id'] ? 'selected' : '' ?> ><?= $cat['name'] ?></option>
								<?php } ?>	
							</select>
						</td>
					</tr>
					<tr>
						<th></th>
						<td><input type='submit' name='edit' value='Edit'></td>
					</tr>
				</table>
				
			</form>
		
		</main>	
		
	</body>
	
</html>