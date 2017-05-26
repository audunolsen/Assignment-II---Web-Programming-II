<?php
// Class containing authenticate functionality
class Database {
	
	// Store database connection
	public $db;
	
	// Run atomagicaly when classes called upon
	public function __construct() {
		
		// Connect to db
		$this->db = new PDO('mysql:host=localhost;dbname=news', 'root', 'root') or die('Unable to connect to db');
		// Display SQL errors
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		
	}
	
	// Function which uses prepare and execute to avoid SQL injection when using sql statements
	// Takes either one or two arguments, the SQL statement and an additional array of variables
	public function run($sql, $vars = null) {
		
		$q = $this->db->prepare($sql);
		
		if(!is_null($vars)) 
			$q->execute($vars);
			
		if(is_null($vars))
			$q->execute();
		
		return $q;
	}
	
	public static function db($sql, $vars = null){
		$db = new self();
		return $db->run($sql, $vars);
	}
	
}