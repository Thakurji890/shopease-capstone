<?php
$pageTitle = 'Home';
$currentPage = 'home';
include 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero" aria-labelledby="hero-title">
      <div class="container hero-content">
        <h1 id="hero-title">Shop Smart. <span>Shop Easy.</span></h1>
        <p>Discover amazing products at unbeatable prices. Your perfect shopping experience starts here!</p>
        <a href="products.php" class="btn btn-primary">Shop Now →</a>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features" aria-labelledby="features-title">
      <div class="container">
        <h2 id="features-title" class="section-title">Why Choose Us?</h2>
        <div class="features-grid">
          <article class="feature-card">
            <div class="feature-icon">🚚</div>
            <h3>Free Shipping</h3>
            <p>Free delivery on orders over $50</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">🔒</div>
            <h3>Secure Payment</h3>
            <p>100% safe &amp; secure transactions</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">↩️</div>
            <h3>Easy Returns</h3>
            <p>30-day money back guarantee</p>
          </article>
          <article class="feature-card">
            <div class="feature-icon">💬</div>
            <h3>24/7 Support</h3>
            <p>We're here to help anytime</p>
          </article>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
      <div class="container">
        <div class="about-wrapper">
          <div class="about-content">
            <h2 class="section-title">About ShopEase</h2>
            <p>Welcome to <strong>ShopEase</strong> — your trusted online shopping destination!
            We believe shopping should be <strong>simple, fast, and enjoyable</strong>.
            Our mission is to bring you the best products at unbeatable prices, with
            excellent customer service every step of the way.</p>
            <div class="stats">
              <div class="stat-item"><h3>10K+</h3><p>Happy Customers</p></div>
              <div class="stat-item"><h3>500+</h3><p>Products</p></div>
              <div class="stat-item"><h3>24/7</h3><p>Support</p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products" aria-labelledby="featured-title">
      <div class="container">
        <h2 id="featured-title" class="section-title">⭐ Featured Products</h2>
        <div class="products-grid" id="featuredProducts">
          <!-- Products injected via JavaScript -->
        </div>
        <div class="text-center">
          <a href="products.php" class="btn btn-outline">View All Products</a>
        </div>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter" aria-labelledby="newsletter-title">
      <div class="container">
        <h2 id="newsletter-title">📧 Stay Updated</h2>
        <p>Subscribe to our newsletter for exclusive deals &amp; offers</p>
        <form class="newsletter-form" id="newsletterForm">
          <input type="email" placeholder="Enter your email" required aria-label="Email address">
          <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
      </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
