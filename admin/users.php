<?php
$pageTitle = 'Manage Users';
$activePage = 'users';
include 'includes/header.php';
?>

<header class="admin-header">
    <h1>Manage Users</h1>
</header>

<div class="table-card">
    <table class="table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined Date</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            <!-- Loaded via JS -->
        </tbody>
    </table>
</div>

<script>
    async function fetchAdminUsers() {
        try {
            const response = await fetch('../api/get_users.php');
            const users = await response.json();
            document.getElementById('userTableBody').innerHTML = users.map(u => `
                <tr>
                    <td>#USR-${u.id}</td>
                    <td>${u.name}</td>
                    <td>${u.email}</td>
                    <td><span class="role-badge role-${u.role}">${u.role}</span></td>
                    <td>${new Date(u.created_at).toLocaleDateString()}</td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error fetching users:', error);
        }
    }

    fetchAdminUsers();
</script>

<?php include 'includes/footer.php'; ?>

