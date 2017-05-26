<?php

// Shorthand if which assigns either date or popularity string to variable
$order = $_GET['order'] == 'date' ? 'date' : 'popularity'; 

// Set order cookie assign value and expire time
setcookie('order', $order, (86400 * 30)+time(), '/');

// Redirect to index (refresh)
header('location: ../../index.php');