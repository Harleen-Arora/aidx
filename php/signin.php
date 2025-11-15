<?php
session_start();
require_once 'config.php';

$errors = [];

if (!$pdo) {
    $errors[] = "Database connection failed. Please run setup_database.php first.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username) {
        $errors[] = "Please enter your username or phone number.";
    }
    if (!$password) {
        $errors[] = "Please enter your password.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id, role, name, password_hash FROM users WHERE phone = :user OR email = :user");
            $stmt->execute([':user' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                header("Location: ../html/dashboard.html");
                exit;
            } else {
                $errors[] = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $errors[] = "An error occurred. Please try again later.";
        }
    }
}
?>

<?php if (!empty($success_message)): ?>
    <div class="text-green-400 mb-4"><?= htmlspecialchars($success_message) ?></div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="text-red-400 mb-4">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - AID-X: Connecting Hearts, Delivering Hope</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    

    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#00b4d8',
                        'secondary': '#48cae4',
                        'accent': '#90e0ef',
                        'background': '#003049'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    backgroundImage: {
                        'hero-pattern': "url('https://images.unsplash.com/photo-1543269865-cbe426643c99?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')",
                    }
                }
            }
        }
    </script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        
        .hero-background {
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .hamburger-menu {
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }
        
        .hamburger-line {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 2px;
            display: block;
        }
        
        @media (max-width: 767px) {
            .hamburger-menu {
                display: flex !important;
            }
        }
        
        .hamburger-menu.active .hamburger-line:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }
        
        .hamburger-menu.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger-menu.active .hamburger-line:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
        

        
        input, textarea, select {
            pointer-events: auto !important;
            position: relative;
            z-index: 50 !important;
        }
        
        form {
            pointer-events: auto !important;
            position: relative;
            z-index: 50 !important;
        }

    </style>
</head>
<body class="font-sans min-h-screen bg-gray-50">

    <!-- Language Selector -->
    <div class="language-selector">
        <select onchange="changeLanguage(this.value)">
            <option value="en">🇺🇸 EN</option>
            <option value="hi">🇮🇳 हि</option>
        </select>
    </div>

    <div class="hero-background bg-hero-pattern min-h-screen flex flex-col relative">
        <div class="absolute inset-0 bg-background opacity-80 backdrop-blur-sm pointer-events-none"></div>

        <header class="relative z-10 bg-white/10 backdrop-blur-md border-b border-white/20">
            <nav class="flex justify-between items-center max-w-7xl mx-auto px-6 py-4">
                <div class="text-2xl md:text-3xl font-extrabold text-white tracking-wider flex items-center">
                    <svg class="w-6 h-6 md:w-8 md:h-8 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    AID-<span class="text-secondary">X</span>
                </div>

                <div class="flex items-center space-x-2 lg:hidden">
                    <button class="p-2 bg-white bg-opacity-20 rounded-lg" onclick="toggleMobileMenu()" aria-label="Toggle menu">
                        <div class="hamburger-menu">
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                        </div>
                    </button>
                </div>
                
                <div class="hidden lg:flex items-center space-x-4 xl:space-x-8">
                    <a href="../index.html" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Home</a>
                    <a href="#about" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">About</a>
                    <a href="#services" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Services</a>
                    <a href="#contact" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Contact</a>
                    <a href="signin.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Login</a>
                    <a href="singup.php" class="bg-primary hover:bg-secondary text-white px-4 py-2 xl:px-6 xl:py-2 rounded-full transition duration-200 font-medium text-sm xl:text-base">Sign Up</a>
                </div>

            </nav>
        </header>
        
        <div id="mobile-nav" class="hidden lg:hidden relative z-20 bg-white/10 backdrop-blur-md border-b border-white/20">
            <div class="px-6 py-4 space-y-3">
                <a href="../index.html" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Home</a>
                <a href="#about" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">About</a>
                <a href="#services" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Services</a>
                <a href="#contact" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Contact</a>
                <a href="signin.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Login</a>
                <a href="singup.php" class="block px-3 py-2 bg-primary hover:bg-secondary text-white rounded-lg transition duration-200 font-medium text-center">Sign Up</a>
            </div>
        </div>

        <main class="relative z-30 flex-grow flex items-center justify-center">
            <div class="w-full max-w-6xl mx-auto grid lg:grid-cols-2 min-h-[80vh]">
                <!-- Left Side - Branding -->
                <div class="hidden lg:flex flex-col justify-center items-center p-12 bg-white/5 backdrop-blur-md rounded-l-3xl">
                    <div class="text-center">
                        <div class="mb-8">
                            <svg class="w-24 h-24 mx-auto text-secondary mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <h1 class="text-5xl font-bold text-white mb-4">AID-<span class="text-secondary">X</span></h1>
                            <p class="text-xl text-gray-300 mb-8">Smart Humanitarian Support Platform</p>
                        </div>
                        <div class="space-y-4 text-left">
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-check-circle text-secondary mr-3"></i>
                                <span>Connect donors with verified beneficiaries</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-check-circle text-secondary mr-3"></i>
                                <span>Real-time tracking of relief operations</span>
                            </div>
                            <div class="flex items-center text-gray-300">
                                <i class="fas fa-check-circle text-secondary mr-3"></i>
                                <span>Transparent aid distribution</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="flex items-center justify-center p-8 lg:p-12 bg-white/10 backdrop-blur-md rounded-r-3xl lg:rounded-l-none rounded-3xl">
                    <div class="w-full max-w-md">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
                            <p class="text-gray-300">Sign in to your AID-X account</p>
                        </div>

                        <?php if (!empty($errors)): ?>
                            <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-lg mb-6">
                                <ul class="list-disc list-inside">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="" class="space-y-6">
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username or Email</label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" id="username" name="username" 
                                           class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent" 
                                           placeholder="Enter your username or email" required>
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                                <div class="relative">
                                    <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="password" id="password" name="password" 
                                           class="w-full pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent" 
                                           placeholder="Enter your password" required>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center text-sm text-gray-300">
                                    <input type="checkbox" name="remember" class="mr-2 rounded">
                                    Remember me
                                </label>
                                <a href="#" class="text-sm text-secondary hover:text-accent transition duration-200">Forgot password?</a>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-lg">
                                Sign In
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <p class="text-gray-300">
                                Don't have an account? 
                                <a href="singup.php" class="text-secondary hover:text-accent font-semibold transition duration-200">Sign up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleMobileMenu() {
            const mobileNav = document.getElementById('mobile-nav');
            const hamburger = document.querySelector('.hamburger-menu');
            
            mobileNav.classList.toggle('hidden');
            hamburger.classList.toggle('active');
        }
        
        function changeLanguage(lang) {
            // Language switching functionality
            console.log('Language changed to:', lang);
        }
    </script>
</body>

</html>