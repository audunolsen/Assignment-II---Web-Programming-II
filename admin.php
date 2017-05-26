<?php

// Call session_start function to access global variables
session_start();

// Only show page content if session exists and user is admin, redirect to index if not
if (!isset($_SESSION['username']) || ($_SESSION['admin'] == 0))
	header('location: index.php');

// Get database functions
require_once ('controllers/Database.php');

// Get Article functionality
require_once ('controllers/Article.php');

// Get Admin functionality
require_once ('controllers/Admin.php');

// New instance of Article class
$article = new Article();

// New instance of Admin class
$admin = new Admin();

// Every error returned by add_category function will overwrite default string in below variable
$category_feedback = '';

// If category is set (button clicked), run if block
if(isset($_POST['category']))
	// Call login function and provide form input as arguments
	$category_feedback = $admin->add_category($_POST['name']);

?>

<!DOCTYPE html>
<html>
	
	<?php include 'head.php'; //Include head tag ?>
	
	<body>
		
		<?php include 'nav.php'; //Include nav tag ?>
		
		<main>
			
			<h1>Manage articles</h1>
			
			<?php
				// If there are articles, run if block
				if (!empty($article->get_articles())) {
					// Foreach loop for array returned by get_articles function
					foreach ($article->get_articles() as $key => $value) {
						// Echo markup for each array item (article)
						echo '
							<article>
							<h4>'.$value['title'].'</h4>
							<ul class="about-article inline">
								<li>' . ($value['name'] == null ? 'deleted category' : $value['name']) . '</li>
								<li>●</li>
								<li>Author: ' . ($value['username'] == null ? 'deleted user' : $value['username']) . '</li>
								<li>●</li>
								<li>Created: ' . date('d/m/y', strtotime($value['created'])) .( $value['created'] != $value['updated'] ? ' </li><li>●</li><li>Updated: '.date('d/m/y', strtotime($value['updated'])) : ''). '</li>
							</ul>
							<br>
							<p>' . $value['content'] . '</p>
							<ul class="inline post-options">
								<li><a href="controllers/functions/delete-post.php?id='.$value['id'].'&redirect=admin.php" class="delete">Delete</a></li>
							</ul>
							</article>
							<br>';
					}
				} else // Echo message if there are no articles
					echo '<p class="info">There are currently no articles</p>';
						
			?>
			
			<p></p> <!-- Lazy margin -->
			<hr>
			
			<h1>Manage categories</h1>
			<table>
				<?php
					// If there are categoreis, run if block
					if (!empty($admin->article_count())) {
						foreach ($admin->article_count() as $key => $cat) { // Foreach for array retunred by article_count function
							// Echo markup for each array item returned by foreach loop
							echo '
								<tr>
									<td>'.$cat['name'].'</td>
									<td> Articles: '.$cat['total'].'</td>
									<td>
										<ul class="inline post-options">
											<li><a href="controllers/functions/delete-category.php?id='.$cat['id'].'" class="delete">Delete</a></li>
										</ul>
									<td>
								</tr>
							';
						}
					} else // Echo message if there are no articles
						echo '<p class="info">There are currently no categories</p>';
				
				?>
			</table>
			
			<h4>Add category</h4>
			
			<!-- Returned strings from login function -->
			<div><?= $category_feedback ?></div>
			
			<form method="post">	
				<table>
					<tr>
						<th><span>Category name:</span></th>
						<!-- Input with value as either empty or newly typed value in the input field -->
						<td><input type='text' name='name' value='<?= isset($_POST['name']) ? strip_tags($_POST['name']) : '' ?>'></td>
					</tr>
					<tr>
						<th></th>
						<td><input type='submit' name='category' value='Add category'></td>
					</tr>
				</table>
			</form>
			
			<hr>
			
			<h1>Managse users</h1>
			<table>
				<?php // If there are users, run if block
					if (!empty($admin->get_users())) {
						// Foreach loop for array returned by get_users function
						foreach ($admin->get_users() as $key => $value) {
							// Echo markup for every array item (user) returned by foreach loop
							echo '
								<tr>
									<td><p class="bold">'.$value['username'].'</p></td>
									<td>
										<ul class="inline post-options">
											<li><a href="controllers/functions/delete-user.php?id='.$value['id'].'" class="delete">Delete</a></li>
										</ul>
									</td>
								</tr>
							';
						}
					} else // Echo message if there are no users
						echo '<p class="info">There are currently no other users</p>';
				?>
			</table>
		
		</main>
		
	</body>
</html>