<?php 
session_start();

$cart = $_SESSION['cart'] ?? [];
$total_cart_items = 0;

foreach ($cart as $item) {
  $total_cart_items += $item['quantity'];
}
?>

<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<!-- Tailwind + Alpine CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
  [x-cloak] {
    display: none !important;
  }

  .loading-spinner {
    border: 2px solid #f3f4f6;
    border-top: 2px solid #f97316;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>

<script>
  // Simple global loading functions
  function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
  }

  function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
  }

  function navigateWithLoading(url) {
    showLoading();

    // Navigate after short delay
    setTimeout(() => {
      window.location.href = url;
    }, 500);

    // Hide loading after 3 seconds as fallback (in case navigation fails)
    setTimeout(() => {
      hideLoading();
    }, 3000);
  }
</script>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none;"
  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]">
  <div class="bg-white rounded-lg p-8 flex items-center space-x-4 shadow-xl">
    <div class="loading-spinner"></div>
    <span class="text-gray-700 font-medium text-lg">Loading...</span>
  </div>
</div>

<div class="bg-gray-800 text-white py-2 text-sm">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
    <div class="flex items-center space-x-4">
      <span> Support: (02) 123-4567</span>
      <span> info@noblehome.com</span>
    </div>
    <div class="flex items-center space-x-4">
      <a href="javascript:void(0)" onclick="navigateWithLoading('help')"
        class="hover:text-orange-300 transition inline-flex items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Help
      </a>
      <a href="javascript:void(0)" onclick="navigateWithLoading('support')"
        class="hover:text-orange-300 transition inline-flex items-center gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5zM8.25 12l7.5 0" />
        </svg>
        Support
      </a>
    </div>
  </div>
</div>

<!-- Navigation -->
<nav x-data="{ mobileOpen: false }" class="bg-white shadow-lg sticky top-0 z-50 text-black font-bold">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center py-4">
      <!-- Logo -->
      <a href="javascript:void(0)" onclick="navigateWithLoading('index')"
        class="flex items-center space-x-3 hover:opacity-80 transition">
        <div class="w-10 h-10 rounded overflow-hidden">
          <img src="img/logo/logo.png" alt="Noble Home Logo" class="w-full h-full object-cover">
        </div>
        <div class="flex flex-col leading-tight">
          <span class="text-2xl font-bold text-orange-500">NobleHome</span>
          <span class="text-sm text-gray-600">Construction</span>
        </div>
      </a>

      <!-- Desktop Links -->
      <div class="hidden md:flex space-x-6 items-center">
        <a href="javascript:void(0)" onclick="navigateWithLoading('index')"
          class="<?= $current_page == 'index' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">
          Home
        </a>

        <!-- Products Dropdown -->
        <div x-data="{ open: false, selected: null }" class="relative">
          <button @click="open = !open" class="text-gray-700 hover:text-orange-500 transition">Products</button>

          <div x-show="open" @click.away="open = false" x-transition x-cloak
            class="absolute left-0 mt-2 bg-white shadow-lg rounded-lg flex w-[500px] z-50">
            <!-- Main Categories -->
            <div class="w-1/2 border-r p-4 space-y-2">
              <button @mouseenter="selected = 'materials'"
                class="block w-full text-left hover:text-orange-500">Construction Materials</button>
              <button @mouseenter="selected = 'furniture'"
                class="block w-full text-left hover:text-orange-500">Furniture</button>
            </div>

            <!-- Subpanel -->
            <div class="w-1/2 p-4">
              <template x-if="selected === 'materials'">
                <div>
                  <a href="javascript:void(0)" onclick="navigateWithLoading('')"
                    class="block hover:text-orange-500">temporary</a>
                  <a href="javascript:void(0)" onclick="navigateWithLoading('')"
                    class="block hover:text-orange-500">temporary</a>
                </div>
              </template>

              <template x-if="selected === 'furniture'">
                <div>
                  <a href="javascript:void(0)" onclick="navigateWithLoading('')"
                    class="block hover:text-orange-500">temporary</a>
                  <a href="javascript:void(0)" onclick="navigateWithLoading('')"
                    class="block hover:text-orange-500">temporary</a>
                </div>
              </template>
            </div>
          </div>
        </div>

        <a href="javascript:void(0)" onclick="navigateWithLoading('about')"
          class="<?= $current_page == 'about' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">
          About
        </a>

        <a href="javascript:void(0)" onclick="navigateWithLoading('contact')"
          class="<?= $current_page == 'contact' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">
          Contact
        </a>

        <a href="javascript:void(0)" onclick="navigateWithLoading('shop')"
          class="<?= $current_page == 'shop' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition inline-flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l1 11a1 1 0 001 1h14a1 1 0 001-1l1-11M4 9V7a1 1 0 011-1h14a1 1 0 011 1v2M9 22V12h6v10" />
          </svg>
          Shop
        </a>
<a href="javascript:void(0)" onclick="navigateWithLoading('cart_view')"
  class="<?= $current_page == 'cart/cart_view' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition inline-flex items-center gap-1 relative">

  <!-- Cart Icon -->
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.5h11.1M16 21a1 1 0 100-2 1 1 0 000 2zm-8 0a1 1 0 100-2 1 1 0 000 2z" />
  </svg>

  <!-- Text -->
  Cart

  <!-- 🔴 Notification Bubble -->
  <span id="cartCountBadge" class="absolute -top-2 -right-3 bg-red-500 text-white text-[10px] px-1 py-0.5 p-1 rounded-full font-bold leading-none <?= ($total_cart_items > 0 ? '' : 'hidden') ?>">
    <?= $total_cart_items ?>
  </span>
</a>


      </div>

      <!-- Mobile Hamburger -->
      <div class="md:hidden">
        <button @click="mobileOpen = !mobileOpen" class="text-gray-700 focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Dropdown -->
    <div x-show="mobileOpen" x-transition x-cloak class="md:hidden space-y-2 pb-4">
      <a href="javascript:void(0)" onclick="navigateWithLoading('index.php')"
        class="<?= $current_page == 'index.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">
        Home
      </a>

      <!-- Mobile Products Dropdown -->
      <div x-data="{ open: false }">
        <button @click="open = !open" class="block w-full text-left text-gray-700 hover:text-orange-500 transition">Products</button>
        <div x-show="open" x-transition x-cloak class="pl-4 space-y-1">
          <a href="javascript:void(0)" onclick="navigateWithLoading('products/wpc.php')"
            class="block hover:text-orange-500">WPC Fluted</a>
          <a href="javascript:void(0)" onclick="navigateWithLoading('products/pvc.php')"
            class="block hover:text-orange-500">PVC Panels</a>
          <a href="javascript:void(0)" onclick="navigateWithLoading('products/ceiling-flat.php')"
            class="block hover:text-orange-500">Flat Ceiling</a>
          <a href="javascript:void(0)" onclick="navigateWithLoading('products/deck-composite.php')"
            class="block hover:text-orange-500">Composite Decking</a>
        </div>
      </div>

      <a href="javascript:void(0)" onclick="navigateWithLoading('about.php')"
        class="<?= $current_page == 'about.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">
        About
      </a>
      <a href="javascript:void(0)" onclick="navigateWithLoading('contact.php')"
        class="<?= $current_page == 'contact.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">
        Contact
      </a>
      <a href="javascript:void(0)" onclick="navigateWithLoading('quote.php')"
        class="<?= $current_page == 'quote.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">
        Get Free Quote
      </a>
    </div>
  </div>
</nav>
