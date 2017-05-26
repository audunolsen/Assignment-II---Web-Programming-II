<?php

// Call session_start function to access global variables
session_start();

// Redirect to index if already logged in
if (isset($_SESSION['username']))
	header('location: index.php');

// Get database functions
require_once ('controllers/Database.php');

// Get login functionality
require_once ('controllers/Authenticate.php');

// New instance of authenticate
$authenticate = new Authenticate();

// Every error returned by login function will overwrite default string in below variable
$login_feedback = '<p class="info">First time here? <a href="register.php">Register yourself!</a></p>';

// If login is set (button clicked), run if block
if(isset($_POST['login']))
	// Call login function and provide form input as arguments
	$login_feedback = $authenticate->login($_POST['username'], $_POST['password']);

?>

<!DOCTYPE html>
<html>
	
	<?php include 'head.php'; //Include head tag ?>
	
	<body>
		
		<?php include 'nav.php'; //Include nav tag ?>
		
		<div class='authenticate-container'>
		
			<form class='authenticate' method='post'>
				
				<h1>Sign in</h1>
				
				<!-- Returned strings from login function -->
				<div><?= $login_feedback ?></div>
				
				<table>
					<tr>
						<th><span>Username:</span></th>
						<!-- Input with value as either empty or newly typed value in the input field -->
						<td><input type='text' name='username' value='<?= isset($_POST['username']) ? strip_tags($_POST['username']) : '' ?>'></td>
					</tr>
					<tr>
						<th><span>Password:</span></th>
						<td><input type='password' name='password'></td>
					</tr>
					<tr>
						<th></th>
						<td><input type='submit' name='login' value='Sign in'></td>
					</tr>
				</table>
			
			</form>
			
		</div>
		
	</body>
	
</html>