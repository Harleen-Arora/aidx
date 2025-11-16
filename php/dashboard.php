<?php
session_start();

$user_name = $_SESSION['user_name'] ?? 'User';

// Fetch aid requests from database
try {
    $pdo = new PDO("mysql:host=localhost;dbname=aidx_db;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    $stmt = $pdo->prepare("SELECT * FROM aid_requests ORDER BY id DESC");
    $stmt->execute();
    $aid_requests = $stmt->fetchAll();
} catch (PDOException $e) {
    $aid_requests = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AID-X: Smart Giving Timely Living</title>
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
    </style>
</head>
<body class="font-sans min-h-screen bg-gray-50">

    <div class="hero-background bg-hero-pattern min-h-screen flex flex-col relative">
        <div class="absolute inset-0 bg-background opacity-80 backdrop-blur-sm pointer-events-none"></div>

        <header class="relative z-10 bg-white/10 backdrop-blur-md border-b border-white/20">
            <nav class="flex justify-between items-center max-w-7xl mx-auto px-6 py-4">
                <div class="text-2xl md:text-3xl font-extrabold text-white tracking-wider flex items-center">
                    <img src="../assets/WhatsApp%20Image%202025-11-15%20at%2021.33.16.jpeg" alt="AID-X Logo" class="w-6 h-6 md:w-8 md:h-8 mr-2 rounded-full object-cover">
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
                    <a href="home.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Home</a>
                    <a href="map.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Map</a>
                    <a href="request.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Request Aid</a>
                    <span class="text-white font-medium text-sm xl:text-base">Welcome, <?= htmlspecialchars($user_name) ?></span>
                    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 xl:px-6 xl:py-2 rounded-full transition duration-200 font-medium text-sm xl:text-base">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>

            </nav>
        </header>
        
        <div id="mobile-nav" class="hidden lg:hidden relative z-20 bg-white/10 backdrop-blur-md border-b border-white/20">
            <div class="px-6 py-4 space-y-3">
                <a href="home.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Home</a>
                <a href="map.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Map</a>
                <a href="request.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Request Aid</a>
                <a href="logout.php" class="block px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-200 font-medium text-center">Logout</a>
            </div>
        </div>

        <main class="relative z-30 flex-grow py-8">
            <div class="max-w-7xl mx-auto px-6">
                <!-- Welcome Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Welcome to Your Dashboard</h1>
                    <p class="text-xl text-gray-300">Track your aid requests and make a difference</p>
                </div>

                <!-- Dashboard Stats -->
                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-hand-holding-heart text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white text-lg font-semibold">Total Requests</h3>
                                <p class="text-secondary text-3xl font-bold">2</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check-circle text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white text-lg font-semibold">Approved</h3>
                                <p class="text-green-400 text-3xl font-bold">1</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white text-lg font-semibold">Pending</h3>
                                <p class="text-yellow-400 text-3xl font-bold">1</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aid Requests Table -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden mb-12">
                    <div class="p-6 border-b border-white/20">
                        <h2 class="text-3xl font-bold text-white mb-2">My Aid Requests</h2>
                        <p class="text-xl text-gray-300">Track the status of your aid requests</p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-4 text-left text-white font-semibold">Request ID</th>
                                    <th class="px-6 py-4 text-left text-white font-semibold">Type</th>
                                    <th class="px-6 py-4 text-left text-white font-semibold">Status</th>
                                    <th class="px-6 py-4 text-left text-white font-semibold">Date</th>
                                    <th class="px-6 py-4 text-left text-white font-semibold">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aid_requests as $request): ?>
                                <tr class="border-b border-white/10 hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 text-gray-300">#<?= $request['id'] ?></td>
                                    <td class="px-6 py-4">
                                        <span class="bg-primary/20 text-primary px-3 py-1 rounded-full text-sm">
                                            <?= htmlspecialchars($request['aidtype'] ?? $request['type']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-300"><?= date('Y-m-d', strtotime($request['created_at'] ?? 'now')) ?></td>
                                    <td class="px-6 py-4 text-gray-300"><?= htmlspecialchars($request['details']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-xl">
                        <h3 class="text-2xl font-bold text-white mb-6">Quick Actions</h3>
                        <div class="space-y-4">
                            <a href="request.php" class="block bg-primary hover:bg-secondary text-white px-6 py-4 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-lg text-center font-semibold">
                                <i class="fas fa-plus mr-2"></i>New Aid Request
                            </a>
                            <a href="map.php" class="block bg-secondary hover:bg-primary text-white px-6 py-4 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-lg text-center font-semibold">
                                <i class="fas fa-map mr-2"></i>View Aid Map
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-xl">
                        <h3 class="text-2xl font-bold text-white mb-6">Support</h3>
                        <div class="space-y-4">
                            <a href="../html/chatbot.html" class="block bg-accent hover:bg-secondary text-background px-6 py-4 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-lg text-center font-semibold">
                                <i class="fas fa-headset mr-2"></i>Contact Support
                            </a>
                            <a href="home.php" class="block border-2 border-secondary text-secondary hover:bg-secondary hover:text-white px-6 py-4 rounded-lg transition duration-300 text-center font-semibold">
                                <i class="fas fa-home mr-2"></i>Back to Home
                            </a>
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
    </script>
</body>
</html>