<?php
// admin/includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Helper for active class
function isActive($page, $activePage) {
    return $page === $activePage ? 'class="active"' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEase Admin | <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root { --sidebar-width: 250px; }
        .admin-layout { display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); background: var(--bg-card); border-right: 1px solid var(--border-color); padding: 30px 20px; }
        .sidebar-logo { font-size: 1.5rem; font-weight: 700; margin-bottom: 40px; display: block; text-decoration: none; color: var(--text-main); }
        .sidebar-nav ul { list-style: none; }
        .sidebar-nav li { margin-bottom: 15px; }
        .sidebar-nav a { display: block; padding: 12px 15px; border-radius: 10px; text-decoration: none; color: var(--text-light); transition: 0.3s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background: var(--primary-color); color: white; }
        .admin-content { flex: 1; padding: 40px; background: var(--bg-main); }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .table-card { background: var(--bg-card); border-radius: 15px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); overflow: hidden; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        .table th { background: var(--bg-alt); color: var(--text-light); font-weight: 600; }
        
        /* Badges & Buttons */
        .status-badge { padding: 5px 10px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .status-pending { background: #fefcbf; color: #b7791f; }
        .status-completed { background: #c6f6d5; color: #2f855a; }
        .status-cancelled { background: #fed7d7; color: #c53030; }
        .role-badge { padding: 4px 8px; border-radius: 5px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .role-admin { background: #e9d8fd; color: #553c9a; }
        .role-user { background: #e2e8f0; color: #4a5568; }
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; }
        .btn-edit { background: #3182ce; color: white; }
        .btn-delete { background: #e53e3e; color: white; }
    </style>
</head>
<body data-theme="dark">
    <div class="admin-layout">
        <aside class="sidebar">
            <a href="index.php" class="sidebar-logo">🛒 ShopEase Admin</a>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php" <?php echo isActive('dashboard', $activePage); ?>>📊 Dashboard</a></li>
                    <li><a href="products.php" <?php echo isActive('products', $activePage); ?>>📦 Products</a></li>
                    <li><a href="orders.php" <?php echo isActive('orders', $activePage); ?>>📜 Orders</a></li>
                    <li><a href="users.php" <?php echo isActive('users', $activePage); ?>>👥 Users</a></li>
                    <li><a href="../index.php">🏠 View Site</a></li>
                    <li><a href="../api/logout.php" style="color: #e53e3e;">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-content">
