<?php
$host = "localhost";
$username = "root";
$password = "12345";

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS shopease_db");
    $pdo->exec("USE shopease_db");

    // Create Users Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create Products Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        category VARCHAR(100),
        price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255),
        description TEXT,
        rating DECIMAL(2, 1) DEFAULT 0.0,
        featured BOOLEAN DEFAULT FALSE,
        badge VARCHAR(50),
        stock_quantity INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create Orders Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Create Order Items Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");

    echo "Database and tables created successfully!";

    // Check if products table is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    if ($stmt->fetchColumn() == 0) {
        $products = [
            ['Wireless Headphones', 'electronics', 79.99, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&q=80', 'Premium wireless headphones with active noise cancellation, 30-hour battery life, and crystal-clear sound quality.', 4.5, 1, 'Best Seller', 50],
            ['Smart Watch Pro', 'electronics', 149.99, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80', 'Advanced smartwatch with heart-rate monitor, GPS, sleep tracking, and a sleek design for everyday use.', 4.7, 1, 'New', 30],
            ['Leather Jacket', 'fashion', 129.00, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&q=80', 'Classic genuine leather jacket with premium stitching and comfortable fit for a stylish look.', 4.3, 1, NULL, 20],
            ['Running Sneakers', 'fashion', 89.50, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80', 'Lightweight running sneakers with breathable mesh and shock-absorbing soles for maximum comfort.', 4.6, 1, 'Hot', 40],
            ['Modern Desk Lamp', 'home', 39.99, 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&q=80', 'Adjustable LED desk lamp with 3 color modes and USB charging port. Perfect for your workspace.', 4.2, 0, NULL, 15],
            ['Ceramic Coffee Mug', 'home', 14.99, 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?w=400&q=80', 'Handcrafted ceramic coffee mug with double-wall insulation to keep drinks warm longer.', 4.8, 0, NULL, 100],
            ['Bestseller Novel', 'books', 19.99, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&q=80', 'An award-winning novel that captures hearts worldwide with its gripping story and memorable characters.', 4.9, 0, 'Top Rated', 200],
            ['Self-Help Book', 'books', 15.50, 'https://images.unsplash.com/photo-1589998059171-988d887df646?w=400&q=80', 'Transform your life with practical advice and proven strategies for personal growth and success.', 4.4, 0, NULL, 150],
            ['Bluetooth Speaker', 'electronics', 59.99, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400&q=80', 'Portable Bluetooth speaker with 360° sound, waterproof design, and 12-hour playback time.', 4.5, 0, NULL, 60],
            ['Sunglasses Classic', 'fashion', 45.00, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&q=80', 'Timeless sunglasses with UV400 protection and lightweight frame for everyday elegance.', 4.1, 0, NULL, 80],
            ['Indoor Plant Pot', 'home', 24.99, 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=400&q=80', 'Modern ceramic pot with drainage hole, perfect for small to medium indoor plants.', 4.3, 0, NULL, 45],
            ['Cookbook Deluxe', 'books', 29.99, 'https://images.unsplash.com/photo-1589998059171-988d887df646?w=400&q=80', '200+ delicious recipes from around the world with beautiful photography and step-by-step guides.', 4.6, 0, NULL, 70]
        ];

        $sql = "INSERT INTO products (name, category, price, image, description, rating, featured, badge, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        foreach ($products as $product) {
            $stmt->execute($product);
        }
        echo "\nProducts seeded successfully!";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
