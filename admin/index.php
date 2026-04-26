<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
include 'includes/header.php';
?>

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
    <div class="table-card">
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

<?php include 'includes/footer.php'; ?>

