<?php
// api/get_stats.php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

try {
    $stats = [];
    
    // Total Products
    $stats['total_products'] = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    
    // Total Users
    $stats['total_users'] = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    // Total Orders
    $stats['total_orders'] = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    
    // Total Sales
    $stats['total_sales'] = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'")->fetchColumn() ?: 0;

    echo json_encode($stats);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch stats: " . $e->getMessage()]);
}
?>
