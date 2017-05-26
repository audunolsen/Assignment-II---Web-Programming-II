<?php

// Call session_start function to access global variables
session_start();

// Destroy session
session_destroy();

// Redirect to login page
header('location: ../../login.php');