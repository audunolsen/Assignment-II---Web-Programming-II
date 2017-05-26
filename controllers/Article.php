<?php
// Class containing article functionality, extends database class to access database functionality
class Article extends Database {
	
	// Get articles function
	public function get_articles() {
		
		// Set a cookie if it does not already exsist, will expire in amonth
		if(!isset($_COOKIE['order'])){
			setcookie('order', 'date', (86400 * 30)+time(), '/');
			$_COOKIE['order'] = 'date';
		}
		
		// Array with parameters to put into the sql, related to the search functionality
		$vars = isset($_GET['search']) ? ['%'.$_GET['search'].'%'] : null;
		// SQL query to get articles
		$query = $this->run('SELECT a.*,
			 c.name,
			 u.username,
			 (SUM(r.vote = 1) / COUNT(r.vote) * 100) AS percent,
			 SUM(r.vote = 1) As vote,
			 (SUM(r.vote = 1) * (SUM(r.vote = 1) / COUNT(r.vote) * 100)) As hidden_value
			 
			 FROM articles AS a
			 
			 LEFT JOIN categories AS c ON c.id = a.category_id
			 LEFT JOIN users AS u ON u.id = a.user_id
			 LEFT JOIN ratings AS r ON r.article_id = a.id
			 
			 '.(isset($_GET['search']) ? 'WHERE a.title LIKE ?' : '').'
			 
			 GROUP BY a.id
			 ORDER BY '.($_COOKIE['order'] == 'date' ? 'a.created DESC' : 'hidden_value DESC'), $vars);
		
		// Return the query results
		return $query->fetchAll();
		
	}
	
	// Return 1, 0 or -1 according if the user has voted up, nothing or down on a post
	public function hasVoted($article){
		if (!isset($_SESSION['username'])) return 0;
		$vote = $this->run('SELECT vote FROM ratings WHERE user_id = ? AND article_id = ?', [$_SESSION['id'], $article])->fetch();
		return ($vote['vote'] == -1 || $vote['vote'] == 1) ? $vote['vote'] : 0;
	}
	
	
	// Only get articles by logged in user function
	public function get_user_articles() {
		
		// SQL query to get articles
		$query = $this->run('SELECT a.*,
			 c.name,
			 u.username,
			 (SUM(r.vote = 1) / COUNT(r.vote) * 100) AS percent,
			 SUM(r.vote = 1) As vote,
			 (SUM(r.vote = 1) * (SUM(r.vote = 1) / COUNT(r.vote) * 100)) As hidden_value
			 
			 FROM articles AS a
			 
			 LEFT JOIN categories AS c ON c.id = a.category_id
			 LEFT JOIN users AS u ON u.id = a.user_id
			 LEFT JOIN ratings AS r ON r.article_id = a.id
			 
			 WHERE a.user_id = ?
			 
			 GROUP BY a.id
			 ORDER BY created DESC
			 
			 ', [$_SESSION['id']]);

		// Return the query results
		return $query->fetchAll();
		
	}
	
	// New article function
	public function new_article($title, $content, $category) {
		
		// Use strip_tags function to protect against XSS-injection
		$title = strip_tags($title);
		$content = strip_tags($content);
		$category = strip_tags($category);
		
		// Empty field check
		if ($title && $content && $category) {
			
			// Check if title is too long
			if (strlen($title)>100) {
				
				return '<p class="alert">Title must be less than 100 characters</p>'; // Return error message if email is too long'
			
			} else {
				
				// Insert into articles table
				$newpost = $this->run('INSERT INTO articles (user_id, category_id, title, content) VALUES (?, ?, ?, ?)', [$_SESSION['id'], $category, $title, $content]);
				
				// Redirect to index
				header('location: index.php');
				
				
			}
			
		} else // Return error message if form contains any empty fields
			return '<p class="alert">Please fill out the whole form</p>';
		
	}
	
	// Edit article function
	public function edit_article($id, $title, $content, $category) {
		
		// Use strip_tags function to protect against XSS-injection
		$title = strip_tags($title);
		$content = strip_tags($content);
		$category = strip_tags($category);
		
		// Empty field check
		if ($title && $content && $category) {
			
			// Check if title is too long
			if (strlen($title)>100) {
				
				return '<p class="alert">Title must be less than 100 characters</p>'; // Return error message if email is too long'
			
			} else {
				
				// Insert into articles table
				$newpost = $this->run('UPDATE articles SET category_id = ?, title = ?, content = ? WHERE id = ?', [$category, $title, $content, $id]);
				
				// Redirect to index
				header('location: posts.php');
				
			}
			
		} else // Return error message if form contains any empty fields
			return '<p class="alert">Please fill out the whole form</p>';
		
	}
	
}