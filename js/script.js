// ==========================================================
// SHOPEASE - MAIN SCRIPT
// ==========================================================

console.log("✅ ShopEase Loaded");

/* ============================================================
   1. DARK MODE TOGGLE
============================================================ */
const themeToggle = document.getElementById("themeToggle");

// Load saved theme
const savedTheme = localStorage.getItem("theme");
if (savedTheme === "dark") {
  document.documentElement.setAttribute("data-theme", "dark");
  if (themeToggle) themeToggle.textContent = "☀️";
}

if (themeToggle) {
  themeToggle.addEventListener("click", () => {
    const isDark = document.documentElement.getAttribute("data-theme") === "dark";
    if (isDark) {
      document.documentElement.removeAttribute("data-theme");
      themeToggle.textContent = "🌙";
      localStorage.setItem("theme", "light");
    } else {
      document.documentElement.setAttribute("data-theme", "dark");
      themeToggle.textContent = "☀️";
      localStorage.setItem("theme", "dark");
    }
  });
}

/* ============================================================
   2. MOBILE MENU TOGGLE
============================================================ */
const menuToggle = document.querySelector(".menu-toggle");
const navLinks = document.querySelector(".nav-links");

if (menuToggle && navLinks) {
  menuToggle.addEventListener("click", () => {
    navLinks.classList.toggle("active");
    const expanded = navLinks.classList.contains("active");
    menuToggle.setAttribute("aria-expanded", expanded);
    menuToggle.textContent = expanded ? "✕" : "☰";
  });

  // Close menu when link is clicked
  document.querySelectorAll(".nav-links a").forEach(link => {
    link.addEventListener("click", () => {
      navLinks.classList.remove("active");
      menuToggle.textContent = "☰";
    });
  });
}

/* ============================================================
   3. CART MANAGEMENT (LocalStorage)
============================================================ */
// Unified localStorage helper
const getStorage = key => JSON.parse(localStorage.getItem(key)) || [];
const getCart     = () => getStorage("cart");
const getWishlist = () => getStorage("wishlist");

function saveCart(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
  updateCartCount();
}

function updateCartCount() {
  const cart = getCart();
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  document.querySelectorAll(".cart-count").forEach(el => {
    el.textContent = totalItems;
  });
}

function addToCart(productId) {
  const cart = getCart();
  const product = products.find(p => p.id === productId);
  if (!product) return;

  const existing = cart.find(item => item.id === productId);
  if (existing) {
    existing.quantity += 1;
  } else {
    cart.push({ ...product, quantity: 1 });
  }

  saveCart(cart);
  showToast(`✅ ${product.name} added to cart!`);
}

/* ============================================================
   4. TOAST NOTIFICATION
============================================================ */
function showToast(message) {
  let toast = document.getElementById("toast");
  if (!toast) {
    toast = document.createElement("div");
    toast.id = "toast";
    toast.className = "toast";
    document.body.appendChild(toast);
  }
  toast.textContent = message;
  toast.classList.add("toast--visible");
  setTimeout(() => toast.classList.remove("toast--visible"), 2500);
}

/* ============================================================
   5. RENDER PRODUCT CARDS
============================================================ */
function createProductCard(product) {
  return `
    <article class="product-card fade-in" data-id="${product.id}">
      <div class="product-image-wrapper">
        ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
        <img src="${product.image}" alt="${product.name}" class="product-image" loading="lazy">
        <button class="wishlist-btn" data-id="${product.id}" aria-label="Add to wishlist">
          ${isInWishlist(product.id) ? "❤️" : "🤍"}
        </button>
        <button class="quick-view-btn" data-id="${product.id}">👁️ Quick View</button>
      </div>
      <div class="product-info">
        <span class="product-category">${product.category}</span>
        <h3 class="product-name">${product.name}</h3>
        <p class="product-price">$${product.price.toFixed(2)}</p>
        <button class="btn btn-primary add-to-cart-btn" data-id="${product.id}">
          Add to Cart 🛒
        </button>
      </div>
    </article>
  `;
}

/* ============================================================
   6. FEATURED PRODUCTS (HOME PAGE)
============================================================ */
const featuredGrid = document.getElementById("featuredProducts");

if (featuredGrid) {
  const featured = products.filter(p => p.featured);
  featuredGrid.innerHTML = featured.map(createProductCard).join("");
  attachAddToCartListeners();
}

