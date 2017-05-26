<?php
// Class containing admin functionality, extends database class to access database functionality
class Admin extends Database {
	
	// Get users function
	public function get_users() {
		
		// SQL query to get all users exept the one currently logged in
		return $this->run('SELECT * 
			FROM users
			WHERE id NOT IN (?)
			', [$_SESSION['id']])->fetchAll();
		
	}
	
	// Count articles per category
	public function article_count() {
		
		// SQL query to get all articles join articles and count articles per category
		return $this->run('SELECT c.name, c.id, count(a.id) AS total
			FROM categories AS c
			LEFT JOIN articles AS a ON c.id = a.category_id
			GROUP BY c.id')->fetchAll();	
	}
	
	public function add_category($name) {
		
		// Use strip_tags function to protect against XSS-injection
		$name = strip_tags($name);
		
		// Empty field check
		if ($name) {
			
			// Check if title is too long
			if (strlen($name)>32) {
				
				// Return error message if category name is too long'
				return '<p class="alert">Category name must be less than 32 characters</p>';
			
			} else {
				
				// Insert into articles table
				$newpost = $this->run('INSERT INTO categories (name) VALUES (?)', [$name]);
				
				// Redirect to index
				header('location: admin.php');
				
			}
			
		} else // Return error message if form contains any empty fields
			return '<p class="alert">Please give a category name</p>';
	}
	
}