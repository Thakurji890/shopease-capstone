<?php
// api/get_products.php
require_once 'config.php';

$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$featured = isset($_GET['featured']) ? (bool)$_GET['featured'] : false;

try {
    $query = "SELECT * FROM products WHERE 1=1";
    $params = [];

    if ($category !== 'all') {
        $query .= " AND category = ?";
        $params[] = $category;
    }

    if (!empty($search)) {
        $query .= " AND (name LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($featured) {
        $query .= " AND featured = 1";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    echo json_encode($products);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch products: " . $e->getMessage()]);
}
?>
