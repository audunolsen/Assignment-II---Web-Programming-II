<?php

// Call session_start function to access global variables
session_start();

// Get database functions
require_once ("controllers/Database.php");

// Get register functionality
require_once ("controllers/Authenticate.php");

// New instance of authenticate
$authenticate = new Authenticate();

// Every error returned by update function will overwrite default string in below variable
$update_feedback = '';

// Every error returned by new_password function will overwrite default string in below variable
$password_feedback = '';

// If update is set (button clicked), run if block
if(isset($_POST['update']))
	// Call update function and provide form input as arguments
	$update_feedback = $authenticate->update($_POST['email'], $_POST['username']);
	
// If newpassword_submit is set (button clicked), run if block
if(isset($_POST['newpassword_submit']))
	// Call new_password function and provide form input as arguments
	$password_feedback = $authenticate->new_password($_POST['oldpassword'], $_POST['newpassword'], $_POST['repeatnewpassword']);

?>

<!DOCTYPE html>
<html>
	
	<?php include 'head.php'; //Include head tag  ?>
	
	<body>
		
		<?php include 'nav.php'; //Include nav tag  ?>
		
		<div class="authenticate-container">
			
			<div>
				
				<form method='post'>
					
					<h3>Update email &amp; username</h3>
					
					<!-- Returned strings from update function -->
					<div><?= $update_feedback ?></div>
					
					<table>
						<tr>
							<th><span>Change Email:</span></th>
							<!-- Input with value as corresponding email row from users table, or newly typed value in the input field -->
							<td><input type='text' name='email' value='<?= isset($_POST['email']) ? strip_tags($_POST['email']) : $_SESSION['email'] ?>'></td>
						</tr>
						<tr>
							<th><span>Change username:</span></th>
							<!-- Input with value as corresponding username row from users table, or newly typed value in the input field -->
							<td><input type='text' name="username" value='<?= isset($_POST['username']) ? strip_tags($_POST['username']) : $_SESSION['username'] ?>'></td>
						</tr>
						<tr>
							<th></th>
							<td><input type='submit' name='update' value='Update'></td>
						</tr>
					</table>
				
				</form>
				
				<hr>
				
				<form method='post'>
					
					<h3>New password</h3>
					
					<!-- Returned strings from new_password function -->
					<div><?= $password_feedback ?></div>
					
					<table>
						<tr>
							<th><span>Old password:</span></th>
							<td><input type='password' name="oldpassword" value=''></td>
						</tr>
						<tr>
							<th><span>New password:</span></th>
							<td><input type='password' name='newpassword' value=''></td>
						</tr>
						<tr>
							<th><span>Repeat password:</span></th>
							<td><input type='password' name='repeatnewpassword' value=''></td>
						</tr>
						<tr>
							<th></th>
							<td><input type='submit' name='newpassword_submit' value='Set new password'></td>
						</tr>
					</table>
				
				</form>
			
			</div>
		
		</div>
		
	</body>
	
</html>