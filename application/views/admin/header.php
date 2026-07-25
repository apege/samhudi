<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Header -->
<header class="h-20 bg-gradient-to-r from-[#374D49] to-[#3E6C65] border-b border-[#4D6B67]/30 flex items-center justify-between px-4 md:px-8 shrink-0 shadow-md">
    <div class="flex items-center gap-2 sm:gap-4 min-w-0">
        <button onclick="document.getElementById('adminSidebar').classList.remove('-translate-x-full');" class="md:hidden text-white/80 hover:text-white p-1 shrink-0">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <div class="min-w-0">
            <h1 class="font-display font-bold text-sm sm:text-lg text-white truncate">Dashboard Overview</h1>
            <p class="text-[10px] sm:text-xs text-white/80 mt-0.5 truncate">Selamat datang, <?= htmlspecialchars($admin_name ?? 'Admin') ?></p>
        </div>
    </div>
    
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        <button id="theme-toggle" class="text-white hover:text-gray-300 focus:outline-none p-2 rounded-full hover:bg-white/10 transition-colors shrink-0" title="Toggle Tema">
            <i id="theme-icon" class="bi bi-moon-stars text-base"></i>
        </button>
        <button onclick="window.location.reload();" class="flex items-center gap-1.5 border border-white/20 bg-white/10 hover:bg-white/20 text-[#E3E3E3] hover:text-white p-2.5 sm:px-4 sm:py-2 rounded-lg text-xs font-semibold tracking-wide transition-all shadow-sm backdrop-blur-sm shrink-0" title="Refresh Halaman">
            <i class="bi bi-arrow-clockwise text-sm"></i>
            <span class="hidden sm:inline">Refresh</span>
        </button>
    </div>
</header>
