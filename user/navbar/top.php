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
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="<?= $current_page == 'index.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Home</a>
                <a href="#products" class="text-gray-700 hover:text-orange-500 transition">Products</a>
                <a href="about.php" class="<?= $current_page == 'about.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">About</a>
                <a href="contact.php" class="<?= $current_page == 'contact.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Contact</a>
                 <a href="quote.php" class="<?= $current_page == 'quote.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> hover:text-orange-500 transition">Get Free Quote</a>
            </div>

            <!-- Mobile Hamburger Button -->
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
            <a href="products" class="block text-gray-700 hover:text-orange-500 transition">Products</a>
            <a href="about.php" class="<?= $current_page == 'about.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">About</a>
            <a href="contact.php" class="<?= $current_page == 'contact.php' ? 'text-orange-600 underline font-bold' : 'text-gray-700' ?> block hover:text-orange-500 transition">Contact</a>
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
