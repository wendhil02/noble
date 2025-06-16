<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noble Home Corp - About Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans"> 
    <?php include 'navbar/top.php'; ?>
    <section class="about-us max-w-6xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">About Us</h1>
            <div class="w-24 h-1 bg-blue-600 mx-auto"></div>
        </div>
        
        <!-- About Content -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-shrink-0">
                    <img src="img/logo/logo.png" alt="Noble Home Corp Logo" class="w-32 h-32 object-contain">
                </div>
                <div class="flex-1">
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Welcome to <span class="font-semibold text-blue-600">Noble Home Corp.</span>, a leading provider of high-quality construction products. With years of experience, we pride ourselves on delivering top-notch products that transform spaces. Our goal is to offer solutions that enhance the functionality and aesthetic appeal of homes and commercial spaces alike.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Mission & Vision Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Mission -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-bullseye text-blue-600 mr-3"></i>
                    Our Mission
                </h2>
                <ul class="space-y-6">
                    <li class="border-l-4 border-blue-600 pl-4">
                        <strong class="text-gray-800 block mb-2">For Our Buyers:</strong>
                        <p class="text-gray-600">We are committed to offering high-quality, aesthetically pleasing, and reasonably priced homes tailored to your needs and aspirations.</p>
                    </li>
                    <li class="border-l-4 border-green-600 pl-4">
                        <strong class="text-gray-800 block mb-2">For Our Employees & Agents:</strong>
                        <p class="text-gray-600">We provide a nurturing environment that encourages professional growth, personal development, and long-term financial success.</p>
                    </li>
                    <li class="border-l-4 border-purple-600 pl-4">
                        <strong class="text-gray-800 block mb-2">For Our Business Partners:</strong>
                        <p class="text-gray-600">We build long-lasting relationships grounded in trust, transparency, and mutual benefit.</p>
                    </li>
                </ul>
            </div>
            
            <!-- Vision -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-eye text-blue-600 mr-3"></i>
                    Our Vision
                </h2>
                <div class="space-y-4">
                    <p class="text-gray-700 leading-relaxed">
                        We envision a future where <span class="font-semibold text-blue-600">Noble Home Corp.</span> stands as the premier name in the construction product industry—recognized for exceptional quality, innovation, and customer trust.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        We aim to continuously exceed expectations by empowering our team, embracing innovation, and staying true to our values in all we do.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Core Values Section -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">
                Our Core Values
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Quality -->
                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-cogs text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Quality</h3>
                    <p class="text-gray-600 leading-relaxed">
                        We ensure the highest standards in every product we offer. Precision and craftsmanship define our work.
                    </p>
                </div>
                
                <!-- Integrity -->
                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-handshake text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Integrity</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Honesty and transparency are at the core of all our business practices. We build trust with our clients and partners.
                    </p>
                </div>
                
                <!-- Customer Focus -->
                <div class="text-center group hover:transform hover:scale-105 transition-all duration-300">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-users text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Customer Focus</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Your satisfaction is our priority. We aim to deliver exceptional service and solutions tailored to your needs.
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>