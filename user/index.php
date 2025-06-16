<?php
include '../connection/connect.php'; // Adjust path as needed

// Use description if image_name doesn't exist
$query = "SELECT description AS image_name, image_data FROM images ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noble Home - Modern Furnishing Supplies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
    <section id="home" class="relative flex items-center justify-center hero-bg overflow-hidden mt-20">
        <!-- Floating Background Elements -->
        <div class="floating-elements"></div>

        <!-- Animated Background Particles -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-1 bg-white rounded-full animate-ping"></div>
            <div class="absolute bottom-1/4 left-1/3 w-1.5 h-1.5 bg-orange-300 rounded-full animate-pulse"></div>
            <div class="absolute bottom-1/3 right-1/4 w-1 h-1 bg-white rounded-full animate-ping" style="animation-delay: 1s;"></div>
        </div>

        <!-- Main Content -->
        <div class="relative max-w-6xl mx-auto px-3 text-center z-20">
            <!-- Brand Badge -->
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-6 py-2 mb-8">
                <div class="w-8 h-8 bg-gradient-to-r from-orange-400 to-orange-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-sm">NH</span>
                </div>
                <span class="text-white/90 font-medium">Premium Quality Materials</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-6xl md:text-7xl lg:text-8xl font-black text-white mb-6 text-shadow leading-tight">
                MODERN
                <br>
                <span class="gradient-text">FURNISHING</span>
                <br>
                SUPPLIES
            </h1>

            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-orange-100 mb-4 max-w-3xl mx-auto font-light leading-relaxed">
                Decorate the dreams with <span class="text-orange-400 font-semibold">Noblehome</span>
            </p>

            <!-- Description -->
            <p class="text-lg text-white/80 mb-12 max-w-2xl mx-auto">
                Your trusted partner for quality materials and furniture. Building dreams, one piece at a time.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <!-- Modal trigger + logic -->
                <div x-data="{ open: false }" class="text-center">
                    <!-- Trigger Button -->
                    <button @click="open = true"
                        class="group bg-gradient-to-r from-orange-500 to-orange-600 text-white px-10 py-4 rounded-full font-bold text-lg btn-glow hover:from-orange-600 hover:to-orange-700 transition-all duration-300">
                        <span class="flex items-center gap-2 justify-center">
                            INQUIRE NOW!
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </span>
                    </button>

                    <!-- Modal Overlay -->
                    <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="open = false"></div>

                    <!-- Modal Content -->
                    <div x-show="open" x-cloak x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-auto">

                        <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 relative">
                            <!-- Close Button -->
                            <button @click="open = false"
                                class="absolute top-1 right-3 text-gray-600 hover:text-orange-600 text-2xl">&times;</button>

                            <!-- Iframe Content -->
                            <iframe src="inquireform.php" class="w-full h-[500px] rounded" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>


                <button class="group bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white/20 hover:border-white/50 transition-all duration-300">
                    Explore
                    <svg class="w-5 h-5 inline ml-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            <!-- Contact Info -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 text-white/70">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>noblehomeconst.ph@gmail.com</span>
                </div>

                <div class="hidden sm:block w-px h-4 bg-white/30"></div>

                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Philippines</span>
                </div>
            </div>
        </div>

        <a href="#products">
            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
                <div class="animate-bounce">
                    <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </div>
            </div>
        </a>
    </section>


    <!-- Products Section -->
    <section id="products" class="py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-4">Our Products</h2>
                <p class="text-white max-w-2xl mx-auto">Discover our wide range of quality materials and furniture for your home and construction needs.</p>
            </div>
            <!-- Product Categories -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-12 text-center">
                <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Construction Materials</h3>
                    <p class="text-gray-600 text-sm">Quality cement, steel, blocks, and building supplies</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Furniture</h3>
                    <p class="text-gray-600 text-sm">Stylish and durable furniture for every room</p>
                </div>
            </div>

            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-orange-500 mb-4">MARINE</h2>
            </div>

            <!-- Featured Products -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/a.png" alt="Cement Bags" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">
                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/b.png" alt="Steel Bars" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/c.png" alt="Dining Set" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/d.png" alt="Hollow Blocks" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/e.png" alt="Office Chair" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/f.png" alt="Paint Buckets" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/g.png" alt="Cement Bags" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/h.png" alt="Steel Bars" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/i.png" alt="Dining Set" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/a.png" alt="Hollow Blocks" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/b.png" alt="Office Chair" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/yero/c.png" alt="Paint Buckets" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">Not Available For Now</p>
                </div>
            </div>

            <section class="p-3">
                <div class="mb-12 mt-10">
                    <h2 class="text-4xl font-bold text-orange-500 mb-4">MARINE</h2>
                </div>

                <!-- Swiper Container -->
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <div class="swiper-slide bg-white rounded-lg shadow-md p-4 text-center">
                                <img src="data:image/jpeg;base64,<?= base64_encode($row['image_data']) ?>"
                                    alt="<?= htmlspecialchars($row['image_name']) ?>"
                                    class="w-16 h-16 object-cover mx-auto mb-3 rounded-lg" />
                                <p class="text-orange-600 font-semibold text-sm"><?= htmlspecialchars($row['image_name']) ?></p>
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
        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl flex items-center justify-center shadow-2xl glow-effect floating overflow-hidden">
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
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="m18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <span class="text-gray-300">noblehomeconst.ph@gmail.com</span>
                        </div>
                        <div class="flex items-center space-x-3 text-sm">
                            <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
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
                            <path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 2 .3 2.5.5.6.2 1 .6 1.5 1.1.4.4.8.9 1.1 1.5.2.5.4 1.3.5 2.5.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.3 2-.5 2.5-.2.6-.6 1-1.1 1.5-.4.4-.9.8-1.5 1.1-.5.2-1.3.4-2.5.5-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-2-.3-2.5-.5-.6-.2-1-.6-1.5-1.1-.4-.4-.8-.9-1.1-1.5-.2-.5-.4-1.3-.5-2.5C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.3-2 .5-2.5.2-.6.6-1 1.1-1.5.4-.4.9-.8 1.5-1.1.5-.2 1.3-.4 2.5-.5C8.4 2.2 8.8 2.2 12 2.2zm0 2.3c-3.1 0-3.5 0-4.7.1-.9.1-1.4.2-1.8.4-.5.2-.8.4-1.2.8s-.6.7-.8 1.2c-.2.4-.3.9-.4 1.8-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1.9.2 1.4.4 1.8.2.5.4.8.8 1.2.4.4.7.6 1.2.8.4.2.9.3 1.8.4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c.9-.1 1.4-.2 1.8-.4.5-.2.8-.4 1.2-.8s.6-.7.8-1.2c.2-.4.3-.9.4-1.8.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-.9-.2-1.4-.4-1.8-.2-.5-.4-.8-.8-1.2s-.7-.6-1.2-.8c-.4-.2-.9-.3-1.8-.4-1.2-.1-1.6-.1-4.7-.1zm0 3.7a5.8 5.8 0 100 11.6 5.8 5.8 0 000-11.6zm0 9.5a3.7 3.7 0 110-7.4 3.7 3.7 0 010 7.4zm5.9-9.8a1.3 1.3 0 11-2.6 0 1.3 1.3 0 012.6 0z"/>
                        </svg>
                    </a>
                    
                    <a href="#" class="w-12 h-12 glass-effect rounded-xl flex items-center justify-center social-hover transition-all duration-300 group" aria-label="LinkedIn">
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    
                   
                </div>
                
                <!-- Back to Top Button -->
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        class="w-12 h-12 bg-orange-500 hover:bg-orange-600 rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Background Pattern -->
        <div class="absolute bottom-0 right-0 opacity-5">
            <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
                <path d="M50 50h100v100H50z" stroke="currentColor" stroke-width="2"/>
                <path d="M70 70h60v60H70z" stroke="currentColor" stroke-width="1"/>
                <path d="M90 90h20v20H90z" stroke="currentColor" stroke-width="1"/>
            </svg>
        </div>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 2,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 6,
                },
            },
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