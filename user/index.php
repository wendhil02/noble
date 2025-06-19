<?php
include '../connection/connect.php'; // Adjust path if needed

$query_variants1 = "SELECT id, type_id, color, size, price, percent, image FROM product_variants ORDER BY id DESC";
$result_variants = mysqli_query($conn, $query_variants1);
// Indoor Variants: Fetch unique products by codename
$SYCJ_query = "
    SELECT * FROM products
    WHERE codename = 'furniture'
    ORDER BY id DESC
";
$SYCJ_result = mysqli_query($conn, $SYCJ_query);

$material_query = "
    SELECT * FROM products
    WHERE codename = 'material'
    ORDER BY id DESC
";
$material_result = mysqli_query($conn, $material_query);




$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
$filter = mysqli_real_escape_string($conn, $_GET['filter'] ?? '');

// Furniture Query
$furniture_query = "SELECT * FROM products WHERE codename = 'furniture'";
if ($search) {
    $furniture_query .= " AND product_name LIKE '%$search%'";
}
if ($filter && $filter !== 'furniture') {
    // Skip furniture results if another filter is selected
    $furniture_query .= " AND 1=0";
}
$furniture_query .= " ORDER BY id DESC";
$SYCJ_result = mysqli_query($conn, $furniture_query);

// Material Query
$material_query = "SELECT * FROM products WHERE codename = 'material'";
if ($search) {
    $material_query .= " AND product_name LIKE '%$search%'";
}
if ($filter && $filter !== 'material') {
    $material_query .= " AND 1=0";
}
$material_query .= " ORDER BY id DESC";
$material_result = mysqli_query($conn, $material_query);

