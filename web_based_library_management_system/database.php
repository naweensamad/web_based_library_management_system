<?php
// Define database connection parameters
$host = 'localhost'; // Hostname of the database server
$dbname = 'library_management_system'; // Name of the database to connect to
$username = 'root'; // Username for the database
$password = ''; // Password for the database (left empty here for local environment)

// Create a connection to the MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If the connection failed, terminate the script and display an error message
    die("Connection failed: " . $conn->connect_error);
} 

// else {
//     echo "Connected successfully!"; // Message confirming successful connection
// }

?>
