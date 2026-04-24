// ==========================================================
// SHOPEASE - PRODUCT DATA (Fetched from Backend)
// ==========================================================

async function fetchProducts(filters = {}) {
  const { category = 'all', search = '', featured = false } = filters;
  const url = new URL('api/get_products.php', window.location.origin + window.location.pathname.replace(/\/[^\/]*$/, '/'));
  
  if (category !== 'all') url.searchParams.append('category', category);
  if (search) url.searchParams.append('search', search);
  if (featured) url.searchParams.append('featured', '1');

  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error('Network response was not ok');
    return await response.json();
  } catch (error) {
    console.error('Error fetching products:', error);
    return [];
  }
}

// Keep a reference to the products for synchronous use cases if needed, 
// but initialize it from the API
let products = [];

async function initializeProducts() {
  products = await fetchProducts();
  // Dispatch an event so other scripts know products are loaded
  window.dispatchEvent(new CustomEvent('productsLoaded', { detail: products }));
}

initializeProducts();