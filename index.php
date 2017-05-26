<?php

// Call session_start function to access global variables
session_start();

// Append search query from search input to url when searchbtn is is set
if(isset($_POST['searchbtn']))
	header('location: index.php?search='. $_POST['search']);

// Get database functions
require_once ('controllers/Database.php');

// Get Article functionality
require_once ('controllers/Article.php');

// New instance of Article class
$article = new Article();

// SQL statement to get articles and assign to variable
$articles = $article->get_articles();

?>

<!DOCTYPE html>
<html>
	
	<?php include 'head.php'; //Include head tag ?>
	
	<body>
		
		<?php include 'nav.php'; //Include nav tag ?>
		
		<main>
			
			<div class='sort'>
				
				<?php
					// If user has searched, run if block
					if(isset($_GET['search'])) {
						// Echo message showing string searched for
						echo '<span class="center">Search results found for "' . $_GET['search'] . '"<br><a href="index.php">Clear search</a></span>';
					} else
						// Echo sorting options
						// insert active class attribute to whichever button returning true in the shorthand if
						echo '
							<a href="controllers/functions/cookie.php?order=date" class="' . ($_COOKIE['order'] == 'date' ? 'active' : '') . '"><span>date</span></a>
							<a href="controllers/functions/cookie.php?order=popularity" class="' . ($_COOKIE['order'] == 'date' ? '' : 'active') . '"><span>popularity</span></a>';
				?>
				
			</div>
			
			<div class='arrow-line'>
				<div class='side-line'></div>
				<div class='triangle'></div>
				<div class='side-line'></div>
			</div>
			
			<?php
				// If articles are returned, run if block
				if (!empty($articles)) {
					// Loop trough each array item returned by get_articles function
					foreach ($articles as $key => $value) {
						// Check is user who is logged in has previously voted
						$hasVoted = $article->hasVoted($value['id']);	
						// Variable containing markup for each array item (article)
						$post = '
							<article>
							<h1>'.$value['title'].'</h1>
							<ul class="about-article inline">
								<li>' . ($value['name'] == null ? 'deleted category' : $value['name']) . '</li>
								<li>●</li>
								<li>Author: ' . ($value['username'] == null ? 'deleted user' : $value['username']) . '</li>
								<li>●</li>
								<li>Created: ' . date('d/m/y', strtotime($value['created'])) .( $value['created'] != $value['updated'] ? ' </li><li>●</li><li>Updated: '.date('d/m/y', strtotime($value['updated'])) : ''). '</li>
							</ul>
							<br>
							<p>' . $value['content'] . '</p>
							<p class="votebox">Vote: '.($value['vote'] == null ? 'No votes' : $value['vote']).' / '.(int) $value['percent'].'%</p>';
							// Append rating system to var if user is logged in
							if (isset($_SESSION['username']))
								$post .= '
									<ul class="inline rate">
										<li><a data-id="' . $value['id'].'" data-vote="1" class="like '. ($hasVoted == 1 ? 'active' : '') . '">Like △</a></li>
										<li>|</li>
										<li><a data-id="' . $value['id'].'" data-vote="-1" class="dislike '. ($hasVoted == -1 ? 'active' : '') . '">Dislike ▽</a></li>
									</ul>';
							// Append end tag to var
							$post .= '
							</article>
							<br>';
						// Echo the var containing the all the markup for each array item
						echo $post;
					}
				} else
					// Echo message if array is empty
					if (isset($_GET['search'])) {
						// Echo message if search yields no results
						echo '<h3 class="center">No search result found for "'.$_GET['search'].'"</h3>';
					} else
						// Echo message if there are no articles yet
						echo '<h3 class="center">There are no articles at this time, <br><a href="new.php">write one!<a></h3>';	
			?>
		
		</main>
		
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
		<script src='scripts/main.js'></script>
		
	</body>
	
</html>