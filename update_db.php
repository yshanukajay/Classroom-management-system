<?php
require_once 'config/db.php';

$sql = "ALTER TABLE classrooms MODIFY COLUMN status ENUM('Active', 'Maintenance', 'Inactive') DEFAULT 'Active'";
if ($mysqli->query($sql) === TRUE) {
    echo "Table updated successfully";
} else {
    echo "Error updating table: " . $mysqli->error;
}
$mysqli->close();
?>