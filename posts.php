<?php

// Call session_start function to access global variables
session_start();

// Only show page content if session exists, redirect to loginpage if not
if (!isset($_SESSION['username']))
	header('location: login.php');

// Get database functions
require_once ("controllers/Database.php");

// Get Article functionality
require_once ("controllers/Article.php");

// New instance of Article class
$article = new Article();

?>

<!DOCTYPE html>
<html>
	
	<?php include 'head.php'; //Include head tag ?>
	
	<body>
		
		<?php include 'nav.php'; //Include head tag ?>
		
		<main>
			
			<h1>Your articles</h1>
			
			<?php		
			// If user has posted any previous articles, run if block
			if (!empty($article->get_user_articles())) {
				// Foreach to loop trough array returned by get_user_articles function
				foreach ($article->get_user_articles() as $key => $value) {
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
						<p class="votebox">Vote: '.($value['vote'] == null ? 'No votes' : $value['vote']).' / '.(int) $value['percent'].'%</p>
						<ul class="inline post-options">
							<li><a href="edit.php?id='.$value['id'].'" class="edit">Edit</a></li>
							<li>|</li>
							<li><a href="controllers/functions/delete-post.php?id='.$value['id'].'" class="delete">Delete</a></li>
						</ul>
						</article>
						<br>';
				}				
			} else // Echo message if user has not posted any articles yet
				echo '<p class="info">You have not posted any articles, <a href="new.php">write one!</a></p>';
			?>
			
		</main>
		
	</body>
	
</html>