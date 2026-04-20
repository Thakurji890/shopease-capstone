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
        <p class="cart-item-price">$${item.price.toFixed(2)}</p>
        <div class="quantity-controls">
          <button class="qty-decrease" data-id="${item.id}">−</button>
          <span>${item.quantity}</span>
          <button class="qty-increase" data-id="${item.id}">+</button>
        </div>
      </div>
      <div class="cart-item-right">
        <p><strong>$${(item.price * item.quantity).toFixed(2)}</strong></p>
        <button class="remove-btn" data-id="${item.id}">Remove</button>
      </div>
    </div>
  `).join("");

  updateSummary(cart);
  attachCartListeners();
}

/* ============================================================
   UPDATE ORDER SUMMARY
============================================================ */
function updateSummary(cart) {
  const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
  const shipping = subtotal > 50 ? 0 : (subtotal > 0 ? 5.99 : 0);
  const total = subtotal + shipping;

  subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
  shippingEl.textContent = shipping === 0 ? "FREE" : `$${shipping.toFixed(2)}`;
  totalEl.textContent = `$${total.toFixed(2)}`;
}

/* ============================================================
   CART INTERACTIONS
============================================================ */
function attachCartListeners() {
  document.querySelectorAll(".qty-increase").forEach(btn => {
    btn.addEventListener("click", () => updateQuantity(+btn.dataset.id, 1));
  });

  document.querySelectorAll(".qty-decrease").forEach(btn => {
    btn.addEventListener("click", () => updateQuantity(+btn.dataset.id, -1));
  });

  document.querySelectorAll(".remove-btn").forEach(btn => {
    btn.addEventListener("click", () => removeFromCart(+btn.dataset.id));
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
   CHECKOUT MODAL
============================================================ */
if (checkoutBtn) {
  checkoutBtn.addEventListener("click", () => {
    checkoutModal.hidden = false;
  });
}

if (modalClose) {
  modalClose.addEventListener("click", () => {
    checkoutModal.hidden = true;
  });
}

// Close modal when clicking outside
if (checkoutModal) {
  checkoutModal.addEventListener("click", e => {
    if (e.target === checkoutModal) {
      checkoutModal.hidden = true;
    }
  });
}

/* ============================================================
   CHECKOUT FORM SUBMISSION
============================================================ */
if (checkoutForm) {
  checkoutForm.addEventListener("submit", e => {
    e.preventDefault();
    const name = document.getElementById("fullName").value.trim();

    // Clear cart
    localStorage.removeItem("cart");
    checkoutModal.hidden = true;

    // Show success
    alert(`🎉 Thank you, ${name}! Your order has been placed successfully.`);

    // Reset
    checkoutForm.reset();
    renderCart();
    updateCartCount();
  });
}

/* ============================================================
   INITIALIZE
============================================================ */
document.addEventListener("DOMContentLoaded", renderCart);