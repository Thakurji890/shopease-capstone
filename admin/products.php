<?php
$pageTitle = 'Manage Products';
$activePage = 'products';
include 'includes/header.php';
?>

<header class="admin-header">
    <h1>Manage Products</h1>
    <button class="btn btn-primary" onclick="openProductModal()">+ Add Product</button>
</header>

<div class="table-card">
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
                    <td><img src="${p.image}" class="product-img-sm" style="width:40px; height:40px; object-fit:cover; border-radius:5px;"></td>
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

<?php include 'includes/footer.php'; ?>

