<?php
// api/debug_db.php
require_once 'config.php';

try {
    echo "<h1>Database Debug</h1>";
    
    // Check connection
    echo "✅ Connected to database.<br>";

    // Check products table
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    $count = $stmt->fetchColumn();
    echo "📊 Products in table: " . $count . "<br>";

    if ($count > 0) {
        $stmt = $pdo->query("SELECT id, name, category, price FROM products LIMIT 5");
        $products = $stmt->fetchAll();
        echo "<h3>Sample Products:</h3>";
        echo "<pre>";
        print_r($products);
        echo "</pre>";
    } else {
        echo "❌ No products found in table. Did you run setup_db.php?<br>";
    }

    // Check users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $uCount = $stmt->fetchColumn();
    echo "👥 Users in table: " . $uCount . "<br>";

} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage();
}
?>
