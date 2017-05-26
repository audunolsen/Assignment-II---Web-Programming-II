<nav>
	
	<ul class='left inline'>
		<li>
			<a href='index.php'>
				<span class='logo'>Best News In The Universe</span>
			</a>
		</li>
	</ul>
	
	<?php
	
	// Variable for search form
	$search = '
		<li>
			<form method="post" action="index.php" class="search">
				<input class="search" type="text" name="search" onfocus="if(this.value != \'\') { this.value = \'\'; }" onblur="if (this.value == \'\' || this.value != \'\') { this.value = \'search\'; }" value="search">
				<input class="search" type="submit" name="searchbtn" value="Go">
			</form>
		</li>
	';
	
	// Display below nav items if no session
	if (!isset($_SESSION['username'])) {
		echo'
			<ul class="right inline">
				' . $search . '
				<li>|</li>
				<li><a href="login.php">Sign in</a></li>
				<li>|</li>
				<li><a href="register.php">Register</a></li>
			</ul>';
	} else // Only show user spesific nav items if session exists, show additional admin nav item if user is admin
		echo '
			<ul class="right inline">
				' . $search . '
				<li>|</li>
				<li><a href="profile.php">Edit your profile</a></li>
				<li>|</li>
				<li><a href="new.php">New article</a></li>
				<li>|</li>
				<li><a href="posts.php">Your articles</a></li>
				' . ($_SESSION['admin'] == 1 ? '<li>|</li><li><a href="admin.php">Manage site</a></li>' : '') . '
				<li>|</li>
				<li><a href="controllers/functions/logout.php">Logout</a></li>
			</ul>
		';
	?>
	
</nav>