/* ============================================================
   7. PRODUCTS PAGE - Render, Search, Filter, Sort
============================================================ */
const productsGrid = document.getElementById("productsGrid");
const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
const sortFilter = document.getElementById("sortFilter");
const noResults = document.getElementById("noResults");

function renderProducts(list) {
  if (!productsGrid) return;

  if (list.length === 0) {
    productsGrid.innerHTML = "";
    noResults.hidden = false;
    return;
  }

  noResults.hidden = true;
  productsGrid.innerHTML = list.map(createProductCard).join("");
  attachAddToCartListeners();
}

function filterAndSort() {
  let filtered = [...products];

  // Search
  const searchTerm = searchInput?.value.trim().toLowerCase();
  if (searchTerm) {
    filtered = filtered.filter(p =>
      p.name.toLowerCase().includes(searchTerm) ||
      p.category.toLowerCase().includes(searchTerm)
    );
  }

  // Category
  const category = categoryFilter?.value;
  if (category && category !== "all") {
    filtered = filtered.filter(p => p.category === category);
  }

  // Sort
  const sort = sortFilter?.value;
  if (sort === "low-high") filtered.sort((a, b) => a.price - b.price);
  else if (sort === "high-low") filtered.sort((a, b) => b.price - a.price);
  else if (sort === "name") filtered.sort((a, b) => a.name.localeCompare(b.name));

  renderProducts(filtered);
}

if (productsGrid) {
  renderProducts(products);
  searchInput?.addEventListener("input", filterAndSort);
  categoryFilter?.addEventListener("change", filterAndSort);
  sortFilter?.addEventListener("change", filterAndSort);
}

/* ============================================================
   8. ATTACH ADD-TO-CART LISTENERS  (defined fully in section 17)
============================================================ */

/* ============================================================
   9. CONTACT FORM VALIDATION
============================================================ */
const contactForm = document.getElementById("contactForm");
const successMsg = document.getElementById("successMsg");

if (contactForm) {
  contactForm.addEventListener("submit", e => {
    e.preventDefault();
    let valid = true;

    const fields = [
      { id: "name", msg: "Name must be at least 3 characters.", validate: v => v.trim().length >= 3 },
      { id: "contactEmail", msg: "Enter a valid email.", validate: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) },
      { id: "subject", msg: "Subject is required.", validate: v => v.trim().length >= 3 },
      { id: "message", msg: "Message must be at least 10 characters.", validate: v => v.trim().length >= 10 }
    ];

    fields.forEach(f => {
      const input = document.getElementById(f.id);
      const errorEl = input.parentElement.querySelector(".error-msg");
      if (!f.validate(input.value)) {
        errorEl.textContent = f.msg;
        input.style.borderColor = "#e53e3e";
        valid = false;
      } else {
        errorEl.textContent = "";
        input.style.borderColor = "";
      }
    });

    if (valid) {
      successMsg.hidden = false;
      contactForm.reset();
      setTimeout(() => { successMsg.hidden = true; }, 4000);
    }
  });
}

/* ============================================================
   10. NEWSLETTER FORM
============================================================ */
const newsletterForm = document.getElementById("newsletterForm");
if (newsletterForm) {
  newsletterForm.addEventListener("submit", e => {
    e.preventDefault();
    showToast("🎉 Subscribed successfully!");
    newsletterForm.reset();
  });
}

/* ============================================================
   11. INITIALIZE ON LOAD  (merged into section 18 below)
============================================================ */

/* ============================================================
   12. LOADING SPINNER
============================================================ */
window.addEventListener("load", () => {
  const loader = document.getElementById("loader");
  if (loader) {
    setTimeout(() => {
      loader.classList.add("hidden");
    }, 600);
  }
});

/* ============================================================
   13. BACK TO TOP BUTTON
============================================================ */
const backToTop = document.getElementById("backToTop");

if (backToTop) {
  window.addEventListener("scroll", () => {
    backToTop.classList.toggle("show", window.scrollY > 300);
  });

  backToTop.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}

/* ============================================================
   14. WISHLIST FEATURE
============================================================ */
function isInWishlist(id) {
  return getWishlist().includes(id);
}

