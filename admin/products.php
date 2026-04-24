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
    <title>ShopEase Admin | Manage Products</title>
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
        
        .product-table-card { background: var(--bg-card); border-radius: 15px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); overflow: hidden; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        .table th { background: var(--bg-alt); color: var(--text-light); font-weight: 600; }
        .product-img-sm { width: 40px; height: 40px; object-fit: cover; border-radius: 5px; }
        
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; }
        .btn-edit { background: #3182ce; color: white; }
        .btn-delete { background: #e53e3e; color: white; }
        
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
        .admin-modal { background: var(--bg-card); padding: 30px; border-radius: 15px; width: 500px; max-width: 90%; }
    </style>
</head>
<body data-theme="dark">
    <div class="admin-layout">
        <aside class="sidebar">
            <a href="index.php" class="sidebar-logo">🛒 ShopEase Admin</a>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php">📊 Dashboard</a></li>
                    <li><a href="products.php" class="active">📦 Products</a></li>
                    <li><a href="orders.php">📜 Orders</a></li>
                    <li><a href="users.php">👥 Users</a></li>
                    <li><a href="../index.html">🏠 View Site</a></li>
                    <li><a href="../api/logout.php" style="color: #e53e3e;">🚪 Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Manage Products</h1>
                <button class="btn btn-primary" onclick="openProductModal()">+ Add Product</button>
            </header>

            <div class="product-table-card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal-overlay" style="display: none;">
        <div class="admin-modal">
            <h2 id="modalTitle">Add New Product</h2>
            <form id="productForm">
                <input type="hidden" id="productId">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" id="pName" required>
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label>Category</label>
                        <select id="pCategory">
                            <option value="electronics">Electronics</option>
                            <option value="fashion">Fashion</option>
                            <option value="home">Home</option>
                            <option value="books">Books</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <label>Price ($)</label>
                        <input type="number" step="0.01" id="pPrice" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" id="pImage" required>
                </div>
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" id="pStock" value="10" required>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Save Product</button>
                    <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeProductModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/script.js"></script>
    <script>
        const productTableBody = document.getElementById('productTableBody');
        const productModal = document.getElementById('productModal');
        const productForm = document.getElementById('productForm');

        async function fetchAdminProducts() {
            try {
                const response = await fetch('../api/get_products.php');
                const products = await response.json();
                productTableBody.innerHTML = products.map(p => `
                    <tr>
                        <td><img src="${p.image}" class="product-img-sm"></td>
                        <td>${p.name}</td>
                        <td style="text-transform: capitalize;">${p.category}</td>
                        <td>$${parseFloat(p.price).toFixed(2)}</td>
                        <td>${p.stock_quantity || 0}</td>
                        <td>
                            <button class="btn btn-sm btn-edit" onclick='editProduct(${JSON.stringify(p).replace(/'/g, "&apos;")})'>Edit</button>
                            <button class="btn btn-sm btn-delete" onclick="deleteProduct(${p.id})">Delete</button>
                        </td>
                    </tr>
                `).join('');
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function openProductModal() {
            productForm.reset();
            document.getElementById('productId').value = '';
            document.getElementById('modalTitle').textContent = 'Add New Product';
            productModal.style.display = 'flex';
        }

        function closeProductModal() {
            productModal.style.display = 'none';
        }

        function editProduct(p) {
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productId').value = p.id;
            document.getElementById('pName').value = p.name;
            document.getElementById('pCategory').value = p.category;
            document.getElementById('pPrice').value = p.price;
            document.getElementById('pImage').value = p.image;
            document.getElementById('pStock').value = p.stock_quantity;
            productModal.style.display = 'flex';
        }

        productForm.onsubmit = async (e) => {
            e.preventDefault();
            const data = {
                id: document.getElementById('productId').value,
                name: document.getElementById('pName').value,
                category: document.getElementById('pCategory').value,
                price: document.getElementById('pPrice').value,
                image: document.getElementById('pImage').value,
                stock_quantity: document.getElementById('pStock').value
            };

            try {
                const response = await fetch('../api/save_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    closeProductModal();
                    fetchAdminProducts();
                }
            } catch (error) {
                console.error('Error saving product:', error);
            }
        };

        async function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;
            try {
                const response = await fetch('../api/delete_product.php?id=' + id, { method: 'DELETE' });
                if (response.ok) fetchAdminProducts();
            } catch (error) {
                console.error('Error deleting product:', error);
            }
        }

        fetchAdminProducts();
    </script>
</body>
</html>