$discount_result = mysqli_query(
    $conn,
    "SELECT * FROM product_variants ORDER BY percent ASC"
);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noble Home - Modern Furnishing Supplies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com?plugins=aspect-ratio"></script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-bg {
            background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%),
                url('img/bodyimg/a.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, rgba(251, 146, 60, 0.1), rgba(251, 146, 60, 0.05));
            animation: float 6s ease-in-out infinite;
        }

        .floating-elements::before {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-elements::after {
            width: 200px;
            height: 200px;
            bottom: 10%;
            right: 10%;
            animation-delay: 3s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-glow {
            box-shadow: 0 0 30px rgba(251, 146, 60, 0.3);
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            box-shadow: 0 0 40px rgba(251, 146, 60, 0.5);
            transform: translateY(-2px);
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-900">

    <?php include 'navbar/top.php'; ?>


    <section class="w-full overflow-hidden bg-gray-100">
        <div
            x-data="{
            images: [
                'img/promo/1.png',
                'img/promo/a.png',
                'img/marine/marine1/c.png'
            ],
            current: 0,
            next() {
                this.current = (this.current + 1) % this.images.length;
            },
            prev() {
                this.current = (this.current - 1 + this.images.length) % this.images.length;
            }
        }"
            x-init="setInterval(() => next(), 3000)"
            class="relative w-full">

            <!-- Image Container with Slide -->
            <div class="w-full h-[300px] md:h-[500px] overflow-hidden relative">
                <div
                    class="flex transition-transform duration-700 ease-in-out"
                    :style="'transform: translateX(-' + (current * 100) + '%)'">
                    <template x-for="(img, index) in images" :key="index">
                        <img :src="img" class="w-full flex-shrink-0 object-contain h-[300px] md:h-[500px]">
                    </template>
                </div>
            </div>

            <!-- Prev Button -->
            <button @click="prev"
                class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-white/80 p-2 rounded-r-md hover:bg-white z-10">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Next Button -->
            <button @click="next"
                class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-white/80 p-2 rounded-l-md hover:bg-white z-10">
                <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </section>
    <section class="bg-gradient-to-r from-orange-500 to-red-500 text-white py-1 px-2 shadow-md">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">

            <!-- Discount Text -->
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 14l6-6M15 14l-6-6M9 10h6v4H9z" />
                </svg>
                <p class="text-lg font-semibold">
                    Not Available For Now <span class="underline font-bold"> Discount Banner</span>
                </p>
            </div>

            <!-- Action Button -->
            <a href="shop.php#discounts" class="bg-white text-orange-600 hover:bg-gray-100 font-semibold px-5 py-1 rounded-lg shadow transition">
                Shop Now
            </a>
        </div>
    </section>


    <section class="bg-white shadow-md py-4 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">

            <!-- Inquire Block -->
            <a href="inquire.php" class="flex-1 bg-orange-100 hover:bg-orange-200 transition rounded-lg p-4 text-center">
                <h3 class="text-lg font-semibold text-orange-700">📩 Inquire</h3>
                <p class="text-sm text-gray-700">Send us a question or message.</p>
            </a>

            <!-- Divider for mobile view -->
            <div class="hidden md:block w-px bg-gray-300 h-10 mx-4"></div>

            <!-- Appointment Block -->
            <a href="appointment.php" class="flex-1 bg-orange-100 hover:bg-orange-200 transition rounded-lg p-4 text-center">
                <h3 class="text-lg font-semibold text-orange-700">📅 Appointment</h3>
                <p class="text-sm text-gray-700">Book a consultation now.</p>
            </a>

        </div>
    </section>




    <!-- Products Section -->
    <section id="products" class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-4">Our Products</h2>
                <p class="text-white max-w-2xl mx-auto">Discover our wide range of quality materials and furniture for your home and construction needs.</p>
            </div>
            <form method="GET" action="#products" class="max-w-2xl mx-auto mb-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 font-semibold rounded">
                        Search
                    </button>
                    <!-- Search Input -->
                    <input
                        type="text"
                        name="search"
                        placeholder="Search products..."
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                        class="px-4 py-2 border rounded w-full" />

                    <!-- Filter Dropdown -->
                    <select name="filter" class="px-4 py-2 border rounded w-full">
                        <option value="">All Categories</option>
                        <option value="furniture" <?= ($_GET['filter'] ?? '') === 'furniture' ? 'selected' : '' ?>>Furniture</option>
                        <option value="material" <?= ($_GET['filter'] ?? '') === 'material' ? 'selected' : '' ?>>Material</option>
                    </select>


                </div>
            </form>

            <!-- Compact Discount Banner  -->
            <section class="py-8 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <h2 class="text-2xl font-bold text-orange-600 mb-6 text-center">
                        Not Available For Now Discounted Variants
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <?php while ($v = mysqli_fetch_assoc($discount_result)): ?>
                            <div class="bg-white rounded-lg shadow hover:shadow-md p-2 transition text-center">

                                <!-- Img (smaller) -->
                                <?php if (!empty($v['image'])): ?>
                                    <div class="aspect-[1/1] bg-gray-50 rounded mb-2 overflow-hidden">
                                        <img src="data:image/jpeg;base64,<?= base64_encode($v['image']) ?>"
                                            alt="Variant"
                                            class="w-full h-full object-contain" />
                                    </div>
                                <?php else: ?>
                                    <div class="aspect-[1/1] bg-gray-200 flex items-center justify-center text-xs text-gray-500 mb-2 rounded">
                                        No Image
                                    </div>
                                <?php endif; ?>

                                <!-- Info (tiny) -->
                                <h3 class="text-xs font-semibold text-gray-800 break-words">
                                    <?= htmlspecialchars($v['color']) ?> (<?= htmlspecialchars($v['size']) ?>)
                                </h3>
                                <p class="text-[11px] text-gray-600">₱<?= number_format($v['price'], 2) ?></p>
                                <span class="inline-block mt-1 text-[11px] px-2 py-[1px] bg-green-100 text-green-600 rounded">
                                    -<?= $v['percent'] ?>%
                                </span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>

            <section class="p-3">
                <div class="mb-12 mt-10">
                    <h2 class="text-4xl font-bold text-orange-500 mb-4">Furniture</h2>
                    <h6 class="text-white text-md">Explore our range of furniture products.</h6>
                    <div class="relative -bottom-2 left-0 w-15 h-1 bg-gradient-to-r from-orange-500 to-transparent rounded-full"></div>
                </div>


                <div class="swiper mySwiper-indoor">
                    <div class="swiper-wrapper">
                        <?php while ($row = mysqli_fetch_assoc($SYCJ_result)) : ?>
                            <div class="swiper-slide">
                                <a href="product_view.php?id=<?= (int)$row['id'] ?>" class="bg-gray-300 rounded-lg shadow p-2 group block text-center w-full">
                                    <!-- Image Container with Aspect Ratio -->
                                    <div class="w-full aspect-[4/5] mb-2">
                                        <?php if (!empty($row['main_image'])): ?>
                                            <img
                                                src="data:image/jpeg;base64,<?= base64_encode($row['main_image']) ?>"
                                                class="w-full h-full object-contain rounded group-hover:scale-105 transition-transform duration-300 mx-auto"
                                                alt="<?= htmlspecialchars($row['product_name']) ?>" />
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200 rounded text-gray-500 text-xs">No Image</div>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Product Name -->
                                    <h2 class="text-xs  bg-red-900 font-semibold text-white rounded-lg px-3 py-2 text-center break-words">
                                        <?= htmlspecialchars($row['product_name']) ?>
                                    </h2>

                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>


            <section class="p-3">
                <div class="mb-12 mt-10">
                    <h2 class="text-4xl font-bold text-orange-500 mb-4">Material</h2>
                    <div class="relative -bottom-2 left-0 w-15 h-1 bg-gradient-to-r from-orange-500 to-transparent rounded-full"></div>
                </div>

                <div class="swiper mySwiper-material">
                    <div class="swiper-wrapper">
                        <?php while ($row = mysqli_fetch_assoc($material_result)) : ?>
                            <div class="swiper-slide">
                                <a href="product_view.php?id=<?= (int)$row['id'] ?>" class="bg-white border rounded-lg shadow p-2 group block text-center w-full">
                                    <!-- Image Container with Aspect Ratio -->
                                    <div class="w-full aspect-[4/5] mb-2">
                                        <?php if (!empty($row['main_image'])): ?>
                                            <img
                                                src="data:image/jpeg;base64,<?= base64_encode($row['main_image']) ?>"
                                                class="w-full h-full object-contain rounded group-hover:scale-105 transition-transform duration-300 mx-auto"
                                                alt="<?= htmlspecialchars($row['product_name']) ?>" />
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200 rounded text-gray-500 text-xs">No Image</div>
                                        <?php endif; ?>
                                    </div>

                                    <h2 class="text-sm bg-red-900 font-semibold text-white rounded-lg p-3 break-words"><?= htmlspecialchars($row['product_name']) ?></h2>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-white mt-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center lg:flex-row-reverse">
                <!-- Image -->
                <div>
                    <img src="img/wpc.jpg" alt="About Noble Home" class="w-full h-96 object-contain rounded-lg shadow-md">
                </div>

                <!-- Text Content -->
                <div>
                    <h2 class="text-4xl font-bold text-orange-600 mb-6">NobleHome</h2>
                    <p class="text-black font-light mb-6">
                        Interested? Drop me a message — I'm here and ready to assist you every step of the way!<br><br>
                        <strong>Email:</strong> noblehomeconst.ph@gmail.com<br>
                        <strong>Viber:</strong> 0968 591 6536<br>
                        <strong>Located at:</strong> MC Premier - 1181 EDSA Balintawak, Quezon City 1008 Quezon City, Philippines (Main Office)
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section class="p-3">
        <div class="relative flex flex-col-reverse lg:flex-row items-center justify-between max-w-7xl mx-auto px-6 z-20">
            <!-- Left Side: Image -->
            <div class="w-full lg:w-1/2 flex justify-center mb-10 lg:mb-0">
                <img src="your-image.jpg" alt="Showcase" class="w-full max-w-md rounded-lg shadow-lg" />
            </div>

            <!-- Right Side: Text & Buttons -->
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <!-- Brand Badge -->

                <!-- Heading -->
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 text-shadow leading-tight">
                    MODERN <br>
                    <span class="gradient-text">FURNISHING</span><br>
                    SUPPLIES
                </h1>

                <!-- Subtitle -->
                <p class="text-xl md:text-2xl text-orange-100 mb-4 font-light leading-relaxed">
                    Decorate the dreams with <span class="text-orange-400 font-semibold">Noblehome</span>
                </p>

                <!-- Description -->
                <p class="text-lg text-white/80 mb-8">
                    Your trusted partner for quality materials and furniture. Building dreams, one piece at a time.
                </p>

                <!-- Buttons Side by Side -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <!-- Inquire Button with Modal -->
                    <div x-data="{ open: false }" class="text-center">
                        <button @click="open = true"
                            class="group bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-3 rounded-full font-bold text-lg btn-glow hover:from-orange-600 hover:to-orange-700 transition-all duration-300">
                            INQUIRE NOW!
                        </button>

                        <!-- Modal -->
                        <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 z-40" @click="open = false"></div>
                        <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative">
                                <button @click="open = false" class="absolute top-2 right-3 text-gray-600 hover:text-orange-600 text-2xl">&times;</button>
                                <iframe src="inquireform.php" class="w-full h-[500px] rounded" frameborder="0"></iframe>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>

    </section>
    <!-- About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">About <span class="text-orange-700">NobleHome</span></h2>
                    <p class="text-gray-600 mb-6">Welcome to Noble Home Corp. A leading provider of high-quality construction products. With years of experience, we pride ourselves on delivering top-notch products that transform spaces. Our goal is to offer solutions that enhance the functionality and aesthetic appeal of homes and commercial spaces alike.</p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-orange-600 mb-2">5000+</div>
                            <div class="text-gray-600">Happy Customers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-orange-600 mb-2">15+</div>
                            <div class="text-gray-600">Years Experience</div>
                        </div>
                    </div>
                </div>
                <!-- Image -->
                <div>
                    <img src="img/logo/logo.png" alt="About Noble Home" class="w-full h-96 object-contain rounded-lg shadow-md">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Contact Us</h2>
                <p class="text-gray-600">Get in touch with us for your construction and furniture needs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Address</h3>
                    <p class="text-gray-600 text-sm">MC Premier - 1181 EDSA Balintawak, Quezon City 1008 Quezon City,</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Phone</h3>
                    <p class="text-gray-600 text-sm">0968 591 6536</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Email</h3>
                    <p class="text-gray-600 text-sm">noblehomeconst.ph@gmail.com</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="gradient-bg pattern-bg text-white py-16 mt-12 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 via-orange-400 to-orange-500"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                <!-- Enhanced Branding Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-4 mb-6">
                        <!-- Logo with glow and pulse -->
                        <div class="relative">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-2xl glow-effect floating overflow-hidden">
                                <img src="img/logo/logo.png" alt="Noble Home Logo" class="w-10 h-10 object-cover">
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-400 rounded-full animate-pulse"></div>
                        </div>

                        <!-- Text Branding -->
                        <div>
                            <h2 class="text-3xl font-bold bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">Noble Home</h2>
                            <p class="text-orange-300 font-medium">Building quality homes since 2010</p>
                        </div>
                    </div>


                    <p class="text-gray-300 leading-relaxed mb-6 max-w-md">
                        Crafting exceptional living spaces with unmatched quality and attention to detail. Your dream home awaits with our expert construction and design services.
                    </p>

                    <!-- Contact Info -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 text-sm">
                            <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="m18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <span class="text-gray-300">noblehomeconst.ph@gmail.com</span>
                        </div>
                        <div class="flex items-center space-x-3 text-sm">
                            <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </div>
                            <span class="text-gray-300">0968 591 6536</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-6 text-white relative">
                        Quick Links
                        <div class="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-orange-500 to-transparent rounded-full"></div>
                    </h3>
                    <nav class="space-y-3">
                        <a href="index.php" class="block text-gray-300 hover:text-white link-hover transition-all duration-300 font-medium">Home</a>
                        <a href="about.php" class="block text-gray-300 hover:text-white link-hover transition-all duration-300 font-medium">About Us</a>
                        <a href="contact.php" class="block text-gray-300 hover:text-white link-hover transition-all duration-300 font-medium">Contact</a>
                        <a href="portfolio.php" class="block text-gray-300 hover:text-white link-hover transition-all duration-300 font-medium">Portfolio</a>
                        <a href="services.php" class="block text-gray-300 hover:text-white link-hover transition-all duration-300 font-medium">Services</a>
                    </nav>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="text-xl font-bold mb-6 text-white relative">
                        Our Services
                        <div class="absolute -bottom-2 left-0 w-12 h-1 bg-gradient-to-r from-orange-500 to-transparent rounded-full"></div>
                    </h3>
                    <ul class="space-y-3 text-gray-300">
                        <li class="hover:text-orange-300 transition-colors cursor-pointer">Appointment</li>
                        <li class="hover:text-orange-300 transition-colors cursor-pointer"></li>
                        <li class="hover:text-orange-300 transition-colors cursor-pointer"></li>
                        <li class="hover:text-orange-300 transition-colors cursor-pointer"></li>
                        <li class="hover:text-orange-300 transition-colors cursor-pointer"></li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="h-px bg-gradient-to-r from-transparent via-gray-600 to-transparent mb-8"></div>

            <!-- Bottom Section -->
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                <!-- Copyright -->
                <div class="text-center lg:text-left">
                    <p class="text-gray-400 text-sm">
                        © 2025 Noble Home Construction. All rights reserved.
                    </p>
                    <p class="text-gray-500 text-xs mt-1">
                        Licensed & Insured | PCAB License No. 12345
                    </p>
                </div>

                <!-- Enhanced Social Media -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-400 text-sm mr-2">Follow us:</span>

                    <a href="#" class="w-12 h-12 glass-effect rounded-xl flex items-center justify-center social-hover transition-all duration-300 group" aria-label="Facebook">
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22 12a10 10 0 10-11.63 9.88v-6.99H8.4v-2.89h1.97V9.91c0-1.95 1.16-3.03 2.93-3.03.85 0 1.74.15 1.74.15v1.91h-.98c-.97 0-1.27.6-1.27 1.21v1.45h2.16l-.35 2.89h-1.81v6.99A10 10 0 0022 12z" />
                        </svg>
                    </a>

                    <a href="#" class="w-12 h-12 glass-effect rounded-xl flex items-center justify-center social-hover transition-all duration-300 group" aria-label="Instagram">
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 2 .3 2.5.5.6.2 1 .6 1.5 1.1.4.4.8.9 1.1 1.5.2.5.4 1.3.5 2.5.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.3 2-.5 2.5-.2.6-.6 1-1.1 1.5-.4.4-.9.8-1.5 1.1-.5.2-1.3.4-2.5.5-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-2-.3-2.5-.5-.6-.2-1-.6-1.5-1.1-.4-.4-.8-.9-1.1-1.5-.2-.5-.4-1.3-.5-2.5C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.3-2 .5-2.5.2-.6.6-1 1.1-1.5.4-.4.9-.8 1.5-1.1.5-.2 1.3-.4 2.5-.5C8.4 2.2 8.8 2.2 12 2.2zm0 2.3c-3.1 0-3.5 0-4.7.1-.9.1-1.4.2-1.8.4-.5.2-.8.4-1.2.8s-.6.7-.8 1.2c-.2.4-.3.9-.4 1.8-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1.9.2 1.4.4 1.8.2.5.4.8.8 1.2.4.4.7.6 1.2.8.4.2.9.3 1.8.4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c.9-.1 1.4-.2 1.8-.4.5-.2.8-.4 1.2-.8s.6-.7.8-1.2c.2-.4.3-.9.4-1.8.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-.9-.2-1.4-.4-1.8-.2-.5-.4-.8-.8-1.2s-.7-.6-1.2-.8c-.4-.2-.9-.3-1.8-.4-1.2-.1-1.6-.1-4.7-.1zm0 3.7a5.8 5.8 0 100 11.6 5.8 5.8 0 000-11.6zm0 9.5a3.7 3.7 0 110-7.4 3.7 3.7 0 010 7.4zm5.9-9.8a1.3 1.3 0 11-2.6 0 1.3 1.3 0 012.6 0z" />
                        </svg>
                    </a>

                    <a href="#" class="w-12 h-12 glass-effect rounded-xl flex items-center justify-center social-hover transition-all duration-300 group" aria-label="LinkedIn">
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </a>
                </div>

                <!-- Back to Top Button -->
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="w-12 h-12 bg-orange-500 hover:bg-orange-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Background Pattern -->
        <div class="absolute bottom-0 right-0 opacity-5">
            <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
                <path d="M50 50h100v100H50z" stroke="currentColor" stroke-width="2" />
                <path d="M70 70h60v60H70z" stroke="currentColor" stroke-width="1" />
                <path d="M90 90h20v20H90z" stroke="currentColor" stroke-width="1" />
            </svg>
        </div>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.mySwiper-indoor', {
                slidesPerView: 2,
                spaceBetween: 15,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                loop: true,
                breakpoints: {
                    768: {
                        slidesPerView: 3
                    },
                    1024: {
                        slidesPerView: 4
                    },
                },
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.mySwiper-material', {
                slidesPerView: 2,
                spaceBetween: 15,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                loop: true,
                breakpoints: {
                    768: {
                        slidesPerView: 3
                    },
                    1024: {
                        slidesPerView: 4
                    },
                },
            });
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>