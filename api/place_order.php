<?php
// api/place_order.php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Please login to place an order"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$cart = $data['cart'] ?? [];
$totalAmount = $data['total_amount'] ?? 0;
$userId = $_SESSION['user_id'];

if (empty($cart)) {
    http_response_code(400);
    echo json_encode(["error" => "Cart is empty"]);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Create Order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$userId, $totalAmount]);
    $orderId = $pdo->lastInsertId();

    // 2. Create Order Items and Update Stock
    $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $updateStockStmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");

    foreach ($cart as $item) {
        $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
        $updateStockStmt->execute([$item['quantity'], $item['id']]);
    }

    $pdo->commit();
    echo json_encode(["success" => true, "order_id" => $orderId, "message" => "Order placed successfully!"]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["error" => "Failed to place order: " . $e->getMessage()]);
}
?>
