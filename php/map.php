<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Map - AID-X: Smart Humanitarian Support Platform</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
  #map {
    height: 70vh; 
    min-height: 500px;
    border-radius: 16px; 
    box-shadow: 0 12px 48px rgba(0,180,216,0.4); 
    border: 2px solid #00b4d8;
  }
  #sidebar {
    background: rgba(255,255,255,0.1); backdrop-filter: blur(16px); border-radius: 16px; padding: 18px; box-sizing: border-box;
    display: flex; flex-direction: column; overflow-y: auto; border: 1px solid rgba(255,255,255,0.2);
  }
  #search-section {
    margin-bottom: 28px; border-bottom: 2px solid rgba(255,255,255,0.2); padding-bottom: 16px;
  }
  #search-section h2 { margin:0 0 12px 0; font-size: 1.2rem; color: white; }
  #searchBox {
    display: flex; align-items: center; gap: 8px;
  }
  #searchInput {
    width: 100%; padding:12px 16px; border-radius:8px; border:1px solid rgba(255,255,255,0.3); font-size:1.1rem; min-width:0;
    background: rgba(0,0,0,0.3); color: white; backdrop-filter: blur(8px); cursor: text;
    position: relative; z-index: 10; pointer-events: auto; height: 48px;
  }
  #searchInput::placeholder { color: rgba(255,255,255,0.7); }
  #searchInput:focus {
    outline: none; border-color: #00b4d8; background: rgba(0,0,0,0.4); z-index: 20;
  }
  #searchInput:hover {
    background: rgba(0,0,0,0.4); border-color: rgba(255,255,255,0.5);
  }
  #searchBox {
    position: relative; z-index: 5;
  }
  #searchBtn { 
    background: #00b4d8; color:white; border:none; font-weight:600; border-radius:8px; padding:12px 16px; cursor:pointer; transition: background 0.3s;
    font-size: 1rem; min-height: 44px; display: flex; align-items: center; justify-content: center;
  }
  #searchBtn:hover { background:#48cae4; }
  #resetBtn {
    background:#ef4444; color:white; border:none; border-radius:8px; padding:12px 16px; cursor:pointer; font-size:1rem; transition: background 0.3s;
    min-height: 44px; display: flex; align-items: center; justify-content: center;
  }
  #resetBtn:hover { background:#dc2626; }
  #active-section h2 { margin-top: 0; margin-bottom:15px; font-size: 1.25rem; color: white; }
  .priority-section {
    margin-bottom: 20px; border-left: 4px solid; padding-left: 10px;
  }
  .priority-section.high { border-left-color: #ef4444; }
  .priority-section.medium { border-left-color: #f59e0b; }
  .priority-section.low { border-left-color: #10b981; }
  .priority-section h3 { 
    margin: 0 0 10px 0; font-size: 1.1rem; font-weight: 700; color: white;
  }
  .no-problems {
    text-align: center; color: rgba(255,255,255,0.7); font-style: italic; padding: 20px;
  }
  #sidebar div.problem {
    padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.2); cursor: pointer; transition: background 0.3s; border-radius: 6px; margin-bottom: 8px;
    background: rgba(255,255,255,0.05);
  }
  #sidebar div.problem:hover { background-color: rgba(255,255,255,0.1); }
  #sidebar .field { margin-bottom: 4px; font-size: 0.95rem; color: rgba(255,255,255,0.9); }
  #sidebar .field strong { color: #48cae4; }
  #loading { text-align: center; color: white; font-style: italic; display: none; }
  @media (max-width: 768px) {
    #container { flex-direction: column; }
    #sidebar { width: 100%; height: auto; max-height: 50vh; }
    nav.navbar ul.nav-links { flex-direction: column; }
  }
</style>
</head>
<body class="font-sans min-h-screen bg-gray-50">

<div class="hero-background bg-hero-pattern min-h-screen flex flex-col relative">
    <div class="absolute inset-0 bg-background opacity-80 backdrop-blur-sm pointer-events-none"></div>

    <header class="fixed top-0 left-0 right-0 z-50 bg-white/10 backdrop-blur-md border-b border-white/20">
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
                <?php if ($isLoggedIn): ?>
                    <a href="home.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Home</a>
                    <a href="dashboard.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Dashboard</a>
                    <a href="map.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Map</a>
                    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 xl:px-6 xl:py-2 rounded-full transition duration-200 font-medium text-sm xl:text-base">Logout</a>
                <?php else: ?>
                    <a href="../index.html" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Home</a>
                    <a href="../index.html#about" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">About</a>
                    <a href="../index.html#services" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Services</a>
                    <a href="map.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Map</a>
                    <a href="../index.html#contact" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Contact</a>
                    <a href="signin.php" class="text-white hover:text-secondary transition duration-200 font-medium text-sm xl:text-base">Login</a>
                    <a href="singup.php" class="bg-primary hover:bg-secondary text-white px-4 py-2 xl:px-6 xl:py-2 rounded-full transition duration-200 font-medium text-sm xl:text-base">Sign Up</a>
                <?php endif; ?>
            </div>

        </nav>
    </header>
    
    <div id="mobile-nav" class="hidden lg:hidden fixed top-16 left-0 right-0 z-40 bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="px-6 py-4 space-y-3">
            <?php if ($isLoggedIn): ?>
                <a href="home.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Home</a>
                <a href="dashboard.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Dashboard</a>
                <a href="map.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Map</a>
                <a href="logout.php" class="block px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-200 font-medium text-center">Logout</a>
            <?php else: ?>
                <a href="../index.html" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Home</a>
                <a href="../index.html#about" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">About</a>
                <a href="../index.html#services" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Services</a>
                <a href="map.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Map</a>
                <a href="../index.html#contact" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Contact</a>
                <a href="signin.php" class="block px-3 py-2 text-white hover:text-secondary rounded-lg transition duration-200 font-medium">Login</a>
                <a href="singup.php" class="block px-3 py-2 bg-primary hover:bg-secondary text-white rounded-lg transition duration-200 font-medium text-center">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>

    <main class="relative z-30 flex-1 p-6 pt-32">
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Real-time Aid Request Map</h1>
            <p class="text-xl text-gray-300">Track and coordinate humanitarian aid in real-time</p>
        </div>
        
        <div class="max-w-7xl mx-auto grid lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3">
                <div id="map" class="w-full"></div>
            </div>
            <div class="lg:col-span-1">
                <div id="sidebar">
    <div id="search-section">
      <h2>Search</h2>
      <div id="searchBox">
        <input type="text" id="searchInput" placeholder="Enter location (e.g., Delhi), aid type, name, etc.">
      </div>
      <div class="mt-3 flex gap-2">
        <button id="searchBtn" class="flex-1" onclick="doSearch()">Search</button>
        <button id="resetBtn" class="flex-1" onclick="resetSearch()">Reset</button>
      </div>
    </div>
    
    <div class="mb-6">
      <button id="addRequestBtn" class="w-full bg-primary hover:bg-secondary text-white font-semibold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-[1.02] shadow-lg">
        <i class="fas fa-plus mr-2"></i>Add Aid Request
      </button>
    </div>
    
    <div id="active-section">
      <h2>Active Problems</h2>
      <div id="problems-list">
        <div id="loading">Loading active problems...</div>
      </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let allRequests = [];
let allMarkers = [];

const levelIcons = {
  'Low': 'green',
  'Medium': 'orange', 
  'High': 'red'
};

function createColoredIcon(color) {
  return new L.Icon({
    iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-${color}.png`,
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    iconSize: [25,41], iconAnchor: [12,41], popupAnchor: [1,-34], shadowSize: [41,41]
  });
}

async function fetchRequests() {
  return [
    {fullname: "John Doe", address: "Delhi", latitude: 28.6139, longitude: 77.2090, aidtype: "Food", details: "Urgent ration needed", emergency_level: "High", phone: "9876543210"},
    {fullname: "Jane Smith", address: "Ludhiana", latitude: 30.9010, longitude: 75.8573, aidtype: "Medical", details: "Blood donation required", emergency_level: "Medium", phone: "9876543211"},
    {fullname: "Test User", address: "Mumbai", latitude: 19.0760, longitude: 72.8777, aidtype: "Volunteer", details: "Help with relief", emergency_level: "Low", phone: "9876543212"}
  ];
}

function renderMarkers(requests) {
  if (!map) return;
  
  allMarkers.forEach(m => map.removeLayer(m));
  allMarkers = [];
  const problemsList = document.getElementById('problems-list');
  problemsList.innerHTML = '';

  if (requests.length === 0) {
    problemsList.innerHTML = '<div class="no-problems">No active problems found.</div>';
    return;
  }

  const grouped = { 'High': [], 'Medium': [], 'Low': [] };

  requests.forEach(req => {
    const level = (req.emergency_level || 'Low').toString();
    const capLevel = level.charAt(0).toUpperCase() + level.slice(1);
    grouped[capLevel] = grouped[capLevel] || [];
    grouped[capLevel].push(req);

    const iconColor = levelIcons[capLevel] || 'green';
    const icon = createColoredIcon(iconColor);
    const marker = L.marker([req.latitude, req.longitude], {icon}).addTo(map);
    marker.bindPopup(`<b>${req.fullname}</b><br/>${req.aidtype}`);
    allMarkers.push(marker);
  });

  ['High', 'Medium', 'Low'].forEach(level => {
    if (grouped[level].length === 0) return;
    const section = document.createElement('div');
    section.className = `priority-section ${level.toLowerCase()}`;
    const levelHeading = document.createElement('h3');
    levelHeading.innerText = `${level} Priority (${grouped[level].length})`;
    section.appendChild(levelHeading);

    grouped[level].forEach(item => {
      const div = document.createElement('div');
      div.className = 'problem';
      div.innerHTML = `
        <div class="field"><strong>Name:</strong> ${item.fullname}</div>
        <div class="field"><strong>Location:</strong> ${item.address}</div>
        <div class="field"><strong>Aid Type:</strong> ${item.aidtype}</div>
        <div class="field"><strong>Details:</strong> ${item.details}</div>
        <div class="field"><strong>Contact:</strong> ${item.phone}</div>
      `;
      div.onclick = () => { if (map) map.flyTo([item.latitude, item.longitude], 15); };
      section.appendChild(div);
    });
    problemsList.appendChild(section);
  });
}

function doSearch() {
  if (!map) return;
  const val = document.getElementById('searchInput').value.trim();
  
  if (val.toLowerCase() === 'delhi') {
    map.setView([28.6139, 77.2090], 12);
    const marker = L.marker([28.6139, 77.2090]).addTo(map);
    marker.bindPopup('<b>Delhi</b>').openPopup();
  }
}

function resetSearch() {
  if (!map) return;
  document.getElementById('searchInput').value = '';
  map.setView([23.5937, 80.9629], 5);
}

function toggleMobileMenu() {
  const mobileNav = document.getElementById('mobile-nav');
  mobileNav.classList.toggle('hidden');
}

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
  setTimeout(() => {
    if (document.getElementById('map')) {
      map = L.map('map').setView([23.5937, 80.9629], 5);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18
      }).addTo(map);
      
      fetchRequests().then(results => {
        allRequests = results;
        renderMarkers(results);
      });
    }
  }, 300);
});

document.getElementById('addRequestBtn').onclick = function() {
  <?php if ($isLoggedIn): ?>
    window.location.href = 'request.php';
  <?php else: ?>
    window.location.href = 'signin.php';
  <?php endif; ?>
};
</script>
</body>
</html>