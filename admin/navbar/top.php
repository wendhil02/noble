<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<!-- Tailwind + Alpine CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>[x-cloak] { display: none !important; }</style>

<!-- Navigation -->
<nav x-data="{ mobileOpen: false }" class="bg-white shadow-lg sticky top-0 z-50 text-black font-bold">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center py-4">
      <!-- Logo -->
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded overflow-hidden">
          <img src="../img/logo/logo.png" alt="Noble Home Logo" class="w-full h-full object-cover">
        </div>
        <span class="text-2xl font-bold text-orange-500">Admin Panel</span>
      </div>

      <!-- Desktop Links -->
      <div class="hidden md:flex space-x-6 items-center">
        <a href="../client/dashboard" class="<?= $current_page == '../client/dashboard' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Dashboard</a>

        <!-- Products Dropdown -->
        <div x-data="{ open: false, selected: null }" class="relative">
          <button @click="open = !open" class="text-gray-700 hover:text-orange-500 transition">Products</button>

          <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute left-0 mt-2 bg-white shadow-lg rounded-lg flex w-[500px] z-50">
            <!-- Main Categories -->
            <div class="w-1/2 border-r p-4 space-y-2">
              <button @mouseenter="selected = 'materials'" class="block w-full text-left hover:text-orange-500">Edit & Update Materials</button>

                <button @mouseenter="selected = 'order'" class="block w-full text-left hover:text-orange-500">Order & Products</button>
            </div>


            <!-- Subpanel -->
            <div class="w-1/2 p-4">
              <template x-if="selected === 'materials'">
                <div>
                  <a href="../shop/adminshop.php" class="block hover:text-orange-500">Upload Product</a>
                  <a href="../shop/adminupdateshop.php" class="block hover:text-orange-500">Update Product</a>
                </div>
              </template>

              <template x-if="selected === 'order'">
                <div>
                  <a href="../orders/orders" class="block hover:text-orange-500">Order List</a>
                  <a href="../qrcodeperproduct/qrcodeitem" class="block hover:text-orange-500">Product List</a>
                </div>
              </template>
            </div>
          </div>
        </div>

        <a href="../addclient/insertclient" class="<?= $current_page == '../addclient/insertclient' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Client Manage</a>
        <a href="contact.php" class="<?= $current_page == 'contact.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Inquire Manage</a>
        <a href="quote.php" class="<?= $current_page == 'quote.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Transaction Manage</a>
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
      <a href="index.php" class="<?= $current_page == 'index.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">Home</a>

      <!-- Mobile Products Dropdown -->
      <div x-data="{ open: false }">
        <button @click="open = !open" class="block w-full text-left text-gray-700 hover:text-orange-500 transition">Products</button>
        <div x-show="open" x-transition x-cloak class="pl-4 space-y-1">
          <a href="products/wpc.php" class="block hover:text-orange-500">WPC Fluted</a>
          <a href="products/pvc.php" class="block hover:text-orange-500">PVC Panels</a>
          <a href="products/ceiling-flat.php" class="block hover:text-orange-500">Flat Ceiling</a>
          <a href="products/deck-composite.php" class="block hover:text-orange-500">Composite Decking</a>
        </div>
      </div>

      <a href="about.php" class="<?= $current_page == 'about.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">About</a>
      <a href="contact.php" class="<?= $current_page == 'contact.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">Contact</a>
      <a href="quote.php" class="<?= $current_page == 'quote.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">Get Free Quote</a>
    </div>
  </div>
</nav>
