<?php	
	/**
	* Created to connect to the database
	*/		
		function connect() {
			define('DB_NAME', 'Stock');
			define('DB_USER', 'root');
			define('DB_PASSWORD', 'root');
			define('DB_HOST', 'localhost');

	        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	        if (!$conn) {
	            return 'Could not connect to database!';
	        } else {
	            return $conn;        
    		}
		}

		function closeConnection() {
	        mysqli_close($myconn);
    	}	
    		
?>