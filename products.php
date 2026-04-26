<?php
$pageTitle = 'Products';
$currentPage = 'products';
include 'includes/header.php';
?>

<main>
    <section class="page-header">
      <div class="container">
        <h1>🛍️ All Products</h1>
        <p>Explore our entire collection</p>
      </div>
    </section>

    <!-- Filters & Search -->
    <section class="filters-section">
      <div class="container">
        <div class="filters">
          <div class="search-box">
            <input type="text" id="searchInput" placeholder="🔍 Search products..." aria-label="Search products">
          </div>
          <div class="filter-group">
            <label for="categoryFilter">Category:</label>
            <select id="categoryFilter" aria-label="Filter by category">
              <option value="all">All</option>
              <option value="electronics">Electronics</option>
              <option value="fashion">Fashion</option>
              <option value="home">Home</option>
              <option value="books">Books</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="sortFilter">Sort By:</label>
            <select id="sortFilter" aria-label="Sort products">
              <option value="default">Default</option>
              <option value="low-high">Price: Low → High</option>
              <option value="high-low">Price: High → Low</option>
              <option value="name">Name (A-Z)</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
      <div class="container">
        <div class="products-grid" id="productsGrid"></div>
        <p id="noResults" class="no-results" hidden>😔 No products found. Try different keywords.</p>
      </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
