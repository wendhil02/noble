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
                                class="absolute top-2 right-2 text-gray-600 hover:text-orange-600 text-2xl">&times;</button>

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
                    <img src="img/marine/marine1/a.png" alt="Cement Bags" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">
                    <p class="text-orange-600 font-semibold text-sm">XX-23-070</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/b.png" alt="Steel Bars" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-091</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/c.png" alt="Dining Set" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-092</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/d.png" alt="Hollow Blocks" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-072</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/e.png" alt="Office Chair" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-071</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/f.png" alt="Paint Buckets" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-007</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/g.png" alt="Cement Bags" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-002</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/h.png" alt="Steel Bars" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-004</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/i.png" alt="Dining Set" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-052</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/j.png" alt="Hollow Blocks" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-054</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/k.png" alt="Office Chair" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-060</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 card-hover text-center">
                    <img src="img/marine/marine1/l.png" alt="Paint Buckets" class="w-16 h-16 object-cover rounded-lg mx-auto mb-3">

                    <p class="text-orange-600 font-semibold text-sm">XX-23-036</p>
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

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-3 mb-4 md:mb-0">
                    <div class="w-10 h-10 bg-orange-500 rounded flex items-center justify-center">
                        <span class="text-white font-bold text-xl">NH</span>
                    </div>
                    <span class="text-2xl font-bold">Noble Home</span>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-gray-400">&copy; 2025 Noble Home. All rights reserved.</p>
                    <p class="text-gray-400 text-sm">Building quality homes since 2010</p>
                </div>
            </div>
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