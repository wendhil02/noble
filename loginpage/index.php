<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Portal - Professional Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes subtlePulse {

            0%,
            100% {
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            }

            50% {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .logo-animation {
            animation: subtlePulse 4s ease-in-out infinite;
        }

        .grid-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.3) 1px, transparent 0);
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="min-h-screen bg-white flex items-center justify-center p-4 relative">

    <!-- Subtle Background Pattern -->
    <div class="absolute inset-0 grid-pattern opacity-30"></div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-md">

        <!-- Logo and Company Section -->
        <div class="text-center mb-8 fade-in-up">
            <div class="inline-flex items-center justify-center w-[130px] h-[60px] rounded mb-4 logo-animation">
                <img src="../user/img/logo/logo.png" alt="Logo" class="w-full h-full object-contain" />
            </div>

            <h1 class="text-2xl font-semibold text-gray-900 mb-1">Admin Portal</h1>
            <p class="text-gray-600">Secure access to your dashboard</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl card-shadow p-8 fade-in-up" style="animation-delay: 0.1s">

            <!-- Form Header -->
            <div class="mb-6">
                <h2 class="text-xl font-medium text-gray-900 mb-1">Sign in to your account</h2>
                <p class="text-sm text-gray-600">Please enter your credentials to continue</p>
            </div>

            <!-- Login Form -->
            <form class="space-y-5" id="loginForm">

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-mail-line text-gray-400"></i>
                        </div>
                        <input type="email" id="email"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 input-focus"
                            placeholder="john.doe@company.com" required />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="ri-check-line text-green-500 opacity-0 transition-opacity duration-200" id="emailCheck"></i>
                        </div>
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-lock-line text-gray-400"></i>
                        </div>
                        <input type="password" id="password"
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 input-focus"
                            placeholder="Enter your password" required />
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="togglePassword">
                            <i class="ri-eye-line text-gray-400 hover:text-gray-600 transition-colors duration-200" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full text-white py-3 px-4 rounded-lg font-medium btn-primary focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    id="submitBtn">
                    <span class="flex items-center justify-center" id="buttonContent">
                        <i class="ri-login-circle-line mr-2"></i>
                        Sign In
                    </span>
                </button>

                <!-- Security Notice -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-center text-xs text-gray-500">
                        <i class="ri-shield-check-line mr-1"></i>
                        <span>Secured with 256-bit SSL encryption</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-500 fade-in-up" style="animation-delay: 0.2s">
            <p>© 2025 Your Company. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Email validation
        const emailInput = document.getElementById('email');
        const emailCheck = document.getElementById('emailCheck');

        emailInput.addEventListener('input', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(this.value)) {
                emailCheck.style.opacity = '1';
                this.style.borderColor = '#10b981';
            } else {
                emailCheck.style.opacity = '0';
                this.style.borderColor = '#d1d5db';
            }
        });

        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.className = 'ri-eye-off-line text-gray-400 hover:text-gray-600 transition-colors duration-200';
            } else {
                eyeIcon.className = 'ri-eye-line text-gray-400 hover:text-gray-600 transition-colors duration-200';
            }
        });

        // Form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const buttonContent = document.getElementById('buttonContent');
            const originalContent = buttonContent.innerHTML;

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.style.transform = 'translateY(0)';

            buttonContent.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Signing in...
      `;

            // Simulate authentication
            setTimeout(() => {
                buttonContent.innerHTML = `
          <i class="ri-check-line mr-2"></i>
          Success!
        `;

                submitBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';

                setTimeout(() => {
                    // Reset state
                    submitBtn.disabled = false;
                    submitBtn.style.background = 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)';
                    buttonContent.innerHTML = originalContent;

                    alert('Login successful! Welcome to the administrative portal.');
                }, 1000);
            }, 2000);
        });

        // Input focus effects
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
                this.parentElement.style.transition = 'transform 0.2s ease-out';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</html>