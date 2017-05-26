<?php
// Class containing authenticate functionality, extends database class to access database functionality
class Authenticate extends Database {
	
	public function login($username, $password) {
		
		// Use strip_tags function to protect against XSS-injection
		$username = strip_tags($username);
		$password = strip_tags($password);

		// Check if username and password is true before executing if block
		if (empty($username) || empty($password))
			// Return error message if either field is empty
			return '<p class="alert">Please enter a username and a password</p>';

		// Get user data correspodning to username typed in login form
		$query = $this->run('SELECT password, email, id, admin FROM users WHERE username = ?', [$username]);
		
		// Fetch all data from $query var and put into array
		$row = $query->fetch(PDO::FETCH_OBJ);
		
		// Count rows yielded by above query, will be either 1 or 0
		$numrows = $query->rowCount();

		// If a matching username exists, run if block
		if ($numrows == 0)
			// Error message If numrow equals to 0, the query found no matching username
			return '<p class="alert">User does not exist</p>';
			
		// Check if username and password match corresponding tablerow in database
		if (!(password_verify($password, $row->password)))
			// If password from login form doesn't match password in db, Return error message
			return '<p class="alert">Incorrect password</p>';
		
		// Create a new session
		$_SESSION['username'] = $username;
		$_SESSION['email'] = $row->email;
		$_SESSION['id'] = $row->id;
		$_SESSION['admin'] = $row->admin;
		
		// Redirect to index
		header('location: index.php');

	}
	
	public function register($email, $username, $password, $repeatpassword) {
		
		// Use strip_tags function to protect against XSS-injection
		$email = strip_tags($email);
		$username = strip_tags($username);
		$password = strip_tags($password);
		$repeatpassword = strip_tags($repeatpassword);
		
		// Empty field check
		if (!($email && $username && $password && $repeatpassword))
			// Return error message if form contains any empty fields
			return '<p class="alert">Please fill out the whole form</p>';
		
		// If $username exceeds limit of 25 chars, run if block
		if (strlen($username)>25)
			// Return error message if username is too long
			return '<p class="alert">Username must be less than 25 characters</p>';
			
		// Safeguard for existing users with the same username
		$namecheck = $this->run('SELECT username FROM users WHERE username = ?', [$username]);
		$numrows = $namecheck->rowCount();
			
		// If namecheck query finds a row, run if block
		if ($numrows != 0)
			// Return error message if username is taken
			return '<p class="alert">Username already taken</p>';
				
		// If email exceeds limit of 89 characters, run if block
		if (strlen($email)>89)
			// Return error message if email is too long
			return '<p class="alert">Email must be less than 89 characters</p>';
			
		// If passwords do not match, run if block
		if ($password != $repeatpassword)
			// Return error message if passwords do not match
			return '<p class="alert">Passwords do not match</p>';
						
		// Check password length, only need to check one of the vars because they already matched, run if block if password is length more or less than specified values
		if (strlen($password)>25 || strlen($password)<6)
			// Echo message if password is too long or too small
			return '<p class="alert">Password must be between 6 and 25 characters</p>';
							
		// Assign a new password hash to variable
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		
		// Insert the data from the form into the users table
		$newuser = $this->run('INSERT INTO users VALUES ("", ?, ?, ?, "")', [$email, $username, $password]);
		
		// Return success message
		return '<p class="success">You have been registered! <a href="login.php">Go to login page</a></p>';
			
	}
	
	public function update($email, $username) {
		
		// Use strip_tags function to protect against XSS-injection
		$email = strip_tags($email);
		$username = strip_tags($username);
		
		// If either field, or both, is empty, run if block
		if (!($email && $username))
			// Return error message if form contains any empty fields
			return '<p class="alert">Please fill out the form</p>';
			
		// Safeguard for existing users with the same username
		$namecheck = $this->run('SELECT username FROM users WHERE username = ?', [$username]);
		$numrows = $namecheck->rowCount();
		
		// If namecheck query finds a row, Return error message and return false to quit function
		if ($numrows != 0 && $_SESSION['username'] != $username) // Exclude username of active user
			return '<p class="alert">Username already taken</p>';
			
		// Check if email exceeds limit of 89 characters
		if (strlen($email)>89)
			return '<p class="alert">Email must be less than 89 characters</p>'; // Return error message if email is too long
				
		// Check if $username exceeds limit of 25 chars
		if (strlen($username)>25)
			return '<p class="alert">Username must be less than 25 characters</p>'; // Return error message if username is too long
						
		// Update the data from the form into the users table
		$updateuser = $this->run('UPDATE users SET email = ?, username = ? WHERE id = ?', [$email, $username, $_SESSION['id']]);
		
		// Update existing session
		$_SESSION['username'] = $username;
		
		// Return success message
		return '<p class="success">Your credentials have been updated! <a href="index.php">Go back to news page!</a></p>';
		
	}
	
	public function new_password($oldpassword, $newpassword, $repeatnewpassword) {
		
		// Use strip_tags function to protect against XSS-injection
		$oldpassword = strip_tags($oldpassword);
		$newpassword = strip_tags($newpassword);
		$repeatnewpassword = strip_tags($repeatnewpassword);
		
		// Empty field check
		if (!($oldpassword && $newpassword && $repeatnewpassword))
			// Return error message if form contains any empty fields
			return '<p class="alert">Please fill out the form</p>';
			
		// Check if old password is correct
		// SQL select password
		$query = $this->run('SELECT password FROM users WHERE id = ?', [$_SESSION['id']])->fetch();
		
		// Check if typed password match corresponding password in database
		if (!password_verify($oldpassword, $query['password']))
			// If password from update form doesn't match password in db, Return error message
			return '<p class="alert">Incorrect old password</p>';
		
		// Check if passwords match
		if ($newpassword != $repeatnewpassword)
			// Return error message if passwords do not match
			return '<p class="alert">Passwords do not match</p>';
					
		//Check password length, only need to check one of the vars because they already matched
		if (strlen($newpassword)>25 || strlen($newpassword)<6)
			return '<p class="alert">Password must be between 6 and 25 characters</p>'; // Echo message if password is too long or too small
						
		//Update variable and create a new password hash
		$newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
		
		// Insert the data from the form into the users table
		$updatepassword = $this->run('UPDATE users SET password = ? WHERE id = ?', [$newpassword, $_SESSION['id']]);
		
		// Return success message
		return '<p class="success">Your password has been updated! <a href="index.php">Go back to news page</a></p>';
		
		}
}