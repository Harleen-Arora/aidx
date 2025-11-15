<?php
session_start();

$user_name = $_SESSION['user_name'] ?? 'User';

// Mock data for aid requests
$aid_requests = [
    [
        'id' => 1,
        'type' => 'Food',
        'status' => 'Pending',
        'date' => '2024-01-15',
        'details' => 'Emergency food assistance needed'
    ],
    [
        'id' => 2,
        'type' => 'Medical',
        'status' => 'Approved',
        'date' => '2024-01-10',
        'details' => 'Medical supplies for elderly care'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AID-X</title>
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-background to-primary min-h-screen">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <nav class="flex justify-between items-center max-w-7xl mx-auto px-6 py-4">
            <div class="text-2xl font-extrabold text-white tracking-wider flex items-center">
                <svg class="w-8 h-8 mr-2 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                AID-<span class="text-secondary">X</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <span class="text-white">Welcome, <?= htmlspecialchars($user_name) ?></span>
                <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </nav>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Dashboard Stats -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold">Total Requests</h3>
                        <p class="text-secondary text-2xl font-bold">2</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold">Approved</h3>
                        <p class="text-green-400 text-2xl font-bold">1</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-semibold">Pending</h3>
                        <p class="text-yellow-400 text-2xl font-bold">1</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aid Requests Table -->
        <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-white/20">
                <h2 class="text-2xl font-bold text-white mb-2">My Aid Requests</h2>
                <p class="text-gray-300">Track the status of your aid requests</p>
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
                                    <?= htmlspecialchars($request['type']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($request['status'] === 'Approved'): ?>
                                    <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-check-circle mr-1"></i>Approved
                                    </span>
                                <?php elseif ($request['status'] === 'Pending'): ?>
                                    <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                <?php else: ?>
                                    <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm">
                                        <i class="fas fa-times-circle mr-1"></i>Rejected
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-300"><?= $request['date'] ?></td>
                            <td class="px-6 py-4 text-gray-300"><?= htmlspecialchars($request['details']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid md:grid-cols-2 gap-6">
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl">
                <h3 class="text-xl font-bold text-white mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="request.php" class="block bg-primary hover:bg-secondary text-white px-4 py-3 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-plus mr-2"></i>New Aid Request
                    </a>
                    <a href="../html/map.html" class="block bg-secondary hover:bg-primary text-white px-4 py-3 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-map mr-2"></i>View Aid Map
                    </a>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl shadow-xl">
                <h3 class="text-xl font-bold text-white mb-4">Support</h3>
                <div class="space-y-3">
                    <a href="#" class="block bg-accent hover:bg-secondary text-background px-4 py-3 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-headset mr-2"></i>Contact Support
                    </a>
                    <a href="../index.html" class="block border border-secondary text-secondary hover:bg-secondary hover:text-white px-4 py-3 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-home mr-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>