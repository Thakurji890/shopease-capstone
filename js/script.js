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
function getCart() {
  return JSON.parse(localStorage.getItem("cart")) || [];
}

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
    toast.style.cssText = `
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: #2c7a7b;
      color: white;
      padding: 1rem 1.5rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      z-index: 9999;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.3s ease;
      font-weight: 500;
    `;
    document.body.appendChild(toast);
  }
  toast.textContent = message;
  toast.style.opacity = "1";
  toast.style.transform = "translateY(0)";

  setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateY(20px)";
  }, 2500);
}

/* ============================================================
   5. RENDER PRODUCT CARDS
============================================================ */
function createProductCard(product) {
  return `
    <article class="product-card fade-in">
      <img src="${product.image}" alt="${product.name}" class="product-image" loading="lazy">
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
   8. ATTACH ADD-TO-CART LISTENERS
============================================================ */
function attachAddToCartListeners() {
  document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
    btn.addEventListener("click", e => {
      const id = parseInt(e.target.dataset.id);
      addToCart(id);
    });
  });
}

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
   11. INITIALIZE ON LOAD
============================================================ */
document.addEventListener("DOMContentLoaded", () => {
  updateCartCount();
});