function toggleWishlist(id) {
  let wishlist = getWishlist();
  if (wishlist.includes(id)) {
    wishlist = wishlist.filter(i => i !== id);
    showToast("💔 Removed from wishlist");
  } else {
    wishlist.push(id);
    showToast("❤️ Added to wishlist");
  }
  localStorage.setItem("wishlist", JSON.stringify(wishlist));
  updateWishlistCount();
}

function updateWishlistCount() {
  const count = getWishlist().length;
  document.querySelectorAll(".wishlist-count").forEach(el => {
    el.textContent = count;
  });
}

// Attach wishlist listeners (called after rendering products)
function attachWishlistListeners() {
  document.querySelectorAll(".wishlist-btn").forEach(btn => {
    btn.addEventListener("click", e => {
      e.stopPropagation();
      const id = parseInt(btn.dataset.id);
      toggleWishlist(id);
      btn.textContent = isInWishlist(id) ? "❤️" : "🤍";
      btn.classList.add("pulse");
      setTimeout(() => btn.classList.remove("pulse"), 400);
    });
  });
}

/* ============================================================
   15. PRODUCT QUICK VIEW MODAL
============================================================ */
const productModal = document.getElementById("productModal");
const productModalBody = document.getElementById("productModalBody");

function openProductModal(productId) {
  const product = products.find(p => p.id === productId);
  if (!product || !productModal) return;

  const stars = "★".repeat(Math.floor(product.rating)) + "☆".repeat(5 - Math.floor(product.rating));

  productModalBody.innerHTML = `
    <img src="${product.image}" alt="${product.name}">
    <div class="product-modal-info">
      <span class="product-category">${product.category}</span>
      <h2>${product.name}</h2>
      <div class="rating">${stars} <span style="color: var(--text-light); font-size: 0.9rem;">(${product.rating})</span></div>
      <p class="price">$${product.price.toFixed(2)}</p>
      <p class="description">${product.description}</p>
      <button class="btn btn-primary btn-block add-to-cart-btn" data-id="${product.id}">
        Add to Cart 🛒
      </button>
    </div>
  `;

  productModal.hidden = false;

  // Attach add-to-cart on modal button
  productModalBody.querySelector(".add-to-cart-btn").addEventListener("click", e => {
    addToCart(parseInt(e.target.dataset.id));
    productModal.hidden = true;
  });
}

function attachQuickViewListeners() {
  document.querySelectorAll(".quick-view-btn").forEach(btn => {
    btn.addEventListener("click", e => {
      e.stopPropagation();
      const id = parseInt(btn.dataset.id);
      openProductModal(id);
    });
  });
}

// Close product modal
if (productModal) {
  const closeBtn = productModal.querySelector(".modal-close");
  closeBtn?.addEventListener("click", () => productModal.hidden = true);

  productModal.addEventListener("click", e => {
    if (e.target === productModal) productModal.hidden = true;
  });
}

// ESC key closes modal
document.addEventListener("keydown", e => {
  if (e.key === "Escape") {
    if (productModal) productModal.hidden = true;
    const checkoutModal = document.getElementById("checkoutModal");
    if (checkoutModal) checkoutModal.hidden = true;
  }
});

/* ============================================================
   16. SCROLL REVEAL ANIMATION
============================================================ */
function initScrollReveal() {
  const reveals = document.querySelectorAll(".reveal, .feature-card, .product-card");
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("active");
      }
    });
  }, { threshold: 0.1 });

  reveals.forEach(el => {
    el.classList.add("reveal");
    observer.observe(el);
  });
}

/* ============================================================
   17. UPDATE attachAddToCartListeners to include new features
============================================================ */
// Override the original function
function attachAddToCartListeners() {
  document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
    btn.addEventListener("click", e => {
      e.stopPropagation();
      const id = parseInt(e.target.dataset.id);
      addToCart(id);
      e.target.classList.add("pulse");
      setTimeout(() => e.target.classList.remove("pulse"), 400);
    });
  });

  // Also attach wishlist and quick view
  attachWishlistListeners();
  attachQuickViewListeners();
}

/* ============================================================
   18. INITIALIZE ALL FEATURES (single DOMContentLoaded)
============================================================ */
document.addEventListener("DOMContentLoaded", () => {
  updateCartCount();
  updateWishlistCount();
  initScrollReveal();
  attachWishlistListeners();
  attachQuickViewListeners();
});