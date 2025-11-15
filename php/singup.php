<?php
session_start();
require_once 'config.php';

$errors = [];
$success_message = '';

if (!$pdo) {
    $errors[] = "Database connection failed. Please run setup_database.php first.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $repeat_password = $_POST['repeat-password'] ?? '';

    // Basic validations
    if (!$username) {
        $errors[] = "Username is required.";
    }
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if ($password !== $repeat_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $errors[] = "Username or email already taken. Please choose another.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
                $stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password_hash' => $password_hash,
                ]);
                $success_message = "Registration successful! You can now log in.";
            }
        } catch (PDOException $e) {
            $errors[] = "A database error occurred. Please try again later.";
            // Optionally log error:
            // error_log($e->getMessage());
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - AID-X: Connecting Hearts, Delivering Hope</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/responsive.css">
    
    <iframe src="../html/chatbot.html" class="chatbot-iframe"></iframe>
    
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
        
        .chatbot-iframe {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 600px;
            border: none;
            z-index: 9999;
            pointer-events: auto;
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

        <main class="relative z-30 flex-grow flex items-center justify-center p-4">
            <div class="w-full max-w-md mx-auto">
                <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl shadow-2xl border border-primary/50 relative z-40">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-white mb-2">Create Account</h1>
                        <p class="text-gray-300">Join the AID-X community</p>
                    </div>

                    <?php if (!empty($success_message)): ?>
                        <div class="bg-green-500/20 border border-green-500/50 text-green-200 p-4 rounded-lg mb-6">
                            <?= htmlspecialchars($success_message) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($errors)): ?>
                        <div class="bg-red-500/20 border border-red-500/50 text-red-200 p-4 rounded-lg mb-6">
                            <ul class="list-disc list-inside">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form id="signup-form" method="post" action="" class="space-y-6">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                            <input type="text" id="username" name="username" 
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent" 
                                   placeholder="Enter your username" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" 
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent" 
                                   placeholder="Enter your email address" required>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent" 
                                   placeholder="Enter your password" required>
                        </div>

                        <div>
                            <label for="repeat-password" class="block text-sm font-medium text-gray-300 mb-2">Repeat Password</label>
                            <input type="password" id="repeat-password" name="repeat-password" 
                                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent" 
                                   placeholder="Repeat your password" required>
                        </div>

                        <button type="submit" 
                                class="w-full bg-primary hover:bg-secondary text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-lg">
                            Sign Up
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-gray-300">
                            Already have an account? 
                            <a href="signin.php" class="text-secondary hover:text-accent font-semibold transition duration-200">Sign in</a>
                        </p>
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
