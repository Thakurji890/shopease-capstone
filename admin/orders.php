<?php
$pageTitle = 'Manage Orders';
$activePage = 'orders';
include 'includes/header.php';
?>

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
                        <select onchange="updateOrderStatus(${o.id}, this.value)" style="padding: 5px; border-radius: 5px;">
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

<?php include 'includes/footer.php'; ?>

