<?php
$pageTitle = 'Cart';
$currentPage = 'cart';
$extraScripts = ['card'];
include 'includes/header.php';
?>

<main>
    <section class="page-header">
      <div class="container">
        <h1>🛒 Your Shopping Cart</h1>
        <p>Review your items before checkout</p>
      </div>
    </section>

    <section class="cart-section">
      <div class="container">

        <div class="cart-wrapper">
          <div class="cart-items" id="cartItems"></div>

          <aside class="cart-summary" aria-label="Cart Summary">
            <h2>Order Summary</h2>
            <div class="summary-row">
              <span>Subtotal:</span><span id="subtotal">$0.00</span>
            </div>
            <div class="summary-row">
              <span>Shipping:</span><span id="shipping">$0.00</span>
            </div>
            <div class="summary-row total">
              <span>Total:</span><span id="total">$0.00</span>
            </div>
            <button class="btn btn-primary btn-block" id="checkoutBtn">Proceed to Checkout</button>
            <a href="products.php" class="btn btn-outline btn-block">Continue Shopping</a>
          </aside>
        </div>

        <div class="empty-cart" id="emptyCart" hidden>
          <h2>🛒 Your cart is empty</h2>
          <p>Looks like you haven't added anything yet.</p>
          <a href="products.php" class="btn btn-primary">Start Shopping</a>
        </div>

      </div>
    </section>

    <!-- Checkout Modal -->
    <div class="modal" id="checkoutModal" hidden>
      <div class="modal-content">
        <button class="modal-close" aria-label="Close modal">✕</button>
        <h2>🎉 Checkout</h2>
        <form id="checkoutForm">
          <div class="form-group">
            <label for="fullName">Full Name *</label>
            <input type="text" id="fullName" required>
          </div>
          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" required>
          </div>
          <div class="form-group">
            <label for="address">Shipping Address *</label>
            <textarea id="address" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Place Order</button>
        </form>
      </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
