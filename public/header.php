<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>     
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriChain - Truy xuất nguồn gốc Blockchain</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        @keyframes scan { 0%, 100% { top: 0; } 50% { top: 100%; } }
        .laser-line { animation: scan 2.5s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    <nav class="border-b border-gray-200 bg-white sticky top-0 z-50 px-6 py-4 flex justify-between items-center shadow-sm">
    
    <a href="index.php" class="flex items-center space-x-3 group cursor-pointer">
        <div class="bg-green-50 p-2 rounded-xl text-green-600 border border-green-200 group-hover:bg-green-100 transition">
            <i class="fa-solid fa-cubes text-xl"></i>
        </div>
        <div>
            <span class="text-xl font-bold tracking-tight text-gray-900 block group-hover:text-green-600 transition">AgriChain</span>
            <span class="text-xs text-gray-500">Truy xuất nguồn gốc Blockchain</span>
        </div>
    </a>
    
    <div class="hidden md:flex items-center space-x-8 font-medium text-sm">
        <a href="index.php" class="<?= ($current_page == 'index.php') ? 'text-green-600' : 'text-gray-500 hover:text-green-600' ?> transition">
            <i class="fa-solid fa-chart-pie mr-1.5"></i>Tổng quan
        </a>
        <a href="danh-sach.php" class="<?= ($current_page == 'danh-sach.php') ? 'text-green-600' : 'text-gray-500 hover:text-green-600' ?> transition">
            <i class="fa-solid fa-layer-group mr-1.5"></i>Lô hàng của tôi
        </a>
        <a href="quet-ma.php" class="<?= ($current_page == 'quet-ma.php') ? 'text-green-600' : 'text-gray-500 hover:text-green-600' ?> transition">
            <i class="fa-solid fa-qrcode mr-1.5"></i>Quét mã tra cứu
        </a>
        <a href="admin.php" class="<?= ($current_page == 'admin.php') ? 'text-red-600 font-bold' : 'text-gray-500 hover:text-red-600' ?> transition relative">
            <i class="fa-solid fa-user-shield mr-1.5"></i>Quản trị Admin
            <span class="absolute -top-1 -right-2 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
            </span>
        </a>
    </div>

    <div class="flex items-center space-x-4">
        <button id="btnConnect" onclick="connectWallet()" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-xl text-sm shadow-md transition flex items-center space-x-2">
            <i class="fa-solid fa-wallet"></i>
            <span id="walletAddressDisplay" class="hidden md:inline">Kết nối ví</span>
        </button>
    </div>
</nav>