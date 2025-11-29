<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password

try {
    // 1. Connect to MySQL Server (without selecting a DB yet)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server successfully...<br>";

    // 2. Create Database
    $sql = "CREATE DATABASE IF NOT EXISTS game_store_db";
    $pdo->exec($sql);
    echo "Database 'game_store_db' created successfully...<br>";

    // 3. Select the Database
    $pdo->exec("USE game_store_db");

    // 4. Create Tables (SQL from File 2)
    $commands = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('user', 'admin') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE
        )",
        "CREATE TABLE IF NOT EXISTS games (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10, 2) NOT NULL,
            category_id INT,
            image_url VARCHAR(255) DEFAULT 'placeholder.jpg',
            rating DECIMAL(3, 1) DEFAULT 0.0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        )",
        "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            game_id INT NOT NULL,
            price_at_purchase DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (game_id) REFERENCES games(id)
        )"
    ];

    foreach ($commands as $cmd) {
        $pdo->exec($cmd);
    }
    echo "Tables created successfully...<br>";

    // 5. Insert Dummy Data (Only if tables are empty)
    $check = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    if ($check == 0) {
        $pdo->exec("INSERT INTO categories (name) VALUES ('Action'), ('RPG'), ('Strategy'), ('Sports'), ('Indie')");
        
        $pdo->exec("INSERT INTO games (title, description, price, category_id, rating, image_url) VALUES 
        ('Cyber Adventure', 'A futuristic open-world RPG.', 59.99, 2, 4.8, 'game1.jpg'),
        ('Super Racer', 'High-speed racing simulation.', 29.99, 4, 4.2, 'game2.jpg'),
        ('Space Conqueror', 'Strategy game set in deep space.', 39.99, 3, 4.5, 'game3.jpg'),
        ('Pixel Hero', 'A classic style indie platformer.', 14.99, 5, 4.9, 'game4.jpg'),
        ('Zombie Shooter', 'Survive the apocalypse in this shooter.', 49.99, 1, 4.0, 'game5.jpg')");
        
        echo "Dummy data inserted...<br>";
    }

    echo "<h3 style='color:green'>Setup Complete! You can now access the website.</h3>";
    echo "<a href='index.php'>Go to Home Page</a>";

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>