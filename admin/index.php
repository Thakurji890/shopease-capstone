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
    <title>ShopEase Admin | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-card);
            border-right: 1px solid var(--border-color);
            padding: 30px 20px;
        }
        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 40px;
            display: block;
            text-decoration: none;
            color: var(--text-main);
        }
        .sidebar-nav ul {
            list-style: none;
        }
        .sidebar-nav li {
            margin-bottom: 15px;
        }
        .sidebar-nav a {
            display: block;
            padding: 12px 15px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-light);
            transition: 0.3s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: var(--primary-color);
            color: white;
        }
        .admin-content {
            flex: 1;
            padding: 40px;
            background: var(--bg-main);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: var(--bg-card);
            padding: 25px;
            border-radius: 15px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }
        .stat-card h3 {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 10px;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body data-theme="dark">
    <div class="admin-layout">
        <aside class="sidebar">
            <a href="#" class="sidebar-logo">🛒 ShopEase Admin</a>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php" class="active">📊 Dashboard</a></li>
                    <li><a href="products.php">📦 Products</a></li>
                    <li><a href="orders.php">📜 Orders</a></li>
                    <li><a href="users.php">👥 Users</a></li>
                    <li><a href="../index.html">🏠 View Site</a></li>
                    <li><a href="../api/logout.php" style="color: #e53e3e;">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
                <div class="date" id="currentDate"></div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Sales</h3>
                    <div class="value" id="statSales">$0.00</div>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="value" id="statOrders">0</div>
                </div>
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <div class="value" id="statProducts">0</div>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="value" id="statUsers">0</div>
                </div>
            </div>

            <section class="recent-orders">
                <div class="section-header">
                    <h2>Recent Orders</h2>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="recentOrdersBody">
                            <!-- Injected via JS -->
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Update date
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });

        // Fetch Stats
        async function fetchStats() {
            try {
                const response = await fetch('../api/get_stats.php');
                const data = await response.json();
                
                document.getElementById('statSales').textContent = '$' + parseFloat(data.total_sales).toFixed(2);
                document.getElementById('statOrders').textContent = data.total_orders;
                document.getElementById('statProducts').textContent = data.total_products;
                document.getElementById('statUsers').textContent = data.total_users;
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        }

        fetchStats();
    </script>
</body>
</html>
