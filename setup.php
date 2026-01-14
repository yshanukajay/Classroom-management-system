<?php
// Database credentials matching config.php but without selecting DB yet
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

// Connect to MySQL server first
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS cams_db";
if ($mysqli->query($sql) === TRUE) {
    echo "Database created successfully or already exists.<br>";
} else {
    die("Error creating database: " . $mysqli->error);
}

// Select the database
$mysqli->select_db('cams_db');

// Read the SQL file
$sqlFile = __DIR__ . '/database/schema.sql';
if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    // Remove the CREATE DATABASE and USE lines as we handled that

    // Split into individual queries
    $queries = explode(';', $sqlContent);

    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                if ($mysqli->query($query) === TRUE) {
                    // Success
                } else {
                    echo "Error executing query: " . substr($query, 0, 50) . "... - " . $mysqli->error . "<br>";
                }
            } catch (mysqli_sql_exception $e) {
                // Catch duplicate key/index errors (Code 1061: Duplicate key name)
                if ($e->getCode() == 1061 || strpos($e->getMessage(), 'Duplicate') !== false || strpos($e->getMessage(), 'already exists') !== false) {
                    echo "Note: Index or Table already exists (Skipping).<br>";
                } else {
                    echo "Error executing query: " . $e->getMessage() . "<br>";
                }
            }
        }
    }
    echo "Tables setup completed successfully.<br>";
    echo "<a href='index.php'>Go to Login</a>";
} else {
    echo "Error: database/schema.sql not found.";
}

$mysqli->close();
?>