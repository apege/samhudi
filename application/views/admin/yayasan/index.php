<?php
if (!function_exists('build_nomination_trees')) {
    function build_nomination_trees($candidates_list) {
        $candidates_by_name = [];
        foreach ($candidates_list as $c) {
            $candidates_by_name[strtolower(trim($c['candidate_name']))] = $c;
        }
        
        $children = [];
        $roots = [];
        
        foreach ($candidates_list as $c) {
            $nom = strtolower(trim($c['nominator_name']));
            if (isset($candidates_by_name[$nom])) {
                $children[$nom][] = $c;
            } else {
                $roots[$c['nominator_name']][] = $c;
            }
        }
        
        return ['roots' => $roots, 'children' => $children];
    }
}

if (!function_exists('render_tree_node')) {
    function render_tree_node($cand, $children, $color_theme = 'emerald') {
        $cand_key = strtolower(trim($cand['candidate_name']));
        $has_children = isset($children[$cand_key]);
        $is_cyan = ($color_theme === 'cyan');

        $line_color = $is_cyan ? 'bg-cyan-500/50' : 'bg-emerald-500/50';
        $card_bg    = $is_cyan ? 'from-cyan-500/10 to-cyan-500/0 border-cyan-500/25' : 'from-emerald-500/10 to-emerald-500/0 border-emerald-500/25';
        $text_color = $is_cyan ? 'text-cyan-300' : 'text-emerald-400';
        $border_l   = $is_cyan ? 'border-cyan-500/25' : 'border-emerald-500/25';
        ?>
        <div class="flex flex-col gap-3 text-left">
            <!-- Candidate Node Card -->
            <div class="flex items-center gap-3">
                <div class="w-3 h-0.5 <?= $line_color ?>"></div>
                <div class="bg-gradient-to-r <?= $card_bg ?> border rounded-2xl px-5 py-3 flex items-center justify-between gap-6 transition-all duration-300">
                    <div>
                        <?php 
                        $role_raw = trim((isset($cand['roles_text']) && $cand['roles_text'] !== '-') ? $cand['roles_text'] : ($cand['description'] ?: ''));
                        $is_ketua      = preg_match('/ketua/i', $role_raw);
                        $is_bendahara  = preg_match('/bendahara/i', $role_raw);
                        $is_sekretaris = preg_match('/sekretaris/i', $role_raw);
                        if ($is_ketua)           { $role_lbl = 'Kandidat Ketua'; }
                        elseif ($is_bendahara)   { $role_lbl = 'Kandidat Bendahara'; }
                        elseif ($is_sekretaris)  { $role_lbl = 'Kandidat Sekretaris'; }
                        else                     { $role_lbl = 'Kandidat Ketua'; }
                        ?>
                        <span class="text-[10px] uppercase font-bold <?= $text_color ?> tracking-wider block mb-0.5"><?= htmlspecialchars($role_lbl) ?></span>
                        <strong class="text-white text-base font-semibold"><?= htmlspecialchars($cand['candidate_name']) ?></strong>
                    </div>
                </div>
            </div>
            
            <!-- Recursive Children -->
            <?php if ($has_children): ?>
                <div class="flex flex-col gap-3 pl-8 border-l <?= $border_l ?> ml-[26px] pt-1">
                    <?php foreach ($children[$cand_key] as $child): ?>
                        <?php render_tree_node($child, $children, $color_theme); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('render_custom_pagination')) {
    function render_custom_pagination($total_rows, $limit, $current_page, $param_name, $is_all = false) {
        $total_pages = ceil($total_rows / max($limit, 1));

        $get = $_GET;
        unset($get[$param_name]);
        unset($get['page']);
        $query_base = http_build_query($get);
        $url_prefix = current_url() . ($query_base ? '?' . $query_base . '&' : '?') . $param_name . '=';

        // Build "Lihat Semua" / "Kembali" URL
        $get_toggle = $_GET;
        if ($is_all) {
            unset($get_toggle[$param_name]);
            $toggle_url = current_url() . ($qt = http_build_query($get_toggle)) ? '?' . $qt : '';
            $toggle_label = '<i class="bi bi-list-ul"></i> Per Halaman';
            $toggle_class = 'bg-[#2c3f3a] hover:bg-brand-medium/60 text-white border border-brand-medium/40';
        } else {
            $get_toggle[$param_name] = 'all';
            $toggle_url = current_url() . '?' . http_build_query($get_toggle);
            $toggle_label = '<i class="bi bi-eye-fill"></i> Lihat Semua';
            $toggle_class = 'bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 border border-amber-500/30';
        }
        $toggle_btn = '<a href="' . $toggle_url . '" class="px-3 py-2 rounded-xl text-xs font-semibold transition-all ' . $toggle_class . '">' . $toggle_label . '</a>';

        if ($is_all || $total_pages <= 1) {
            // Show only toggle button when viewing all or single page
            return '<div class="flex items-center justify-center gap-1.5 mt-4">' . $toggle_btn . '</div>';
        }

        $html = '<div class="flex items-center justify-center gap-1.5 mt-4 overflow-x-auto pb-1">';
        $html .= $toggle_btn;

        if ($current_page > 1) {
            $html .= '<a href="' . $url_prefix . ($current_page - 1) . '" class="px-3.5 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all shrink-0"><i class="bi bi-chevron-left"></i></a>';
        }

        // Dynamic pagination window (max 3 page numbers to fit neatly)
        $max_visible = 3;
        $half = floor($max_visible / 2);
        $start_p = max(1, $current_page - $half);
        $end_p   = min($total_pages, $start_p + $max_visible - 1);
        if ($end_p - $start_p + 1 < $max_visible) {
            $start_p = max(1, $end_p - $max_visible + 1);
        }

        if ($start_p > 1) {
            $html .= '<a href="' . $url_prefix . '1" class="px-3 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all shrink-0">1</a>';
            if ($start_p > 2) {
                $html .= '<span class="px-1 py-2 text-white/40 text-xs shrink-0">...</span>';
            }
        }

        for ($i = $start_p; $i <= $end_p; $i++) {
            if ($i === $current_page) {
                $html .= '<span class="px-3.5 py-2 rounded-xl bg-brand-medium text-white text-xs font-bold border border-brand-medium/50 shadow-md shadow-brand-medium/10 shrink-0">' . $i . '</span>';
            } else {
                $html .= '<a href="' . $url_prefix . $i . '" class="px-3.5 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all shrink-0">' . $i . '</a>';
            }
        }

        if ($end_p < $total_pages) {
            if ($end_p < $total_pages - 1) {
                $html .= '<span class="px-1 py-2 text-white/40 text-xs shrink-0">...</span>';
            }
            $html .= '<a href="' . $url_prefix . $total_pages . '" class="px-3 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all shrink-0">' . $total_pages . '</a>';
        }

        if ($current_page < $total_pages) {
            $html .= '<a href="' . $url_prefix . ($current_page + 1) . '" class="px-3.5 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all shrink-0"><i class="bi bi-chevron-right"></i></a>';
        }

        $html .= '</div>';
        return $html;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon Yayasan | Admin Panel</title>
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            950: '#15201E',
                            900: '#1D2A27',
                            800: '#273834',
                            700: '#324742',
                            600: '#435E59',
                            500: '#5F7F7A',
                            400: '#8DAAA4',
                        },
                        brand: {
                            dark: '#374D49',
                            medium: '#4D6B67',
                            light: '#E3E3E3',
                            red: '#E14343',
                        }
                    },
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Highcharts 3D Pie Chart Library -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #15201E; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

        .highcharts-credits { display: none !important; }
        .highcharts-background { fill: transparent !important; }
        .highcharts-legend-navigation { display: none !important; }

        .rundayan-hover {
            position: relative;
            cursor: pointer;
            text-decoration: underline;
            text-decoration-style: dotted;
            text-underline-offset: 3px;
        }
    </style>
</head>
<body class="bg-teal-950 text-white font-body h-screen flex overflow-hidden">

    <!-- Sidebar removed as requested: Halaman khusus laporan hasil rekapitulasi -->

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden">
        
        <!-- Header (Tanpa Hamburger Menu) -->
        <header class="h-20 bg-gradient-to-r from-[#374D49] to-[#3E6C65] border-b border-[#4D6B67]/30 flex items-center justify-between px-4 md:px-8 shrink-0 shadow-md">
            <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                <div class="min-w-0">
                    <h1 class="font-display font-bold text-sm sm:text-lg text-white truncate">Dashboard Overview</h1>
                    <p class="text-[10px] sm:text-xs text-white/80 mt-0.5 truncate">Selamat datang, <?= htmlspecialchars($admin_name ?? 'Admin Utama') ?></p>
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

        <!-- Content Area -->
        <div class="p-4 md:p-8 space-y-8">
            <?php
            // Determine which table is in "view all" mode to focus view
            $show_only = null;
            if ($page_individu === 'all') $show_only = 'individu';
            elseif ($page_rundayan === 'all') $show_only = 'rundayan';

            // Build back URL (remove both all params)
            $get_back = $_GET;
            unset($get_back['page_individu'], $get_back['page_rundayan']);
            $back_url = base_url('admin/yayasan') . ($get_back ? '?' . http_build_query($get_back) : '');
            ?>

            <!-- Toast Alert for success/error messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-300 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span class="text-sm font-semibold"><?= $this->session->flashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                    <span class="text-sm font-semibold"><?= $this->session->flashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if ($show_only): ?>
            <!-- Focus Banner: Lihat Semua mode -->
            <div class="flex items-center justify-between gap-4 bg-[#1a2e2b] border border-brand-medium/40 rounded-2xl px-5 py-3">
                <span class="text-sm font-semibold text-white/80">
                    <i class="bi bi-eye-fill text-amber-300 mr-1.5"></i>
                    Menampilkan semua data tabel <strong class="text-amber-300"><?= $show_only === 'individu' ? 'Individu' : 'Rundayan' ?></strong>
                </span>
                <a href="<?= $back_url ?>" class="flex items-center gap-1.5 px-4 py-1.5 bg-brand-medium hover:bg-brand-medium/80 text-white text-xs font-bold rounded-xl transition-all">
                    <i class="bi bi-arrow-left"></i> Kembali ke Tampilan Normal
                </a>
            </div>
            <?php else: ?>
            <!-- Title & Action -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Kelola Calon Yayasan</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Daftar calon ketua yayasan hasil pencalonan keluarga besar beserta jumlah suara (votes).</p>
                </div>
                
                <!-- Action Buttons: Dewan Pembina QR Code -->
                <div class="flex items-center gap-3">
                    <button onclick="openQrModal()" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-teal-950 font-display font-bold text-xs rounded-xl shadow-lg shadow-amber-500/20 flex items-center gap-2 transition-all">
                        <i class="bi bi-qr-code-scan text-base"></i> QR Code Dewan Pembina
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- SUMMARY STAT CARDS (TOTAL SUARA MASUK) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-[#1d3530] to-[#132522] border border-amber-500/30 rounded-2xl p-4 flex items-center gap-4 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl shrink-0">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold tracking-wider text-amber-200/70 uppercase block">TOTAL SUARA MASUK</span>
                        <div class="text-2xl font-black text-amber-400 flex items-baseline gap-1.5">
                            <?= number_format($total_suara_masuk ?? 0) ?>
                            <span class="text-xs font-semibold text-white/50">Suara</span>
                        </div>
                        <span class="text-[10px] text-amber-300/80 font-medium block mt-0.5">
                            <i class="bi bi-people-fill text-xs mr-0.5"></i> Dari <strong class="text-amber-200"><?= number_format($total_pemilih_keseluruhan ?? 0) ?></strong> Pemilih (Orang)
                        </span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#1d3530] to-[#132522] border border-emerald-500/30 rounded-2xl p-4 flex items-center gap-4 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-xl shrink-0">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold tracking-wider text-emerald-200/70 uppercase block">JUMLAH SUARA MASUK KATEGORI INDIVIDU</span>
                        <div class="text-2xl font-black text-emerald-400 flex items-baseline gap-1.5">
                            <?= number_format($total_suara_individu ?? 0) ?>
                            <span class="text-xs font-semibold text-white/50">Suara</span>
                        </div>
                        <span class="text-[10px] text-emerald-300/80 font-medium block mt-0.5">
                            <i class="bi bi-people-fill text-xs mr-0.5"></i> Dari <strong class="text-emerald-200"><?= number_format($total_pemilih_individu ?? 0) ?></strong> Pemilih (Orang)
                        </span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#1d3530] to-[#132522] border border-cyan-500/30 rounded-2xl p-4 flex items-center gap-4 shadow-lg">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-xl shrink-0">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold tracking-wider text-cyan-200/70 uppercase block">JUMLAH SUARA MASUK KATEGORI RUNDAYAN</span>
                        <div class="text-2xl font-black text-cyan-400 flex items-baseline gap-1.5">
                            <?= number_format($total_suara_rundayan ?? 0) ?>
                            <span class="text-xs font-semibold text-white/50">Suara</span>
                        </div>
                        <span class="text-[10px] text-cyan-300/80 font-medium block mt-0.5">
                            <i class="bi bi-people-fill text-xs mr-0.5"></i> Dari <strong class="text-cyan-200"><?= number_format($total_pemilih_rundayan ?? 0) ?></strong> Pemilih (Orang)
                        </span>
                    </div>
                </div>
            </div>

            <!-- RINGKASAN STATUS INPUT 14 RUNDAYAN -->
            <?php 
                $count_submitted = count(array_filter($rundayan_input_status ?? [], function($r) { return $r['has_input']; }));
                $count_pending   = 14 - $count_submitted;
                $sum_rundayan_votes = array_sum(array_column($rundayan_input_status ?? [], 'vote_count'));
            ?>
            <div class="bg-gradient-to-br from-[#1b332e] to-[#122320] border border-teal-700/40 rounded-2xl p-5 shadow-xl space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-teal-700/30 pb-3">
                    <div>
                        <h4 class="font-display font-bold text-base text-white flex items-center gap-2">
                            <i class="bi bi-card-checklist text-amber-400 text-lg"></i> Keterangan Status Input 14 Rundayan
                        </h4>
                        <p class="text-xs text-white/50">Daftar kelengkapan inputan suara khusus 14 Rundayan Samhudi.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                        <span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 rounded-xl">
                            <i class="bi bi-box-seam-fill mr-1"></i> Total Suara Rundayan: <strong class="text-cyan-200"><?= number_format($sum_rundayan_votes) ?></strong> Suara
                        </span>
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-xl">
                            <i class="bi bi-check-circle-fill mr-1"></i> <?= $count_submitted ?> Sudah Input
                        </span>
                        <span class="px-3 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 rounded-xl">
                            <i class="bi bi-x-circle-fill mr-1"></i> <?= $count_pending ?> Belum Input
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2.5">
                    <?php if (!empty($rundayan_input_status)): ?>
                        <?php foreach ($rundayan_input_status as $r): ?>
                            <?php 
                                $rName = $r['name'];
                                $mDetail = $rundayan_modal_data[$rName] ?? null;
                                $candList = [];
                                if ($mDetail && !empty($mDetail['candidates'])) {
                                    $cMap = [];
                                    foreach ($mDetail['candidates'] as $entry) {
                                        $cMap[$entry] = ($cMap[$entry] ?? 0) + 1;
                                    }
                                    arsort($cMap);
                                    foreach ($cMap as $cand => $cnt) {
                                        $candList[] = htmlspecialchars($cand) . ' <span class="text-emerald-400 font-bold">(' . $cnt . ' suara)</span>';
                                    }
                                }
                            ?>
                            <div class="relative group p-2.5 rounded-xl border flex flex-col justify-between transition-all <?= $r['has_input'] ? 'bg-emerald-950/40 border-emerald-500/30 text-emerald-200 hover:border-emerald-400/60' : 'bg-rose-950/30 border-rose-500/20 text-rose-300/80 hover:border-rose-400/50' ?>">
                                <div>
                                    <span class="text-xs font-bold truncate block" title="<?= htmlspecialchars($r['name']) ?>"><?= htmlspecialchars($r['name']) ?></span>
                                    <span class="text-[11px] font-semibold text-amber-300/90 block mt-0.5"><?= htmlspecialchars($r['pj']) ?></span>
                                </div>
                                <div class="mt-2 flex items-center justify-between border-t border-white/5 pt-1.5">
                                    <?php if ($r['has_input']): ?>
                                        <span class="text-[10px] font-bold tracking-wider uppercase text-emerald-400 flex items-center gap-1">
                                            <i class="bi bi-check-circle-fill text-xs"></i> Sudah
                                        </span>
                                        <span class="text-[10px] font-bold text-emerald-300/90 bg-emerald-500/10 px-1.5 py-0.5 rounded border border-emerald-500/20"><?= $r['vote_count'] ?> Suara</span>
                                    <?php else: ?>
                                        <span class="text-[10px] font-bold tracking-wider uppercase text-rose-400 flex items-center gap-1">
                                            <i class="bi bi-x-circle-fill text-xs"></i> Belum
                                        </span>
                                        <span class="text-[10px] font-medium text-white/40">0 Suara</span>
                                    <?php endif; ?>
                                </div>

                                <!-- HOVER TOOLTIP / POPOVER -->
                                <div class="fixed sm:absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 sm:top-auto sm:bottom-full sm:left-1/2 sm:-translate-x-1/2 sm:translate-y-0 sm:mb-2 w-[85vw] max-w-xs sm:w-64 p-3 bg-gray-950/95 sm:bg-gray-900/95 border border-teal-500/50 rounded-xl shadow-2xl backdrop-blur-md opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                                    <div class="font-bold text-white text-xs border-b border-white/10 pb-1.5 mb-1.5 flex items-center justify-between gap-1.5">
                                        <span class="truncate flex items-center gap-1.5"><i class="bi bi-people-fill text-amber-400"></i> <?= htmlspecialchars($r['name']) ?></span>
                                        <span class="text-[10px] text-amber-300/80 font-normal shrink-0"><?= htmlspecialchars($r['pj']) ?></span>
                                    </div>
                                    <?php if (!empty($candList)): ?>
                                        <div class="space-y-1 max-h-48 overflow-y-auto pr-1 text-[11px] text-white/90">
                                            <?php foreach ($candList as $item): ?>
                                                <div class="bg-white/5 px-2.5 py-1.5 rounded-lg border border-white/10 flex justify-between items-center gap-2">
                                                    <?= $item ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-[11px] text-white/50 text-center py-1 font-normal">Belum ada suara tercatat</div>
                                    <?php endif; ?>
                                    <!-- Tooltip Arrow (Desktop only) -->
                                    <div class="hidden sm:block absolute top-full left-1/2 -translate-x-1/2 -mt-px border-8 border-transparent border-t-gray-900/95"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RINCIAN JUMLAH PEMILIH PER-RUNDAYAN (SEKALI LIHAT AKURAT & RAPI) -->
            <div class="bg-gradient-to-br from-[#162a27] to-[#0f1e1c] border border-teal-700/40 rounded-2xl p-5 shadow-xl space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-teal-700/30 pb-3">
                    <div>
                        <h4 class="font-display font-bold text-base text-white flex items-center gap-2">
                            <i class="bi bi-people-fill text-emerald-400 text-lg"></i> Rincian Jumlah Pemilih (Orang) Per-Rundayan
                        </h4>
                        <p class="text-xs text-white/50">Daftar jumlah orang (pemilih unik) yang sudah berpartisipasi mengisi suara dari masing-masing rundayan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2.5">
                    <?php if (!empty($rundayan_input_status)): ?>
                        <?php foreach ($rundayan_input_status as $r): ?>
                            <div class="p-3 rounded-xl border bg-black/20 border-white/10 flex flex-col justify-between space-y-2">
                                <div>
                                    <span class="text-xs font-bold text-white truncate block" title="<?= htmlspecialchars($r['name']) ?>"><?= htmlspecialchars($r['name']) ?></span>
                                </div>
                                <div class="border-t border-white/10 pt-1.5 flex items-center justify-between">
                                    <span class="text-[10px] text-white/60 font-semibold">Pemilih:</span>
                                    <span class="text-xs font-black <?= $r['voter_count'] > 0 ? 'text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20' : 'text-white/40' ?>">
                                        <?= $r['voter_count'] ?> Orang
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$show_only): ?>
            <!-- SECTION: CHART 3D PIE (REKAPITULASI DUKUNGAN ADMIN PALING ATAS) -->
            <div class="bg-gradient-to-b from-[#182c29] to-[#122220] border border-teal-700/40 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-teal-700/30 pb-4">
                    <div>
                        <h3 class="font-display font-bold text-xl text-white flex items-center gap-2">
                            <i class="bi bi-pie-chart-fill text-amber-400"></i> Chart 3D Pie Rekapitulasi Suara
                        </h3>
                        <p class="text-xs text-white/60 mt-0.5">Grafik 3D perolehan suara pencalonan ketua yayasan.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Chart Type Switcher Buttons -->
                        <div class="flex bg-black/40 p-1 rounded-xl border border-white/10 text-xs">
                            <button id="btn_chart_individu" onclick="switchChart('individu')" class="px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow">
                                Individu
                            </button>
                            <button id="btn_chart_rundayan" onclick="switchChart('rundayan')" class="px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all">
                                Rundayan
                            </button>
                        </div>
                        <!-- Fullscreen Button -->
                        <button onclick="openChartFullscreen()" title="Fullscreen" class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 hover:bg-white/20 border border-white/15 rounded-xl text-white text-xs font-semibold transition-all">
                            <i class="bi bi-fullscreen text-sm"></i>
                            <span class="hidden sm:inline">Fullscreen</span>
                        </button>
                    </div>
                </div>

                <div class="relative min-h-[380px] flex flex-col items-center justify-center w-full">
                    <div id="container_chart_3d" class="w-full h-[400px]" style="touch-action: manipulation;"></div>
                    
                    <!-- CUSTOM GROUPED HTML LEGEND -->
                    <div id="custom_grouped_legend_container" class="w-full mt-4 space-y-3 shrink-0"></div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Rekapitulasi Hasil Pencalonan (Admin Only) -->
            <div class="mt-4 space-y-6">
                <?php if (!$show_only): ?>
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Rekapitulasi Hasil Pencalonan</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Hasil pengelompokan calon ketua yayasan beserta rincian pemilih per keturunan/rundayan.</p>
                </div>
                <?php endif; ?>

                <!-- 1. INDIVIDU TABLE -->
                <?php if (!$show_only || $show_only === 'individu'): ?>
                <div class="bg-gradient-to-b from-[#1b3638] to-[#122829] border border-amber-500/20 rounded-2xl p-6 shadow-xl space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-lg font-bold text-amber-300 flex items-center gap-2">
                            <i class="bi bi-person-fill"></i> Tabel Pencalonan Individu (Rekap)
                        </h3>
                        <!-- Search Individu -->
                        <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="flex gap-2 w-full sm:max-w-xs">
                            <?php 
                            foreach ($_GET as $key => $val) {
                                if ($key !== 'search_individu' && $key !== 'page_individu') {
                                    echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                                }
                            }
                            ?>
                            <div class="relative flex-1">
                                <i class="bi bi-search absolute left-3 top-2.5 text-white/40 text-xs"></i>
                                <input type="text" name="search_individu" id="input_search_individu" value="<?= htmlspecialchars($search_individu ?? '') ?>" autocomplete="off" placeholder="Cari rekap individu..." class="w-full bg-[#1A2824]/50 border border-[#4D6B67]/30 rounded-xl py-2 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                                <div id="search_individu_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
                            <button type="submit" class="px-3.5 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white rounded-xl flex items-center justify-center transition-all py-2 text-xs">
                                Cari
                            </button>
                        </form>
                    </div>

                    <?php if (empty($individu_candidates)): ?>
                        <p class="text-white/40 text-sm italic">Belum ada data pencalonan individu yang approved.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table id="table_individu" class="w-full text-left border-collapse" style="min-width: 800px;">
                                <thead>
                                    <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-wider">
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">No. Urut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Nama Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Sebagai Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Pencalon / Nominator</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Rundayan / Buyut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap text-emerald-400">Rincian Pemilih</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-sm">
                                    <?php foreach ($individu_candidates as $index => $c): ?>
                                        <tr>
                                            <td class="py-3.5 pr-6 text-white/55 whitespace-nowrap">#<?= ($page_individu === 'all' ? $index + 1 : (($page_individu - 1) * $limit_individu) + $index + 1) ?></td>
                                            <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap">
                                                <?= htmlspecialchars($c['candidate_name']) ?>
                                                <span class="ml-1.5 text-xs text-amber-300 font-semibold">(<?= $c['votes_count'] ?> suara)</span>
                                            </td>
                                            <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                                 <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-300 border border-amber-500/25">
                                                     <?= htmlspecialchars($c['roles_text']) ?>
                                                 </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 max-w-[280px]">
                                                <div class="line-clamp-2 transition-all cursor-pointer text-white/90 hover:text-amber-300" onclick="showTextModal('Pengusul / Nominator', '<?= htmlspecialchars(addslashes($c['nominator_name'])) ?>')">
                                                    <?= htmlspecialchars($c['nominator_name']) ?>
                                                </div>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 max-w-[200px]">
                                                <span class="rundayan-click text-emerald-300 hover:text-emerald-200 font-semibold block truncate cursor-pointer underline decoration-emerald-500/40" onclick="showRundayanModal('<?= htmlspecialchars(addslashes($c['ancestor_name'])) ?>')">
                                                    <?= htmlspecialchars($c['ancestor_name']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-emerald-400 font-semibold max-w-[240px]">
                                                <div class="line-clamp-2 transition-all cursor-pointer hover:text-emerald-300" onclick="showTextModal('Rincian Pemilih', '<?= htmlspecialchars(addslashes(strip_tags($c['breakdown_text']))) ?>')">
                                                    <?= $c['breakdown_text'] ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Rekap Individu -->
                        <div class="mt-4 flex flex-col items-center justify-between gap-4 border-t border-white/5 pt-4 sm:flex-row">
                            <span class="text-xs text-white/55">
                                Menampilkan <?= count($individu_candidates) ?> dari <?= $total_rows_individu ?> data rekap individu
                            </span>
                            <?= render_custom_pagination($total_rows_individu, $limit_individu, $page_individu, 'page_individu', $page_individu === 'all') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- 2. RUNDAYAN TABLE -->
                <?php if (!$show_only || $show_only === 'rundayan'): ?>
                <div class="bg-gradient-to-b from-[#112d30] to-[#0c1f21] border border-cyan-500/20 rounded-2xl p-6 shadow-xl space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-lg font-bold text-cyan-300 flex items-center gap-2">
                            <i class="bi bi-people-fill"></i> Tabel Pencalonan Rundayan (Rekap)
                        </h3>
                        <!-- Search Rundayan -->
                        <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="flex gap-2 w-full sm:max-w-xs">
                            <?php 
                            foreach ($_GET as $key => $val) {
                                if ($key !== 'search_rundayan' && $key !== 'page_rundayan') {
                                    echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                                }
                            }
                            ?>
                            <div class="relative flex-1">
                                <i class="bi bi-search absolute left-3 top-2.5 text-white/40 text-xs"></i>
                                <input type="text" name="search_rundayan" id="input_search_rundayan" value="<?= htmlspecialchars($search_rundayan ?? '') ?>" autocomplete="off" placeholder="Cari rekap rundayan..." class="w-full bg-[#1A2824]/50 border border-[#4D6B67]/30 rounded-xl py-2 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                                <div id="search_rundayan_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
                            <button type="submit" class="px-3.5 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white rounded-xl flex items-center justify-center transition-all py-2 text-xs">
                                Cari
                            </button>
                        </form>
                    </div>

                    <?php if (empty($rundayan_candidates)): ?>
                        <p class="text-white/40 text-sm italic">Belum ada data pencalonan rundayan yang approved.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table id="table_rundayan" class="w-full text-left border-collapse" style="min-width: 800px;">
                                <thead>
                                    <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-wider">
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">No. Urut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Nama Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Sebagai Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Pencalon / Nominator</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Rundayan / Buyut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap text-emerald-400">Rincian Pemilih</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-sm">
                                    <?php foreach ($rundayan_candidates as $index => $c): ?>
                                        <tr>
                                            <td class="py-3.5 pr-6 text-white/55 whitespace-nowrap">#<?= ($page_rundayan === 'all' ? $index + 1 : (($page_rundayan - 1) * $limit_rundayan) + $index + 1) ?></td>
                                            <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap">
                                                <?= htmlspecialchars($c['candidate_name']) ?>
                                                <span class="ml-1.5 text-xs text-cyan-300 font-semibold">(<?= $c['votes_count'] ?> suara)</span>
                                            </td>
                                            <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                                 <span class="px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-300 border border-cyan-500/25">
                                                     <?= htmlspecialchars($c['roles_text']) ?>
                                                 </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 max-w-[280px]">
                                                 <div class="line-clamp-2 transition-all cursor-pointer text-white/90 hover:text-cyan-300" onclick="showTextModal('Pengusul / Nominator', '<?= htmlspecialchars(addslashes($c['nominator_name'])) ?>')">
                                                     <?= htmlspecialchars($c['nominator_name']) ?>
                                                 </div>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 max-w-[200px]">
                                                <span class="rundayan-click text-cyan-300 hover:text-cyan-200 font-semibold block truncate cursor-pointer underline decoration-cyan-500/40" onclick="showRundayanModal('<?= htmlspecialchars(addslashes($c['ancestor_name'])) ?>')">
                                                    <?= htmlspecialchars($c['ancestor_name']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-cyan-400 font-semibold max-w-[240px]">
                                                 <div class="line-clamp-2 transition-all cursor-pointer hover:text-cyan-300" onclick="showTextModal('Rincian Pemilih', '<?= htmlspecialchars(addslashes(strip_tags($c['breakdown_text']))) ?>')">
                                                     <?= $c['breakdown_text'] ?>
                                                 </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Rekap Rundayan -->
                        <div class="mt-4 flex flex-col items-center justify-between gap-4 border-t border-white/5 pt-4 sm:flex-row">
                            <span class="text-xs text-white/55">
                                Menampilkan <?= count($rundayan_candidates) ?> dari <?= $total_rows_rundayan ?> data rekap rundayan
                            </span>
                            <?= render_custom_pagination($total_rows_rundayan, $limit_rundayan, $page_rundayan, 'page_rundayan', $page_rundayan === 'all') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!$show_only): ?>
            <!-- Bagan Silsilah Pencalonan (Admin Only) -->
            <div class="mt-12 space-y-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div>
                            <h2 class="font-display font-extrabold text-2xl text-white">Bagan Silsilah Pencalonan (Rekap)</h2>
                            <p class="text-brand-light/70 text-xs mt-1">Struktur bagan hubungan pengusul dan calon ketua berdasarkan rundayan masing-masing.</p>
                        </div>
                        <!-- Toggle Switcher Individu / Rundayan / Lihat Semua -->
                        <div class="flex bg-black/40 p-1 rounded-xl border border-white/10 text-xs shrink-0 self-start sm:self-auto">
                            <button id="btn_bagan_type_individu" onclick="switchBaganType('individu')" class="px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow">
                                Individu
                            </button>
                            <button id="btn_bagan_type_rundayan" onclick="switchBaganType('rundayan')" class="px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all">
                                Rundayan
                            </button>
                            <button id="btn_bagan_type_all" onclick="switchBaganType('all')" class="px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all">
                                Lihat Semua
                            </button>
                        </div>
                    </div>
                    <!-- Search Bagan -->
                    <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="flex gap-2 w-full sm:max-w-xs">
                        <?php 
                        foreach ($_GET as $key => $val) {
                            if ($key !== 'search_bagan') {
                                echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                            }
                        }
                        ?>
                        <div class="relative flex-1">
                            <i class="bi bi-search absolute left-3 top-2.5 text-white/40 text-xs"></i>
                            <input type="text" name="search_bagan" id="input_search_bagan" value="<?= htmlspecialchars($search_bagan ?? '') ?>" autocomplete="off" placeholder="Cari di silsilah..." class="w-full bg-[#1A2824]/50 border border-[#4D6B67]/30 rounded-xl py-2 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                            <div id="search_bagan_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                        </div>
                        <button type="submit" class="px-3.5 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white rounded-xl flex items-center justify-center transition-all py-2 text-xs">
                            Cari
                        </button>
                    </form>
                </div>

                <div class="space-y-8">
                    <?php 
                        $grouped_bagan_individu = [];
                        $grouped_bagan_rundayan = [];
                        foreach ($approved_candidates as $c) {
                            $c_type = $c['type'] ?? 'individu';
                            if ($c_type === 'rundayan') {
                                $grouped_bagan_rundayan[$c['ancestor_name']][] = $c;
                            } else {
                                $grouped_bagan_individu[$c['ancestor_name']][] = $c;
                            }
                        }
                    ?>
                    
                    <!-- CONTAINER BAGAN INDIVIDU -->
                    <div id="container_bagan_individu" class="space-y-8">
                        <?php if (empty($grouped_bagan_individu)): ?>
                            <div class="bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-[#4D6B67]/20 rounded-2xl p-6 text-center text-white/40 text-sm italic">
                                Belum ada bagan pencalonan kategori Individu.
                            </div>
                        <?php else: ?>
                            <?php foreach ($grouped_bagan_individu as $ancestor => $cand_list): ?>
                                <div class="bagan-ancestor-card bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-[#4D6B67]/20 rounded-2xl p-6 shadow-xl">
                                    <h3 class="text-xl font-bold text-emerald-300 border-b border-[#4D6B67]/20 pb-3 mb-6 flex items-center gap-2">
                                        <i class="bi bi-diagram-3-fill"></i> Rundayan: 
                                        <span class="rundayan-click text-white hover:text-emerald-300 cursor-pointer underline decoration-emerald-500/50" onclick="showRundayanModal('<?= htmlspecialchars(addslashes($ancestor)) ?>')">
                                            <?= htmlspecialchars($ancestor) ?>
                                        </span>
                                    </h3>
                                    
                                    <?php 
                                        $tree_data = build_nomination_trees($cand_list); 
                                        $roots = $tree_data['roots'];
                                        $children = $tree_data['children'];
                                    ?>
                                    
                                    <div class="overflow-x-auto pb-4">
                                        <div class="flex flex-col gap-6" style="min-width: 600px;">
                                            <?php foreach ($roots as $nominator => $root_cands): ?>
                                                <div class="flex flex-col gap-4 pl-4 border-l-2 border-emerald-500/30">
                                                    <div class="flex items-center gap-2">
                                                        <span class="px-2.5 py-1 rounded-xl bg-white/5 border border-white/10 text-[10px] font-bold text-white/55 uppercase tracking-wider">Anggota Keluarga Samhudi</span>
                                                        <strong class="text-white text-sm font-semibold"><?= htmlspecialchars($nominator) ?></strong>
                                                    </div>
                                                    
                                                    <div class="flex flex-col gap-3 pl-6">
                                                        <?php foreach ($root_cands as $rc): ?>
                                                            <?php render_tree_node($rc, $children); ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- CONTAINER BAGAN RUNDAYAN -->
                    <div id="container_bagan_rundayan" class="space-y-8 hidden">
                        <?php if (empty($grouped_bagan_rundayan)): ?>
                            <div class="bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-[#4D6B67]/20 rounded-2xl p-6 text-center text-white/40 text-sm italic">
                                Belum ada bagan pencalonan kategori Rundayan.
                            </div>
                        <?php else: ?>
                            <?php foreach ($grouped_bagan_rundayan as $ancestor => $cand_list): ?>
                                <div class="bagan-ancestor-card bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-[#4D6B67]/20 rounded-2xl p-6 shadow-xl">
                                    <h3 class="text-xl font-bold text-cyan-300 border-b border-[#4D6B67]/20 pb-3 mb-6 flex items-center gap-2">
                                        <i class="bi bi-diagram-3-fill"></i> Rundayan: 
                                        <span class="rundayan-click text-white hover:text-cyan-300 cursor-pointer underline decoration-cyan-500/50" onclick="showRundayanModal('<?= htmlspecialchars(addslashes($ancestor)) ?>')">
                                            <?= htmlspecialchars($ancestor) ?>
                                        </span>
                                    </h3>
                                    
                                    <?php 
                                        $tree_data = build_nomination_trees($cand_list); 
                                        $roots = $tree_data['roots'];
                                        $children = $tree_data['children'];
                                    ?>
                                    
                                    <div class="overflow-x-auto pb-4">
                                        <div class="flex flex-col gap-6" style="min-width: 600px;">
                                            <?php foreach ($roots as $nominator => $root_cands): ?>
                                                <div class="flex flex-col gap-4 pl-4 border-l-2 border-cyan-500/30">
                                                    <div class="flex items-center gap-2">
                                                        <span class="px-2.5 py-1 rounded-xl bg-white/5 border border-white/10 text-[10px] font-bold text-white/55 uppercase tracking-wider">Anggota Keluarga Samhudi</span>
                                                        <strong class="text-white text-sm font-semibold"><?= htmlspecialchars($nominator) ?></strong>
                                                    </div>
                                                    
                                                    <div class="flex flex-col gap-3 pl-6">
                                                        <?php foreach ($root_cands as $rc): ?>
                                                            <?php render_tree_node($rc, $children, 'cyan'); ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; // end !$show_only (bagan silsilah) ?>

        </div>

    </main>
    
    <!-- Modal Confirm -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
        <div class="bg-[#1A2824] border border-[#4D6B67]/30 rounded-2xl p-6 max-w-sm w-full shadow-2xl transform transition-all scale-95 opacity-0" id="confirmModalCard">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-brand-red/20 flex items-center justify-center text-brand-red border border-brand-red/30">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h3 class="text-lg font-bold text-white" id="confirmTitle">Konfirmasi</h3>
            </div>
            <p class="text-sm text-white/70 mb-6 leading-relaxed" id="confirmMessage">Apakah Anda yakin?</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 rounded-xl text-sm font-semibold text-white/70 hover:text-white hover:bg-white/10 transition-colors">Batal</button>
                <button type="button" id="confirmActionBtn" class="px-4 py-2 rounded-xl text-sm font-semibold bg-brand-red text-white hover:bg-red-600 transition-colors shadow-lg shadow-brand-red/20">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- MODAL QR CODE DEWAN PEMBINA -->
    <?php $pembina_url = base_url('yayasan/rekapitulasi'); ?>
    <div id="qrModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-[#152421] border border-amber-500/40 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl transform transition-all scale-95 opacity-0 relative text-center space-y-6" id="qrModalCard">
            <button onclick="closeQrModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 text-white/70 hover:text-white flex items-center justify-center transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="space-y-2">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                    QR Code Laporan Dewan Pembina
                </span>
                <h3 class="font-display font-extrabold text-xl text-white">Akses Rekapitulasi Pembina</h3>
                <p class="text-xs text-white/60">Scan QR Code ini menggunakan HP Dewan Pembina untuk membuka laporan rekapitulasi real-time.</p>
            </div>

            <!-- QR Code Render Box -->
            <div class="flex flex-col items-center justify-center bg-white p-6 rounded-2xl shadow-inner border border-amber-500/30 mx-auto w-max">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($pembina_url) ?>" alt="QR Code Dewan Pembina" id="qrCodeImage" class="w-48 h-48 rounded">
            </div>

            <div class="pt-2">
                <button type="button" onclick="downloadQrCodeImage()" id="downloadQrBtn" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-teal-950 font-display font-extrabold rounded-xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                    <i class="bi bi-download text-sm"></i> <span id="downloadBtnText">Download QR Code</span>
                </button>
            </div>
        </div>
    </div>

    <!-- INTERACTIVE POPUP MODAL FOR RUNDAYAN DETAIL -->
    <div id="rundayanDetailModal" class="fixed inset-0 z-[12000] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all duration-200">
        <div id="rundayanDetailModalCard" class="bg-[#142623] border border-emerald-500/40 rounded-3xl p-6 shadow-2xl max-w-md w-full text-left transition-all duration-200 transform scale-95 opacity-0 space-y-4">
            <div class="flex items-center justify-between border-b border-emerald-500/20 pb-3">
                <div class="flex items-center gap-2">
                    <i class="bi bi-diagram-3-fill text-emerald-400 text-lg"></i>
                    <h4 class="font-display font-bold text-base text-white" id="modal_rundayan_title">Detail Rundayan</h4>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30" id="modal_rundayan_votes">
                        0 Suara
                    </span>
                    <button type="button" onclick="closeRundayanModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-4 text-xs">
                <div>
                    <span class="text-white/40 uppercase tracking-wider font-bold block mb-1.5 flex items-center gap-1.5">
                        <i class="bi bi-people-fill text-emerald-400"></i> Pengusul / Nominator:
                    </span>
                    <div class="text-white font-medium bg-black/40 rounded-2xl p-3.5 leading-relaxed border border-white/5 max-h-48 overflow-y-auto break-words select-text" id="modal_rundayan_nominators">-</div>
                </div>
                <div>
                    <span class="text-white/40 uppercase tracking-wider font-bold block mb-1.5 flex items-center gap-1.5">
                        <i class="bi bi-person-badge-fill text-amber-400"></i> Calon yang Diusulkan:
                    </span>
                    <div class="text-amber-300 font-medium bg-black/40 rounded-2xl p-3.5 leading-relaxed border border-white/5 max-h-32 overflow-y-auto break-words select-text" id="modal_rundayan_candidates">-</div>
                </div>
            </div>

            <div class="pt-2">
                <button type="button" onclick="closeRundayanModal()" class="w-full py-2.5 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 font-bold rounded-xl text-xs transition-all border border-emerald-500/30">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- INTERACTIVE POPUP MODAL FOR GENERAL TEXT DETAIL -->
    <div id="generalTextModal" class="fixed inset-0 z-[12000] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all duration-200">
        <div id="generalTextModalCard" class="bg-[#142623] border border-amber-500/40 rounded-3xl p-6 shadow-2xl max-w-md w-full text-left transition-all duration-200 transform scale-95 opacity-0 space-y-4">
            <div class="flex items-center justify-between border-b border-amber-500/20 pb-3">
                <div class="flex items-center gap-2">
                    <i class="bi bi-file-text-fill text-amber-400 text-lg"></i>
                    <h4 class="font-display font-bold text-base text-white" id="modal_text_title">Detail Informasi</h4>
                </div>
                <button type="button" onclick="closeTextModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 text-white/60 hover:text-white flex items-center justify-center transition-all">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
            
            <div class="text-xs">
                <div class="text-white font-medium bg-black/40 rounded-2xl p-4 leading-relaxed border border-white/5 max-h-60 overflow-y-auto break-words select-text" id="modal_text_content">-</div>
            </div>

            <div class="pt-2">
                <button type="button" onclick="closeTextModal()" class="w-full py-2.5 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 font-bold rounded-xl text-xs transition-all border border-amber-500/30">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script>
        // Modal Confirm
        function showConfirm(event, url, message) {
            event.preventDefault();
            const modal = document.getElementById('confirmModal');
            const card = document.getElementById('confirmModalCard');
            document.getElementById('confirmMessage').innerText = message;
            const actionBtn = document.getElementById('confirmActionBtn');
            actionBtn.onclick = function() { window.location.href = url; };
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const card = document.getElementById('confirmModalCard');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 200);
        }

        // Modal QR Code Dewan Pembina
        function openQrModal() {
            const modal = document.getElementById('qrModal');
            const card = document.getElementById('qrModalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeQrModal() {
            const modal = document.getElementById('qrModal');
            const card = document.getElementById('qrModalCard');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 200);
        }

        function downloadQrCodeImage() {
            const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" + encodeURIComponent("<?= $pembina_url ?>");
            const btnText = document.getElementById('downloadBtnText');
            if (btnText) btnText.innerText = 'Mengunduh...';

            fetch(qrUrl)
                .then(res => res.blob())
                .then(blob => {
                    const blobUrl = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = 'QR_Code_Dewan_Pembina.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(blobUrl);
                    if (btnText) btnText.innerText = 'Download QR Code';
                })
                .catch(() => {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        const link = document.createElement('a');
                        link.href = canvas.toDataURL('image/png');
                        link.download = 'QR_Code_Dewan_Pembina.png';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        if (btnText) btnText.innerText = 'Download QR Code';
                    };
                    img.src = qrUrl;
                });
        }

        // Chart 3D Pie Script
        const chartDataIndividu = <?= json_encode($chart_data_individu ?? []) ?>;
        const chartDataRundayan = <?= json_encode($chart_data_rundayan ?? []) ?>;
        const rundayanDetailMap = <?= json_encode($rundayan_detail_map ?? []) ?>;
        const rundayanModalData = <?= json_encode($rundayan_modal_data ?? []) ?>;

        let highChartInstance = null;

        function openRundayanModal(rundayanName) {
            const modal   = document.getElementById('modal_rundayan_detail');
            const titleEl = document.getElementById('modal_rundayan_title');
            const bodyEl  = document.getElementById('modal_rundayan_body');

            titleEl.textContent = rundayanName;

            const detail = rundayanModalData[rundayanName];

            if (!detail || !detail.candidates || detail.candidates.length === 0) {
                bodyEl.innerHTML = '<div class="text-xs text-white/50 text-center py-6"><i class="bi bi-inbox text-3xl block mb-2 opacity-30"></i>Belum ada suara yang tercatat dari rundayan ini</div>';
            } else {
                const countMap = {};
                detail.candidates.forEach(entry => {
                    countMap[entry] = (countMap[entry] || 0) + 1;
                });
                const sorted = Object.entries(countMap).sort((a, b) => b[1] - a[1]);
                let html = '<div class="text-xs text-white/60 mb-1 font-medium">Total: <strong class="text-cyan-300">' + detail.total_votes + ' suara</strong></div>';
                sorted.forEach(([cand, count]) => {
                    html += '<div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl px-3 py-2">';
                    html += '<span class="text-white font-semibold text-xs">' + cand + '</span>';
                    html += '<span class="text-emerald-400 font-bold text-xs bg-emerald-500/10 px-2 py-0.5 rounded-lg border border-emerald-500/20">' + count + ' suara</span>';
                    html += '</div>';
                });
                bodyEl.innerHTML = html;
            }

            modal.classList.remove('hidden');
        }

        function closeRundayanModal() {
            document.getElementById('modal_rundayan_detail').classList.add('hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRundayanModal();
        });


        function getDistinctColorsForData(dataSeries) {
            const count = dataSeries ? dataSeries.length : 0;
            if (count === 0) return ['#10B981'];

            // Maximum contrast high-vibrancy distinct color generator (Golden Ratio Hue distribution)
            const colors = [];
            const goldenRatioConjugate = 0.618033988749895;
            let h = 0.15; // Start near vibrant amber/gold

            for (let i = 0; i < count; i++) {
                h += goldenRatioConjugate;
                h %= 1;
                const hue = Math.round(h * 360);
                // Keep saturation extremely high (85%-100%) and lightness bright (52%-62%) for maximum neon contrast against dark theme
                const saturation = 90 + (i % 2) * 10;
                const lightness  = 52 + (i % 3) * 5;
                colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
            }
            return colors;
        }

        function render3DPieChart(dataSeries, titleText) {
            highChartInstance = Highcharts.chart('container_chart_3d', {
                exporting: { enabled: false },
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    },
                    backgroundColor: 'transparent',
                    events: {
                        click: function() {
                            if (this.tooltip) this.tooltip.hide();
                            if (this.getSelectedPoints) {
                                this.getSelectedPoints().forEach(p => p.select(false));
                            }
                        }
                    }
                },
                title: {
                    text: titleText,
                    style: {
                        color: '#FFFFFF',
                        fontFamily: 'Plus Jakarta Sans',
                        fontWeight: '700',
                        fontSize: '16px'
                    }
                },
                subtitle: {
                    text: 'Arahkan kursor ke grafik untuk melihat rincian pemilih & pengusul',
                    style: { color: 'rgba(255,255,255,0.7)', fontSize: '11px' }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: window.innerWidth < 640 ? 30 : 35,
                        size: window.innerWidth < 640 ? '90%' : '75%',
                        showInLegend: true,
                        dataLabels: {
                            enabled: true,
                            distance: 25,
                            connectorWidth: 1.5,
                            connectorColor: 'rgba(255, 255, 255, 0.4)',
                            format: '{point.name}<br><span style="color:#34d399;font-weight:600;">{point.y} suara ({point.percentage:.1f}%)</span>',
                            style: {
                                color: '#FFFFFF',
                                textOutline: 'none',
                                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                                fontSize: '11px',
                                fontWeight: '400'
                            }
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    enabled: true,
                    useHTML: true,
                    backgroundColor: '#152421',
                    borderColor: '#4D6B67',
                    borderRadius: 12,
                    style: { color: '#FFFFFF', fontFamily: 'Inter', fontSize: '12px' },
                    formatter: function() {
                        const p = this.point;
                        let html = `<div style="padding: 4px 6px;">`;
                        html += `<div style="font-weight: 800; font-size: 14px; color: #D4B571; margin-bottom: 4px;">${p.name}</div>`;
                        if (p.roles) {
                            html += `<div style="margin-bottom: 4px;"><b>Sebagai Calon:</b> <span style="color:#f59e0b; font-weight:700;">${p.roles}</span></div>`;
                        }
                        html += `<div><b>Total Suara:</b> <span style="color:#10b981;">${p.y} Suara (${p.percentage.toFixed(1)}%)</span></div>`;
                        if (p.nominators) {
                            html += `<div style="margin-top: 4px;"><b>Pengusul / Pemilih:</b> <span style="color:#e2e8f0;">${p.nominators}</span></div>`;
                        }
                        if (p.breakdown) {
                            html += `<div style="margin-top: 2px;"><b>Rincian:</b> <span style="color:#38bdf8;">${p.breakdown}</span></div>`;
                        }
                        html += `</div>`;
                        return html;
                    }
                },
                colors: getDistinctColorsForData(dataSeries),
                series: [{
                    name: 'Dukungan',
                    data: dataSeries
                }]
            });
            renderCustomGroupedLegend(dataSeries, 'custom_grouped_legend_container');
        }

        function renderCustomGroupedLegend(dataSeries, containerId) {
            const el = document.getElementById(containerId);
            if (!el) return;

            const grouped = {
                'Ketua': [],
                'Sekretaris': [],
                'Bendahara': []
            };

            (dataSeries || []).forEach(item => {
                if (!item || !item.name) return;

                const nameUpper = item.name.trim().toUpperCase();
                if (nameUpper === 'KETUA' || nameUpper === 'SEKRETARIS' || nameUpper === 'BENDAHARA') return;

                // role_counts = { 'Ketua': 42, 'Bendahara': 2, ... } dari backend
                const roleCounts = item.role_counts || {};
                const roleStr    = (item.roles || '').toString();

                // Kalau punya role_counts dari backend, pakai itu (akurat per jabatan)
                const hasRoleCounts = Object.keys(roleCounts).length > 0;

                const addToGroup = (roleKey, votes) => {
                    grouped[roleKey].push({ name: item.name, votes: votes });
                };

                if (hasRoleCounts) {
                    Object.entries(roleCounts).forEach(([role, count]) => {
                        const r = role.trim();
                        if (grouped[r] !== undefined) {
                            addToGroup(r, count);
                        }
                    });
                } else {
                    // Fallback: pakai total y, kelompokkan dari roleStr
                    let matched = false;
                    if (roleStr.indexOf('Ketua') !== -1 || roleStr.indexOf('KETUA') !== -1) {
                        addToGroup('Ketua', item.y || 0); matched = true;
                    }
                    if (roleStr.indexOf('Sekretaris') !== -1 || roleStr.indexOf('SEKRETARIS') !== -1) {
                        addToGroup('Sekretaris', item.y || 0); matched = true;
                    }
                    if (roleStr.indexOf('Bendahara') !== -1 || roleStr.indexOf('BENDAHARA') !== -1) {
                        addToGroup('Bendahara', item.y || 0); matched = true;
                    }
                    if (!matched) addToGroup('Ketua', item.y || 0);
                }
            });

            const rolesOrder = ['Ketua', 'Sekretaris', 'Bendahara'];
            let html = '';

            rolesOrder.forEach(roleKey => {
                const list = grouped[roleKey];
                if (list && list.length > 0) {
                    // Sort by votes descending
                    list.sort((a, b) => b.votes - a.votes);

                    html += `<div class="bg-black/30 border border-teal-700/30 rounded-xl p-3.5 space-y-2">`;
                    html += `<div class="font-bold text-amber-400 text-sm flex items-center gap-1.5 border-b border-white/10 pb-1.5">`;
                    html += `<i class="bi bi-person-badge-fill"></i> ${roleKey} :</div>`;
                    html += `<div class="text-xs text-white/90 leading-relaxed font-medium">`;

                    html += list.map(entry =>
                        `<span class="inline-block bg-white/5 hover:bg-white/10 border border-white/10 px-2.5 py-1 rounded-lg mr-1.5 mb-1.5 transition-all"><strong class="text-white">${entry.name}</strong> <span class="text-emerald-400 font-bold">(${entry.votes} suara)</span></span>`
                    ).join('');

                    html += `</div></div>`;
                }
            });

            el.innerHTML = html || '<div class="text-xs text-white/50 text-center py-2">Belum ada data kandidat</div>';
        }

        function switchChart(type) {
            const btnIndividu = document.getElementById('btn_chart_individu');
            const btnRundayan = document.getElementById('btn_chart_rundayan');

            if (type === 'individu') {
                btnIndividu.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow";
                btnRundayan.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                render3DPieChart(chartDataIndividu, 'Perolehan Suara Kandidat Individu');
            } else {
                btnRundayan.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-cyan-500 text-white shadow";
                btnIndividu.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                render3DPieChart(chartDataRundayan, 'Perolehan Suara Kandidat Rundayan');
            }
        }

        function switchBaganType(type) {
            const btnIndividu = document.getElementById('btn_bagan_type_individu');
            const btnRundayan = document.getElementById('btn_bagan_type_rundayan');
            const btnAll = document.getElementById('btn_bagan_type_all');
            const containerIndividu = document.getElementById('container_bagan_individu');
            const containerRundayan = document.getElementById('container_bagan_rundayan');

            if (!containerIndividu || !containerRundayan) return;

            const activeClassIndividu = "px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow";
            const activeClassRundayan = "px-4 py-1.5 rounded-lg font-bold transition-all bg-cyan-500 text-white shadow";
            const activeClassAll      = "px-4 py-1.5 rounded-lg font-bold transition-all bg-amber-500 text-white shadow";
            const inactiveClass       = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";

            if (type === 'individu') {
                btnIndividu.className = activeClassIndividu;
                btnRundayan.className = inactiveClass;
                if (btnAll) btnAll.className = inactiveClass;

                containerIndividu.classList.remove('hidden');
                containerRundayan.classList.add('hidden');
            } else if (type === 'rundayan') {
                btnRundayan.className = activeClassRundayan;
                btnIndividu.className = inactiveClass;
                if (btnAll) btnAll.className = inactiveClass;

                containerRundayan.classList.remove('hidden');
                containerIndividu.classList.add('hidden');
            } else {
                if (btnAll) btnAll.className = activeClassAll;
                btnIndividu.className = inactiveClass;
                btnRundayan.className = inactiveClass;

                containerIndividu.classList.remove('hidden');
                containerRundayan.classList.remove('hidden');
            }
        }

        // FULLSCREEN CHART
        let fsChartInstance = null;
        let currentChartType = 'individu';

        function render3DPieChartFS(dataSeries, titleText) {
            if (fsChartInstance) { fsChartInstance.destroy(); fsChartInstance = null; }

            const isMobile = window.innerWidth < 640;

            fsChartInstance = Highcharts.chart('container_chart_3d_fs', {
                exporting: { enabled: false },
                chart: {
                    type: 'pie',
                    options3d: { enabled: true, alpha: 45, beta: 0 },
                    backgroundColor: 'transparent',
                    animation: { duration: 400 },
                    events: {
                        click: function() {
                            if (this.tooltip) this.tooltip.hide();
                            if (this.getSelectedPoints) {
                                this.getSelectedPoints().forEach(p => p.select(false));
                            }
                        }
                    }
                },
                title: {
                    text: titleText,
                    style: { 
                        color: '#FFFFFF', 
                        fontFamily: 'Plus Jakarta Sans', 
                        fontWeight: '800', 
                        fontSize: isMobile ? '15px' : '22px' 
                    }
                },
                tooltip: {
                    enabled: true,
                    pointFormat: '<b>{point.name}</b><br>Suara: <b>{point.y}</b> ({point.percentage:.1f}%)<br>Pendukung: <b>{point.nominators}</b><br>Rundayan: <b>{point.ancestors}</b>',
                    backgroundColor: 'rgba(15,30,28,0.95)',
                    borderColor: '#4D6B67',
                    style: { color: '#FFFFFF', fontSize: isMobile ? '13px' : '15px' }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: isMobile ? 40 : 45,
                        size: isMobile ? '90%' : '80%',
                        innerSize: '0%',
                        showInLegend: true,
                        dataLabels: {
                            enabled: !isMobile,
                            format: '{point.name}<br>{point.y} suara ({point.percentage:.1f}%)',
                            style: { color: '#FFFFFF', textOutline: 'none', fontFamily: 'sans-serif', fontSize: '11px', fontWeight: '400' }
                        }
                    }
                },
                legend: {
                    enabled: true,
                    labelFormat: isMobile ? '<b>{name}</b>: {y} suara ({percentage:.0f}%)' : '{name}',
                    itemStyle: { color: '#FFFFFF', fontSize: isMobile ? '12px' : '13px', fontWeight: '600' },
                    itemHoverStyle: { color: '#10B981' }
                },
                colors: getDistinctColorsForData(dataSeries),
                series: [{ name: 'Dukungan', data: dataSeries }]
            });
            setTimeout(() => { updateCustomLegendPaginationUI(true); }, 50);
        }

        function fsSwitchChart(type) {
            currentChartType = type;
            const fsBtnI = document.getElementById('fs_btn_individu');
            const fsBtnR = document.getElementById('fs_btn_rundayan');
            if (type === 'individu') {
                fsBtnI.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow";
                fsBtnR.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                render3DPieChartFS(chartDataIndividu, 'Perolehan Suara Kandidat Individu');
            } else {
                fsBtnR.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-cyan-500 text-white shadow";
                fsBtnI.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                render3DPieChartFS(chartDataRundayan, 'Perolehan Suara Kandidat Rundayan');
            }
        }

        // CUSTOM LEGEND PAGINATION HANDLERS
        function scrollLegendPage(isFS, direction) {
            const chartObj = isFS ? fsChartInstance : highChartInstance;
            if (!chartObj || !chartObj.legend || !chartObj.legend.pages) return;
            
            const legend = chartObj.legend;
            const totalPages = legend.pages.length;
            if (totalPages <= 1) return;

            legend.scroll(direction);
            
            setTimeout(() => {
                updateCustomLegendPaginationUI(isFS);
            }, 50);
        }

        function updateCustomLegendPaginationUI(isFS) {
            const chartObj = isFS ? fsChartInstance : highChartInstance;
            const prefix = isFS ? 'fs_' : '';
            const container = document.getElementById(prefix + 'legend_pagination_wrapper');
            const label = document.getElementById(prefix + 'legend_page_info');
            const btnPrev = document.getElementById(prefix + 'btn_legend_prev');
            const btnNext = document.getElementById(prefix + 'btn_legend_next');

            if (!container || !label || !chartObj || !chartObj.legend || !chartObj.legend.pages) {
                if (container) { container.classList.add('hidden'); container.classList.remove('flex'); }
                return;
            }

            const totalPages = chartObj.legend.pages.length;
            if (totalPages <= 1) {
                container.classList.add('hidden');
                container.classList.remove('flex');
                return;
            }

            container.classList.remove('hidden');
            container.classList.add('flex');

            const curPage = chartObj.legend.currentPage || 1;
            label.innerHTML = `${curPage} / ${totalPages}`;

            if (btnPrev) {
                btnPrev.disabled = (curPage === 1);
                btnPrev.style.opacity = (curPage === 1) ? '0.4' : '1';
            }
            if (btnNext) {
                btnNext.disabled = (curPage === totalPages);
                btnNext.style.opacity = (curPage === totalPages) ? '0.4' : '1';
            }
        }

        function openChartFullscreen() {
            const modal = document.getElementById('chart_fullscreen_modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Sync current type
            fsSwitchChart(currentChartType);
        }

        function closeChartFullscreen() {
            const modal = document.getElementById('chart_fullscreen_modal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            if (fsChartInstance) { fsChartInstance.destroy(); fsChartInstance = null; }
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeChartFullscreen();
        });


        // INTERACTIVE RUNDAYAN DETAIL POPUP MODAL
        function showRundayanModal(ancName) {
            const modal = document.getElementById('rundayanDetailModal');
            const card  = document.getElementById('rundayanDetailModalCard');
            document.getElementById('modal_rundayan_title').innerText = `Rundayan: ${ancName}`;
            
            // Collect all ancestors if comma-separated
            const ancList = ancName.split(',').map(s => s.trim()).filter(Boolean);
            let combinedNominators = [];
            let combinedCandidates = [];

            ancList.forEach(anc => {
                const detail = rundayanDetailMap[anc];
                if (detail) {
                    if (detail.nominators) combinedNominators.push(...detail.nominators);
                    if (detail.candidates) combinedCandidates.push(...detail.candidates);
                }
            });

            // Group candidates by Role (Ketua, Bendahara, Sekretaris)
            let ketuaList = [], bendaharaList = [], sekretarisList = [];
            combinedCandidates.forEach(candStr => {
                if (candStr.includes('(Ketua)')) {
                    ketuaList.push(candStr.replace(/\s*\(Ketua\)/i, ''));
                } else if (candStr.includes('(Bendahara)')) {
                    bendaharaList.push(candStr.replace(/\s*\(Bendahara\)/i, ''));
                } else if (candStr.includes('(Sekretaris)')) {
                    sekretarisList.push(candStr.replace(/\s*\(Sekretaris\)/i, ''));
                } else {
                    ketuaList.push(candStr);
                }
            });

            ketuaList = [...new Set(ketuaList)];
            bendaharaList = [...new Set(bendaharaList)];
            sekretarisList = [...new Set(sekretarisList)];

            let htmlCandidates = '';
            if (ketuaList.length > 0) {
                htmlCandidates += `<div style="margin-bottom: 6px;"><strong style="color: #f59e0b;">Ketua:</strong><br><span style="color: #e2e8f0;">${ketuaList.join(', ')}</span></div>`;
            }
            if (bendaharaList.length > 0) {
                htmlCandidates += `<div style="margin-bottom: 6px;"><strong style="color: #10b981;">Bendahara:</strong><br><span style="color: #e2e8f0;">${bendaharaList.join(', ')}</span></div>`;
            }
            if (sekretarisList.length > 0) {
                htmlCandidates += `<div><strong style="color: #38bdf8;">Sekretaris:</strong><br><span style="color: #e2e8f0;">${sekretarisList.join(', ')}</span></div>`;
            }
            if (!htmlCandidates) htmlCandidates = '-';

            if (totalVotes > 0 || combinedCandidates.length > 0) {
                document.getElementById('modal_rundayan_votes').innerText = `${totalVotes} Suara`;
                document.getElementById('modal_rundayan_nominators').innerText = combinedNominators.join(', ') || '-';
                document.getElementById('modal_rundayan_candidates').innerHTML = htmlCandidates;
            } else {
                document.getElementById('modal_rundayan_votes').innerText = `0 Suara`;
                document.getElementById('modal_rundayan_nominators').innerText = `-`;
                document.getElementById('modal_rundayan_candidates').innerHTML = `-`;
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeRundayanModal() {
            const modal = document.getElementById('rundayanDetailModal');
            const card  = document.getElementById('rundayanDetailModalCard');
            if (!modal || !card) return;
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 200);
        }

        // GENERAL TEXT DETAIL POPUP MODAL
        function showTextModal(title, textContent) {
            const modal = document.getElementById('generalTextModal');
            const card  = document.getElementById('generalTextModalCard');
            document.getElementById('modal_text_title').innerText = title;
            document.getElementById('modal_text_content').innerText = textContent;

            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeTextModal() {
            const modal = document.getElementById('generalTextModal');
            const card  = document.getElementById('generalTextModalCard');
            if (!modal || !card) return;
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 200);
        }

        // Auto Complete Suggestions
        const namesData = <?= json_encode($all_names ?? []) ?>;

        function setupSuggestions(inputId, boxId, dataArray) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            if (!input || !box) return;

            input.addEventListener('input', () => {
                const val = input.value.trim().toLowerCase();
                box.innerHTML = '';
                if (!val) {
                    box.classList.add('hidden');
                    return;
                }

                const matched = dataArray.filter(name => name.toLowerCase().includes(val)).slice(0, 5);
                if (matched.length === 0) {
                    box.classList.add('hidden');
                    return;
                }

                matched.forEach(name => {
                    const initial = name.charAt(0).toUpperCase();
                    const itemHtml = `
                        <div onclick="selectSuggestion('${inputId}', '${boxId}', \`${name.replace(/'/g, "\\'")}\`)" class="px-4 py-2.5 hover:bg-[#2c3f3a] cursor-pointer flex items-center gap-3 transition-colors text-left border-b border-[#4D6B67]/10">
                            <div class="w-6 h-6 rounded-full bg-brand-medium/20 text-brand-medium flex items-center justify-center font-bold text-xs shrink-0">
                                ${initial}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-white truncate">${name}</p>
                            </div>
                        </div>
                    `;
                    box.innerHTML += itemHtml;
                });
                box.classList.remove('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !box.contains(e.target)) {
                    box.classList.add('hidden');
                }
            });
        }

        function selectSuggestion(inputId, boxId, value) {
            const input = document.getElementById(inputId);
            input.value = value;
            document.getElementById(boxId).classList.add('hidden');
            input.dispatchEvent(new Event('input'));
        }

        document.addEventListener('DOMContentLoaded', () => {
            render3DPieChart(chartDataIndividu, 'Perolehan Suara Kandidat Individu');

            setupSuggestions('input_search_main', 'search_main_suggestions_box', namesData);
            setupSuggestions('input_search_individu', 'search_individu_suggestions_box', namesData);
            setupSuggestions('input_search_rundayan', 'search_rundayan_suggestions_box', namesData);
            setupSuggestions('input_search_bagan', 'search_bagan_suggestions_box', namesData);

            // Live filters
            const mainInput = document.getElementById('input_search_main');
            if (mainInput) {
                mainInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('#table_main tbody tr');
                    rows.forEach(row => {
                        if (row.cells.length < 2) return;
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }

            const individuInput = document.getElementById('input_search_individu');
            if (individuInput) {
                individuInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('#table_individu tbody tr');
                    rows.forEach(row => {
                        if (row.cells.length < 2) return;
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }

            const rundayanInput = document.getElementById('input_search_rundayan');
            if (rundayanInput) {
                rundayanInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('#table_rundayan tbody tr');
                    rows.forEach(row => {
                        if (row.cells.length < 2) return;
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }

            const baganInput = document.getElementById('input_search_bagan');
            if (baganInput) {
                baganInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const cards = document.querySelectorAll('.bagan-ancestor-card');
                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }
        });
    </script>
    <!-- FULLSCREEN CHART MODAL (Placed at root level so header won't overlap) -->
    <div id="chart_fullscreen_modal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center bg-black/90 backdrop-blur-md">
        <div class="relative w-full h-full flex flex-col bg-[#0e1f1d]">
            <!-- Fullscreen Header Responsive -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-teal-700/40 shrink-0 bg-[#122422] gap-2.5 sm:gap-4">
                <div class="flex items-center justify-between w-full sm:w-auto">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <i class="bi bi-pie-chart-fill text-amber-400 text-base sm:text-lg shrink-0"></i>
                        <div class="min-w-0">
                            <h3 class="font-display font-bold text-white text-sm sm:text-lg truncate">Chart 3D Pie Suara</h3>
                            <p class="text-[10px] sm:text-xs text-white/50 hidden sm:block">Grafik 3D perolehan suara pencalonan ketua yayasan.</p>
                        </div>
                    </div>
                    <!-- Button Tutup Mobile -->
                    <button onclick="closeChartFullscreen()" title="Tutup Fullscreen" class="sm:hidden flex items-center gap-1 px-2.5 py-1.5 bg-red-500/20 hover:bg-red-500/40 border border-red-500/30 rounded-lg text-red-300 text-xs font-semibold transition-all shrink-0">
                        <i class="bi bi-fullscreen-exit text-xs"></i>
                        <span>Tutup</span>
                    </button>
                </div>
                
                <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
                    <!-- Switcher inside fullscreen -->
                    <div class="flex bg-black/40 p-1 rounded-xl border border-white/10 text-xs flex-1 sm:flex-none justify-center">
                        <button id="fs_btn_individu" onclick="fsSwitchChart('individu')" class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow text-center">Individu</button>
                        <button id="fs_btn_rundayan" onclick="fsSwitchChart('rundayan')" class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all text-center">Rundayan</button>
                    </div>
                    <!-- Button Tutup Desktop -->
                    <button onclick="closeChartFullscreen()" title="Tutup Fullscreen" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-red-500/20 hover:bg-red-500/40 border border-red-500/30 rounded-xl text-red-300 text-xs font-semibold transition-all shrink-0">
                        <i class="bi bi-fullscreen-exit text-sm"></i>
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
            <!-- Fullscreen Chart Container -->
            <div class="flex-1 flex flex-col items-center justify-center p-2 sm:p-4 min-h-0 relative">
                <div id="container_chart_3d_fs" class="w-full h-full" style="touch-action: manipulation;"></div>
                
                <!-- FULLSCREEN CUSTOM LEGEND PAGINATION -->
                <div id="fs_legend_pagination_wrapper" class="hidden items-center justify-between w-full max-w-xs mt-2 px-3 py-1.5 bg-[#122422] border border-teal-700/40 rounded-xl text-xs shrink-0 gap-2 shadow-lg">
                    <button id="fs_btn_legend_prev" onclick="scrollLegendPage(true, -1)" class="flex items-center gap-1 px-3 py-1.5 bg-emerald-600/30 hover:bg-emerald-600/60 active:scale-95 text-emerald-300 border border-emerald-500/30 rounded-lg font-bold transition-all disabled:pointer-events-none">
                        <i class="bi bi-chevron-left text-xs"></i>
                        <span>Prev</span>
                    </button>
                    <div class="flex items-center gap-1 text-xs font-bold text-white/90">
                        <span>Hal</span>
                        <span id="fs_legend_page_info">1 / 1</span>
                    </div>
                    <button id="fs_btn_legend_next" onclick="scrollLegendPage(true, 1)" class="flex items-center gap-1 px-3 py-1.5 bg-emerald-600/30 hover:bg-emerald-600/60 active:scale-95 text-emerald-300 border border-emerald-500/30 rounded-lg font-bold transition-all disabled:pointer-events-none">
                        <span>Next</span>
                        <i class="bi bi-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
