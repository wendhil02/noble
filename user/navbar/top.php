<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<!-- Tailwind + Alpine CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Navigation -->
<nav x-data="{ mobileOpen: false }" class="bg-white shadow-lg sticky top-0 z-50 text-black font-bold">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-between items-center py-4">
      <!-- Logo -->
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded overflow-hidden">
          <img src="img/logo/logo.png" alt="Noble Home Logo" class="w-full h-full object-cover">
        </div>
        <span class="text-2xl font-bold text-orange-500">NobleHome</span>
      </div>

      <!-- Desktop Links -->
      <div class="hidden md:flex space-x-6 items-center">
        <a href="index.php" class="<?= $current_page == 'index.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Home</a>

        <!-- Products Dropdown -->
        <div x-data="{ open: false, selected: null }" class="relative">
          <button @click="open = !open" class="text-gray-700 hover:text-orange-500 transition">
            Products
          </button>

          <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 bg-white shadow-lg rounded-lg flex w-[500px] z-50">
            <!-- Main Categories -->
            <div class="w-1/2 border-r p-4 space-y-2">
              <button @mouseenter="selected = 'materials'" class="block w-full text-left hover:text-orange-500">Construction Materials</button>
              <button @mouseenter="selected = 'furniture'" class="block w-full text-left hover:text-orange-500">Furniture</button>
              <button @mouseenter="selected = ''" class="block w-full text-left hover:text-orange-500">Furniture</button>
            </div>

            <!-- Subpanel -->
            <div class="w-1/2 p-4">
              <template x-if="selected === 'materials'">
                <div>
                  <p class="font-bold mb-2">Wall Panels</p>
                  <a href="products/wpc.php" class="block hover:text-orange-500">WPC Fluted</a>
                  <a href="products/pvc.php" class="block hover:text-orange-500">PVC Panels</a>
                </div>
              </template>

              <template x-if="selected === 'contruction'">
                <div>
                  <p class="font-bold mb-2">Ceiling Options</p>
                  <a href="products/ceiling-flat.php" class="block hover:text-orange-500">Flat Ceiling</a>
                  <a href="products/ceiling-wood.php" class="block hover:text-orange-500">Wood Pattern</a>
                </div>
              </template>

              <template x-if="selected === 'furniture'">
                <div>
                  <p class="font-bold mb-2">Decking Types</p>
                  <a href="products/deck-composite.php" class="block hover:text-orange-500">Composite Decking</a>
                  <a href="products/deck-plastic.php" class="block hover:text-orange-500">Plastic Wood</a>
                </div>
              </template>
            </div>
          </div>
        </div>

        <a href="about.php" class="<?= $current_page == 'about.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">About</a>
        <a href="contact.php" class="<?= $current_page == 'contact.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Contact</a>
        <a href="quote.php" class="<?= $current_page == 'quote.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Get Free Quote</a>
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
    <div x-show="mobileOpen" x-transition class="md:hidden space-y-2 pb-4">
      <a href="index.php" class="<?= $current_page == 'index.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">Home</a>

      <!-- Mobile Products Dropdown -->
      <div x-data="{ open: false }">
        <button @click="open = !open" class="block w-full text-left text-gray-700 hover:text-orange-500 transition">Products</button>
        <div x-show="open" x-transition class="pl-4 space-y-1">
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

<!-- Discount Banner -->
<div class="bg-gradient-to-r from-orange-400 to-orange-600 text-white text-center py-3 px-4 animate-bounce">
  <p class="text-lg font-semibold">
    Get up to <span class="underline font-bold">30% OFF</span> on all items! Limited time only!
    <a href="inquireform.php" class="ml-4 bg-white text-orange-600 px-3 py-1 rounded hover:bg-orange-100 font-bold">Inquire Now!</a>
  </p>
</div>
