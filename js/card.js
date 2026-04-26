// ==========================================================
// SHOPEASE - CART PAGE SCRIPT
// ==========================================================

const cartItemsEl = document.getElementById("cartItems");
const emptyCartEl = document.getElementById("emptyCart");
const subtotalEl = document.getElementById("subtotal");
const shippingEl = document.getElementById("shipping");
const totalEl = document.getElementById("total");
const checkoutBtn = document.getElementById("checkoutBtn");
const checkoutModal = document.getElementById("checkoutModal");
const checkoutForm = document.getElementById("checkoutForm");
const modalClose = document.querySelector(".modal-close");

/* ============================================================
   RENDER CART ITEMS
============================================================ */
function renderCart() {
  const cart = getCart();

  if (!cartItemsEl) return;

  if (cart.length === 0) {
    cartItemsEl.innerHTML = "";
    emptyCartEl.hidden = false;
    document.querySelector(".cart-wrapper").style.display = "none";
    return;
  }

  emptyCartEl.hidden = true;
  document.querySelector(".cart-wrapper").style.display = "grid";

  cartItemsEl.innerHTML = cart.map(item => `
    <div class="cart-item" data-id="${item.id}">
      <img src="${item.image}" alt="${item.name}" loading="lazy">
      <div class="cart-item-details">
        <h4>${item.name}</h4>
        <p class="cart-item-price">$${parseFloat(item.price).toFixed(2)}</p>
        <div class="quantity-controls">
          <button class="qty-decrease" data-id="${item.id}">−</button>
          <span>${item.quantity}</span>
          <button class="qty-increase" data-id="${item.id}">+</button>
        </div>
      </div>
      <div class="cart-item-right">
        <p><strong>$${(parseFloat(item.price) * item.quantity).toFixed(2)}</strong></p>
        <button class="remove-btn" data-id="${item.id}">Remove</button>
      </div>
    </div>
  `).join("");

  updateSummary(cart);
}

/* ============================================================
   UPDATE ORDER SUMMARY
============================================================ */
function updateSummary(cart) {
  const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
  const shipping = subtotal > 50 ? 0 : (subtotal > 0 ? 5.99 : 0);
  const total = subtotal + shipping;

  subtotalEl.textContent = `$${parseFloat(subtotal).toFixed(2)}`;
  shippingEl.textContent = shipping === 0 ? "FREE" : `$${parseFloat(shipping).toFixed(2)}`;
  totalEl.textContent = `$${parseFloat(total).toFixed(2)}`;
}

/* ============================================================
   CART INTERACTIONS
============================================================ */
/* ============================================================
   CART INTERACTIONS (Event Delegation)
============================================================ */
function initCartEventListeners() {
  document.addEventListener("click", e => {
    const target = e.target;
    const id = parseInt(target.dataset.id);

    if (target.classList.contains("qty-increase")) {
      updateQuantity(id, 1);
    } 
    else if (target.classList.contains("qty-decrease")) {
      updateQuantity(id, -1);
    } 
    else if (target.classList.contains("remove-btn")) {
      removeFromCart(id);
    }
  });
}

function updateQuantity(id, change) {
  let cart = getCart();
  const item = cart.find(i => i.id === id);
  if (!item) return;

  item.quantity += change;
  if (item.quantity <= 0) {
    cart = cart.filter(i => i.id !== id);
  }

  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
  updateCartCount();
}

function removeFromCart(id) {
  let cart = getCart();
  cart = cart.filter(i => i.id !== id);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
  updateCartCount();
  showToast("🗑️ Item removed");
}

/* ============================================================
   CHECKOUT MODAL & FORM
============================================================ */
if (checkoutBtn) {
  checkoutBtn.addEventListener("click", () => {
    checkoutModal.hidden = false;
  });
}

// Close modal logic
document.addEventListener("click", e => {
  if (e.target.classList.contains("modal-close") || e.target === checkoutModal) {
    if (checkoutModal) checkoutModal.hidden = true;
  }
});

if (checkoutForm) {
  checkoutForm.addEventListener("submit", async e => {
    e.preventDefault();
    const cart = getCart();
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const shipping = subtotal > 50 ? 0 : 5.99;
    const totalAmount = subtotal + shipping;

    try {
      const response = await fetch('api/place_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart, total_amount: totalAmount })
      });

      const data = await response.json();

      if (response.ok) {
        localStorage.removeItem("cart");
        checkoutModal.hidden = true;
        showToast("🎉 Order placed successfully!");
        
        // UI Reset
        checkoutForm.reset();
        renderCart();
        updateCartCount();
        
        // Optional: Redirect to a success state or home
        setTimeout(() => window.location.href = 'index.php', 2000);
      } else {
        showToast("❌ " + (data.error || "Checkout failed"));
        if (response.status === 401) setTimeout(() => window.location.href = 'login.php', 1500);
      }
    } catch (error) {
      showToast("❌ Connection error");
      console.error(error);
    }
  });
}


/* ============================================================
   INITIALIZE
============================================================ */
document.addEventListener("DOMContentLoaded", () => {
  renderCart();
  initCartEventListeners();
});