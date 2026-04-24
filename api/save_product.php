<?php
// api/save_product.php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name']) || !isset($data['price'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

try {
    if (!empty($data['id'])) {
        // Update
        $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, price=?, image=?, stock_quantity=? WHERE id=?");
        $stmt->execute([$data['name'], $data['category'], $data['price'], $data['image'], $data['stock_quantity'], $data['id']]);
        echo json_encode(["message" => "Product updated successfully"]);
    } else {
        // Add
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, image, stock_quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['category'], $data['price'], $data['image'], $data['stock_quantity']]);
        echo json_encode(["message" => "Product added successfully"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
