<?php

class Database {

	// Function to the database and tables and fill them with the default data
	function create_database($data)
	{
		// Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],'');

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Create the prepared statement
		$mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['database']);

        // change database collation
		$mysqli->query("ALTER DATABASE ".$data['database']." CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

		// Close the connection
		$mysqli->close();

		return true;
	}

	// Function to create the tables and fill them with the default data
	function create_tables($data)
	{
        // Connect to the database
		$mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],$data['database']);

		// Check for errors
		if(mysqli_connect_errno())
			return false;

		// Open the default SQL file

        // install with dummy data
        if($data['sample_data'])
        {
            // install dummy database (with dummy data)
		    $query = file_get_contents('database/anofie_dummy_db.sql');
        }
        else
        {
            // install empty database (without dummy data)
		    $query = file_get_contents('database/anofie_empty_db.sql');
        }

		// Execute a multi query
		$mysqli->multi_query($query);

		// Close the connection
		$mysqli->close();

		return true;
	}
}