// ==========================================================
// SHOPEASE - SHARED COMPONENTS (Header + Footer)
// Automatically injects header/footer so each page stays lean.
// Usage: <script src="js/components.js" data-page="home"></script>
// ==========================================================

(function () {
  const activePage = document.currentScript?.dataset.page || "";

  // ── NAV ITEM HELPER ───────────────────────────────────────
  function navItem(href, label, page, extra = "", id = "") {
    const isActive = activePage === page ? ' class="active"' : "";
    const idAttr = id ? ` id="${id}"` : "";
    return `<li${idAttr}><a href="${href}"${isActive}>${label}${extra}</a></li>`;
  }

  // ── INJECT LOADER + BACK-TO-TOP + HEADER ─────────────────
  document.body.insertAdjacentHTML("afterbegin", `
    <div class="loader" id="loader"><div class="spinner"></div></div>
    <button class="back-to-top" id="backToTop" aria-label="Back to top">↑</button>

    <header class="header">
      <div class="container nav-container">
        <a href="index.html" class="logo" aria-label="ShopEase Home">
          🛒 <span>ShopEase</span>
        </a>
        <nav class="navbar" aria-label="Main Navigation">
          <button class="menu-toggle" aria-label="Toggle Menu" aria-expanded="false">☰</button>
          <ul class="nav-links" id="mainNavLinks">
            ${navItem("index.html",    "Home",    "home")}
            ${navItem("products.html", "Products","products")}
            ${navItem("cart.html",     "Cart",    "cart", ' <span class="cart-count">0</span>')}
            ${navItem("contact.html",  "Contact", "contact")}
            ${navItem("login.html", "Login", "login", ' <span id="userStatus">👤</span>', 'authLink')}
            <li><button id="themeToggle" aria-label="Toggle Dark Mode">🌙</button></li>
          </ul>
        </nav>
      </div>
    </header>
  `);

  // ── DYNAMIC AUTH UPDATES ──────────────────────────────────
  async function updateNavbarAuth() {
    try {
      const response = await fetch('api/check_auth.php');
      const data = await response.json();
      const authLink = document.getElementById('authLink');
      const navLinks = document.getElementById('mainNavLinks');

      if (data.authenticated) {
        let adminLink = '';
        if (data.user.role === 'admin') {
          adminLink = navItem("admin/index.php", "Admin", "admin");
        }
        
        authLink.innerHTML = `
          <div class="user-dropdown">
            <span class="user-name">Hi, ${data.user.name.split(' ')[0]}</span>
            <ul class="dropdown-menu">
              ${adminLink}
              <li><a href="api/logout.php">Logout 🚪</a></li>
            </ul>
          </div>
        `;
      }
    } catch (e) { console.error("Auth check failed", e); }
  }

  updateNavbarAuth();

  // ── INJECT FOOTER ─────────────────────────────────────────
  document.body.insertAdjacentHTML("beforeend", `
    <footer class="footer">
      <div class="container footer-container">
        <div class="footer-col">
          <h3>🛒 ShopEase</h3>
          <p>Your trusted online shopping partner since 2026.</p>
        </div>
        <div class="footer-col">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="products.html">Products</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="contact.html">Contact</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Follow Us</h4>
          <div class="social-links">
            <a href="#" aria-label="Facebook">📘</a>
            <a href="#" aria-label="Twitter">🐦</a>
            <a href="#" aria-label="Instagram">📸</a>
            <a href="#" aria-label="LinkedIn">💼</a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2026 ShopEase by Manesh Thakur. All rights reserved. Built with 💚 during ApexPlanet Internship.</p>
      </div>
    </footer>
  `);
})();
