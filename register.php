<?php

// Call session_start function to access global variables
session_start();

// Get database functions
require_once ("controllers/Database.php");

// Get register functionality
require_once ("controllers/Authenticate.php");

// New instance of authenticate
$authenticate = new Authenticate();

// Every error returned by register function will overwrite default string in below variable
$register_feedback = '<p class="info">Already have a user? <a href="login.php">Sign in!</a></p>';

// If register is set (button clicked), run if block
if(isset($_POST['register']))
	// Call register function and provide form input as arguments
	$register_feedback = $authenticate->register($_POST['email'], $_POST['username'], $_POST['password'], $_POST['repeatpassword']);

?>

<!DOCTYPE html>
<html>
	
	<?php include 'head.php'; //Include head tag  ?>
	
	<body>
		
		<?php include 'nav.php'; //Include nav tag  ?>
		
		<div class='authenticate-container'>
			
			<form class="authenticate" action='register.php' method='post'>
				
				<h1>Register</h1>
				
				<!-- Returned strings from register function -->
				<div><?= $register_feedback ?></div>
				
				<table>
					<tr>
						<th><span>Email:</span></th>
						<!-- Input with value as either empty or newly typed value in the input field -->
						<td><input type='text' name='email' value='<?= isset($_POST['email']) ? strip_tags($_POST['email']) : '' ?>'></td>
					</tr>
					<tr>
						<th><span>Username:</span></th>
						<!-- Input with value as either empty or newly typed value in the input field -->
						<td><input type='text' name="username" value='<?= isset($_POST['username']) ? strip_tags($_POST['username']) : '' ?>'></td>
					</tr>
					<tr>
						<th><span>Password:</span></th>
						<td><input type='password' name='password' value=''></td>
					</tr>
					<tr>
						<th><span>Repeat password:</span></th>
						<td><input type='password' name='repeatpassword' value=''></td>
					</tr>
					<tr>
						<th></th>
						<td><input type='submit' name='register' value='Register'></td>
					</tr>
				</table>
			
			</form>
		
		</div>
		
	</body>
	
</html>