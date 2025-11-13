<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP is working!<br>";

$servername = "localhost";
$username   = "utx299ug72uc9";
$password   = "DATABASEPWORD123";
$dbname     = "dbkgyginqghrrn";


try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    echo "Connection successful!<br>";
    echo "Database selected: " . $dbname . "<br>";
    
    $result = $conn->query("SHOW TABLES");
    echo "Tables in database:<br>";
    while($row = $result->fetch_array()) {
        echo "- " . $row[0] . "<br>";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>