<?php
$host = 'localhost';
$dbname = 'fresh';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $connect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connection successful, perform database operations here
    // ...

} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}

?>
