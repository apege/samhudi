<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Header -->
<header class="h-20 bg-gradient-to-r from-[#374D49] to-[#3E6C65] border-b border-[#4D6B67]/30 flex items-center justify-between px-8 shrink-0 shadow-md">
    <div>
        <h1 class="font-display font-bold text-lg text-white">Dashboard Overview</h1>
        <p class="text-xs text-white/80 mt-0.5">Selamat datang kembali, <?= htmlspecialchars($admin_name ?? 'Admin') ?></p>
    </div>
    
    <button onclick="window.location.reload();" class="flex items-center gap-2 border border-white/20 bg-white/10 hover:bg-white/20 text-[#E3E3E3] hover:text-white px-4 py-2 rounded-lg text-xs font-semibold tracking-wide transition-all shadow-sm backdrop-blur-sm">
        <i class="bi bi-arrow-clockwise text-sm"></i>
        <span>Refresh</span>
    </button>
</header>
