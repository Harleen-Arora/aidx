<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store form data in session for later use
    session_start();
    $_SESSION['aid_request'] = [
        'fullname' => $_POST['fullname'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'address' => $_POST['address'] ?? '',
        'aid_type' => $_POST['aid_type'] ?? '',
        'details' => $_POST['details'] ?? ''
    ];
    
    // Redirect to signup page
    header('Location: singup.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Aid - AID-X</title>
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
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-md sm:max-w-lg">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Request Aid</h1>
                <p class="text-gray-300">Fill out the form to request assistance</p>
            </div>
            
            <form method="post" action="" class="space-y-4 sm:space-y-6">
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Full Name</label>
                        <input type="text" name="fullname" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-secondary text-sm" placeholder="Enter your full name" required>
                    </div>
                    
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Phone Number</label>
                        <input type="tel" name="phone" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-secondary text-sm" placeholder="Enter your phone number" required>
                    </div>
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Address</label>
                    <textarea name="address" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-secondary text-sm" rows="2 sm:rows-3" placeholder="Enter your address" required></textarea>
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Type of Aid Needed</label>
                    <div class="relative">
                        <select name="aid_type" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-secondary appearance-none text-sm" required>
                            <option value="" class="bg-background text-white">Select aid type...</option>
                            <option value="food" class="bg-background text-white">🍽️ Food & Nutrition</option>
                            <option value="medical" class="bg-background text-white">🏥 Medical Care</option>
                            <option value="shelter" class="bg-background text-white">🏠 Shelter & Housing</option>
                            <option value="clothing" class="bg-background text-white">👕 Clothing</option>
                            <option value="education" class="bg-background text-white">🎓 Education</option>
                            <option value="water" class="bg-background text-white">💧 Clean Water</option>
                            <option value="transport" class="bg-background text-white">🚗 Transportation</option>
                            <option value="other" class="bg-background text-white">📋 Other</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-300 pointer-events-none"></i>
                    </div>
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Details</label>
                    <textarea name="details" class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-secondary text-sm" rows="3 sm:rows-4" placeholder="Describe your situation and specific needs" required></textarea>
                </div>
                
                <button type="submit" class="w-full bg-primary hover:bg-secondary text-white font-semibold py-2 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-300 transform hover:scale-[1.02] text-sm sm:text-base">
                    Submit Request
                </button>
            </form>
            
            <div class="text-center mt-6">
                <a href="../index.html" class="text-secondary hover:text-accent transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
    <script>
        // Handle radio button selection
        document.addEventListener('DOMContentLoaded', function() {
            const radioInputs = document.querySelectorAll('input[type="radio"]');
            
            radioInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Reset all radio buttons
                    radioInputs.forEach(radio => {
                        const dot = radio.parentElement.querySelector('.w-2');
                        dot.style.opacity = '0';
                        radio.parentElement.classList.remove('bg-white/40');
                    });
                    
                    // Highlight selected radio button
                    if (this.checked) {
                        const dot = this.parentElement.querySelector('.w-2');
                        dot.style.opacity = '1';
                        this.parentElement.classList.add('bg-white/40');
                    }
                });
            });
        });
    </script>
</body>
</html>
