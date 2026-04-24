<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEase Admin | Manage Orders</title>
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
        .table-card { background: var(--bg-card); border-radius: 15px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); overflow: hidden; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        .table th { background: var(--bg-alt); color: var(--text-light); font-weight: 600; }
        .status-badge { padding: 5px 10px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .status-pending { background: #fefcbf; color: #b7791f; }
        .status-completed { background: #c6f6d5; color: #2f855a; }
        .status-cancelled { background: #fed7d7; color: #c53030; }
    </style>
</head>
<body data-theme="dark">
    <div class="admin-layout">
        <aside class="sidebar">
            <a href="index.php" class="sidebar-logo">🛒 ShopEase Admin</a>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php">📊 Dashboard</a></li>
                    <li><a href="products.php">📦 Products</a></li>
                    <li><a href="orders.php" class="active">📜 Orders</a></li>
                    <li><a href="users.php">👥 Users</a></li>
                    <li><a href="../index.html">🏠 View Site</a></li>
                    <li><a href="../api/logout.php" style="color: #e53e3e;">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Manage Orders</h1>
            </header>

            <div class="table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Email</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        async function fetchAdminOrders() {
            try {
                const response = await fetch('../api/get_orders.php');
                const orders = await response.json();
                document.getElementById('orderTableBody').innerHTML = orders.map(o => `
                    <tr>
                        <td>#ORD-${o.id}</td>
                        <td>${o.email}</td>
                        <td>$${parseFloat(o.total_amount).toFixed(2)}</td>
                        <td><span class="status-badge status-${o.status}">${o.status}</span></td>
                        <td>${new Date(o.created_at).toLocaleDateString()}</td>
                        <td>
                            <select onchange="updateOrderStatus(${o.id}, this.value)">
                                <option value="pending" ${o.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="completed" ${o.status === 'completed' ? 'selected' : ''}>Completed</option>
                                <option value="cancelled" ${o.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                            </select>
                        </td>
                    </tr>
                `).join('');
            } catch (error) {
                console.error('Error fetching orders:', error);
            }
        }

        async function updateOrderStatus(id, status) {
            try {
                const response = await fetch('../api/update_order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, status })
                });
                if (response.ok) fetchAdminOrders();
            } catch (error) {
                console.error('Error updating order:', error);
            }
        }

        fetchAdminOrders();
    </script>
</body>
</html>
