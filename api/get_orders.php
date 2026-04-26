<?php
// api/get_orders.php
require_once 'config.php';
require_admin();

try {
    $stmt = $pdo->query("SELECT o.*, u.email FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
    $orders = $stmt->fetchAll();
    echo json_encode($orders);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
