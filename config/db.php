<?php
// Database credentials
// Update these settings based on your local server configuration (e.g., XAMPP/WAMP)
$host = 'localhost';
$db_name = 'game_store_db';
$username = 'root';      // Default XAMPP username
$password = '';          // Default XAMPP password (leave empty)

try {
  // Create a new PDO instance
  $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);

  // Set Error handling to Exception (useful for debugging)
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Set default fetch mode to Associative Array
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
  // If connection fails, stop script and show error
  die("ERROR: Could not connect. " . $e->getMessage());
}
?>