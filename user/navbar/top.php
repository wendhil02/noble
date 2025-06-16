<!-- Tailwind + Alpine CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Navigation -->
<nav x-data="{ mobileOpen: false }" class="bg-white shadow-lg sticky top-0 z-50">
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
            <div class="hidden md:flex space-x-8">
                <a href="#home" class="text-gray-700 hover:text-orange-500 transition">Home</a>
                <a href="#products" class="text-gray-700 hover:text-orange-500 transition">Products</a>
                <a href="#about" class="text-gray-700 hover:text-orange-500 transition">About</a>
                <a href="#contact" class="text-gray-700 hover:text-orange-500 transition">Contact</a>
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
            <a href="#home" class="block text-gray-700 hover:text-orange-500 transition">Home</a>
            <a href="#products" class="block text-gray-700 hover:text-orange-500 transition">Products</a>
            <a href="#about" class="block text-gray-700 hover:text-orange-500 transition">About</a>
            <a href="#contact" class="block text-gray-700 hover:text-orange-500 transition">Contact</a>
        </div>
    </div>
</nav>
