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
            <div class="flex items-center gap-3">
                <div class="w-3 h-0.5 <?= $line_color ?>"></div>
                <div class="bg-gradient-to-r <?= $card_bg ?> border rounded-2xl px-5 py-3 flex items-center justify-between gap-6 transition-all duration-300">
                    <div>
                        <?php 
                        $role_raw = trim((isset($cand['roles_text']) && $cand['roles_text'] !== '-') ? $cand['roles_text'] : ($cand['description'] ?: ''));
                        $is_bendahara  = preg_match('/bendahara/i', $role_raw);
                        $is_sekretaris = preg_match('/sekretaris/i', $role_raw);
                        if ($is_bendahara)   { $role_lbl = 'Kandidat Bendahara'; }
                        elseif ($is_sekretaris)  { $role_lbl = 'Kandidat Sekretaris'; }
                        else                     { $role_lbl = 'Kandidat Ketua'; }
                        ?>
                        <span class="text-[10px] uppercase font-bold <?= $text_color ?> tracking-wider block mb-0.5"><?= htmlspecialchars($role_lbl) ?></span>
                        <strong class="text-white text-base font-semibold"><?= htmlspecialchars($cand['candidate_name']) ?></strong>
                    </div>
                </div>
            </div>
            
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

if (!function_exists('render_cards_pagination')) {
    function render_cards_pagination($total_rows, $limit, $current_page, $param_name, $is_all = false) {
        $total_pages = ceil($total_rows / max($limit, 1));

        $get = $_GET;
        unset($get[$param_name]);
        $query_base = http_build_query($get);
        $url_prefix = current_url() . ($query_base ? '?' . $query_base . '&' : '?') . $param_name . '=';

        // Build "Lihat Semua" / "Per Halaman" URL
        $get_toggle = $_GET;
        if ($is_all) {
            unset($get_toggle[$param_name]);
            $qt = http_build_query($get_toggle);
            $toggle_url   = current_url() . ($qt ? '?' . $qt : '');
            $toggle_label = '<i class="bi bi-list-ul"></i> Per Halaman';
            $toggle_class = 'bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
        } else {
            $get_toggle[$param_name] = 'all';
            $toggle_url   = current_url() . '?' . http_build_query($get_toggle);
            $toggle_label = '<i class="bi bi-eye-fill"></i> Lihat Semua';
            $toggle_class = 'bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
        }
        $toggle_btn = '<a href="' . $toggle_url . '" class="px-3 py-2 rounded-xl text-xs font-semibold transition-all ' . $toggle_class . '">' . $toggle_label . '</a>';

        if ($is_all || $total_pages <= 1) {
            return '<div class="flex items-center justify-center gap-2 mt-6">' . $toggle_btn . '</div>';
        }

        $html = '<div class="flex items-center justify-center gap-2 mt-6">';
        $html .= $toggle_btn;

        if ($current_page > 1) {
            $html .= '<a href="' . $url_prefix . ($current_page - 1) . '" class="px-3.5 py-2 rounded-xl bg-[#172e2b] hover:bg-emerald-600/40 text-white text-xs font-semibold border border-emerald-500/30 transition-all"><i class="bi bi-chevron-left"></i></a>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i === $current_page) {
                $html .= '<span class="px-3.5 py-2 rounded-xl bg-emerald-500 text-teal-950 text-xs font-extrabold border border-emerald-400 shadow-md">' . $i . '</span>';
            } else {
                $html .= '<a href="' . $url_prefix . $i . '" class="px-3.5 py-2 rounded-xl bg-[#172e2b] hover:bg-emerald-600/40 text-white text-xs font-semibold border border-emerald-500/30 transition-all">' . $i . '</a>';
            }
        }

        if ($current_page < $total_pages) {
            $html .= '<a href="' . $url_prefix . ($current_page + 1) . '" class="px-3.5 py-2 rounded-xl bg-[#172e2b] hover:bg-emerald-600/40 text-white text-xs font-semibold border border-emerald-500/30 transition-all"><i class="bi bi-chevron-right"></i></a>';
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
    <title><?= htmlspecialchars($page_title) ?></title>
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
                            950: '#0F1A18',
                            900: '#172724',
                            800: '#203430',
                            700: '#2D4742',
                            600: '#3D5E58',
                        },
                        brand: {
                            dark: '#374D49',
                            medium: '#4D6B67',
                            light: '#E3E3E3',
                            gold: '#D4B571'
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
        ::-webkit-scrollbar-track { background: #0F1A18; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.25); }

        .highcharts-credits { display: none !important; }
        .highcharts-background { fill: transparent !important; }
        .highcharts-legend-navigation { display: none !important; }

        /* Hover Rundayan Tooltip Styling */
        .rundayan-hover {
            position: relative;
            cursor: pointer;
            text-decoration: underline;
            text-decoration-style: dotted;
            text-underline-offset: 3px;
        }
    </style>
</head>
<body class="bg-[#0b1413] text-white font-body min-h-screen pb-16">

    <!-- Top Navigation Header Dewan Pembina -->
    <header class="bg-gradient-to-r from-teal-900 via-[#1e3431] to-teal-900 border-b border-teal-700/30 sticky top-0 z-40 shadow-xl backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="<?= base_url('assets/favicon.jpeg') ?>" alt="Logo" class="w-10 h-10 rounded-full border-2 border-emerald-400/50 shadow-md">
                <div>
                    <h1 class="font-display font-bold text-base sm:text-lg text-white leading-tight">Yayasan H.M Samhudi</h1>
                    <p class="text-xs text-emerald-400 font-medium flex items-center gap-1">
                        <i class="bi bi-shield-check text-xs"></i> Portal Laporan Dewan Pembina
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40">
                    <i class="bi bi-star-fill text-[10px]"></i> Dewan Pembina
                </span>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-12">
        <?php
        // Determine focus mode (view all for cards or tables)
        $show_only = null;
        if ($page_card_individu === 'all' || $page_tbl_individu === 'all') $show_only = 'individu';
        elseif ($page_card_rundayan === 'all' || $page_tbl_rundayan === 'all') $show_only = 'rundayan';

        // Build back URL
        $get_back_rek = $_GET;
        unset($get_back_rek['page_card_individu'], $get_back_rek['page_card_rundayan'], $get_back_rek['page_tbl_individu'], $get_back_rek['page_tbl_rundayan']);
        $back_url_rek = current_url() . ($get_back_rek ? '?' . http_build_query($get_back_rek) : '');
        ?>

        <?php if ($show_only): ?>
        <!-- Focus Banner: Lihat Semua mode -->
        <div class="flex items-center justify-between gap-4 bg-[#172e2b] border border-emerald-500/30 rounded-2xl px-5 py-3">
            <span class="text-sm font-semibold text-white/80">
                <i class="bi bi-eye-fill text-emerald-300 mr-1.5"></i>
                Menampilkan semua data <strong class="text-emerald-300"><?= $show_only === 'individu' ? 'Individu' : 'Rundayan' ?></strong>
            </span>
            <a href="<?= $back_url_rek ?>" class="flex items-center gap-1.5 px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all">
                <i class="bi bi-arrow-left"></i> Kembali ke Tampilan Normal
            </a>
        </div>
        <?php endif; ?>

        <?php if (!$show_only): ?>
        <!-- SECTION 1: CHART 3D PIE (REKAPITULASI DUKUNGAN PALING ATAS) -->
        <div class="bg-gradient-to-b from-[#162724] to-[#101c1a] border border-teal-700/40 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-teal-700/30 pb-4">
                <div>
                    <h3 class="font-display font-bold text-xl text-white flex items-center gap-2">
                        <i class="bi bi-pie-chart-fill text-amber-400"></i> Visualisasi Chart 3D Pie Perolehan Suara
                    </h3>
                    <p class="text-xs text-white/60 mt-0.5">Persentase &amp; jumlah suara dukungan calon ketua yayasan dalam grafik 3D interaktif.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Chart Type Switcher Buttons -->
                    <div class="flex bg-black/40 p-1 rounded-xl border border-white/10 text-xs">
                        <button id="btn_chart_individu" onclick="switchChart('individu')" class="px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow">
                            Kategori Individu
                        </button>
                        <button id="btn_chart_rundayan" onclick="switchChart('rundayan')" class="px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all">
                            Kategori Rundayan
                        </button>
                    </div>
                    <!-- Fullscreen Button -->
                    <button onclick="openChartFullscreen()" title="Fullscreen" class="flex items-center gap-1.5 px-3 py-1.5 bg-white/10 hover:bg-white/20 border border-white/15 rounded-xl text-white text-xs font-semibold transition-all">
                        <i class="bi bi-fullscreen text-sm"></i>
                        <span class="hidden sm:inline">Fullscreen</span>
                    </button>
                </div>
            </div>

            <!-- 3D Pie Chart Render Box -->
            <div class="relative min-h-[400px] flex flex-col items-center justify-center">
                <div id="container_chart_3d" class="w-full h-[420px]" style="touch-action: manipulation;"></div>
                
                <!-- CUSTOM HTML LEGEND PAGINATION CONTROLS -->
                <div id="legend_pagination_wrapper" class="hidden items-center justify-between w-full max-w-xs mt-2 px-3 py-1.5 bg-[#122422] border border-teal-700/40 rounded-xl text-xs shrink-0 gap-2 shadow-lg">
                    <button id="btn_legend_prev" onclick="scrollLegendPage(false, -1)" class="flex items-center gap-1 px-3 py-1.5 bg-emerald-600/30 hover:bg-emerald-600/60 active:scale-95 text-emerald-300 border border-emerald-500/30 rounded-lg font-bold transition-all disabled:pointer-events-none">
                        <i class="bi bi-chevron-left text-xs"></i>
                        <span>Prev</span>
                    </button>
                    <div class="flex items-center gap-1 text-xs font-bold text-white/90">
                        <span>Hal</span>
                        <span id="legend_page_info">1 / 1</span>
                    </div>
                    <button id="btn_legend_next" onclick="scrollLegendPage(false, 1)" class="flex items-center gap-1 px-3 py-1.5 bg-emerald-600/30 hover:bg-emerald-600/60 active:scale-95 text-emerald-300 border border-emerald-500/30 rounded-lg font-bold transition-all disabled:pointer-events-none">
                        <span>Next</span>
                        <i class="bi bi-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endif; // end !$show_only chart ?>

        <?php if (!$show_only): ?>
        <!-- Hero Card Banner -->
        <div class="relative overflow-hidden bg-gradient-to-r from-[#172e2b] via-[#1c3835] to-[#122422] border border-emerald-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-semibold">
                    <i class="bi bi-bar-chart-line-fill"></i> Hasil Rekapitulasi Real-Time
                </div>
                <h2 class="font-display font-extrabold text-2xl sm:text-4xl text-white">
                    Rekapitulasi Pemilihan Ketua Yayasan
                </h2>
                <p class="text-white/70 text-sm max-w-2xl leading-relaxed">
                    Laporan khusus Dewan Pembina mencakup visualisasi Chart 3D Pie perolehan suara, kartu calon ter-rekap per nama, rincian pengusul individu & rundayan, serta bagan struktur silsilah pencalonan.
                </p>
            </div>
        </div>
        <?php endif; // end !$show_only hero ?>

        <!-- SECTION 2: KARTU CALON REKAPITULASI (INDIVIDU & RUNDAYAN SEPARATE + PAGINATION) -->
        <div class="space-y-10">
            <?php if (!$show_only): ?>
            <div class="border-b border-white/10 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white flex items-center gap-2">
                        <i class="bi bi-grid-fill text-amber-400"></i> Kartu Informasi Rekapitulasi Calon
                    </h2>
                    <p class="text-xs text-white/60">Setiap kartu mewakili 1 nama calon ter-rekap dengan total suara, daftar pengusul/pemilih, rincian rundayan, dan tanggal input.</p>
                </div>
            </div>
            <?php endif; // end !$show_only section header ?>

            <!-- 2.1 KARTU INDIVIDU -->
            <?php if ((!$show_only && $page_tbl_individu !== 'all' && $page_tbl_rundayan !== 'all') || $page_card_individu === 'all'): ?>
            <div class="bg-gradient-to-b from-[#18312e] to-[#112422] border border-amber-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-white/10 pb-4">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-bold text-amber-300 flex items-center gap-2">
                            <i class="bi bi-person-badge-fill"></i> Kartu Calon Kategori Individu
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 bg-amber-500/10 text-amber-300 rounded-full border border-amber-500/30">
                            Total <?= $total_cards_individu ?> Calon
                        </span>
                    </div>

                    <!-- Search Form Individu Cards -->
                    <div class="w-full sm:w-72 relative">
                        <form method="GET" action="<?= current_url() ?>">
                            <?php 
                            foreach ($_GET as $key => $val) {
                                if ($key !== 'search_individu' && $key !== 'page_card_individu') {
                                    echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                                }
                            }
                            ?>
                            <div class="relative flex items-center">
                                <i class="bi bi-search absolute left-3 text-white/40 text-xs"></i>
                                <input type="text" name="search_individu" id="input_search_cards_individu" value="<?= htmlspecialchars($search_individu ?? '') ?>" autocomplete="off" placeholder="Cari calon / pemilih / rundayan..." class="w-full bg-black/40 border border-amber-500/30 rounded-xl py-1.5 pl-9 pr-8 text-xs text-white placeholder-white/40 focus:outline-none focus:border-amber-400 transition-all">
                                <?php if (!empty($search_individu)): ?>
                                    <a href="<?= current_url() ?>" class="absolute right-2.5 text-white/40 hover:text-white text-xs"><i class="bi bi-x-circle-fill"></i></a>
                                <?php endif; ?>
                            </div>
                            <div id="search_cards_individu_box" class="absolute left-0 right-0 top-full mt-1 bg-[#162926] border border-amber-500/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                        </form>
                    </div>
                </div>

                <?php if (empty($individu_cards)): ?>
                    <p class="text-white/40 text-sm italic py-4 text-center">Belum ada kartu calon individu yang sesuai pencarian.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($individu_cards as $c): ?>
                            <div class="card-item-individu bg-gradient-to-br from-[#1d3d39] via-[#16302c] to-[#102320] border border-amber-500/20 hover:border-amber-400/60 rounded-3xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between transition-all duration-300 group hover:-translate-y-1">
                                <div class="space-y-4">
                                    <!-- Top Header Badges & Timestamp -->
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            Kategori Individu
                                        </span>
                                        <?php if (!empty($c['created_at'])): ?>
                                            <span class="text-[11px] text-emerald-400/90 font-medium flex items-center gap-1">
                                                <i class="bi bi-clock-history"></i> <?= date('d M Y H:i', strtotime($c['created_at'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Candidate Name & Roles -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-white/50">Dicalonkan Sebagai</span>
                                            <span class="text-xs font-bold text-amber-300 bg-amber-500/10 px-2.5 py-0.5 rounded-full border border-amber-500/30">
                                                <?= htmlspecialchars($c['roles_text']) ?>
                                            </span>
                                        </div>
                                        <h4 class="text-2xl font-display font-extrabold text-white group-hover:text-amber-300 transition-colors">
                                            <?= htmlspecialchars($c['candidate_name']) ?>
                                        </h4>
                                    </div>

                                    <!-- Nominators & Breakdown Box -->
                                    <div class="bg-black/40 border border-white/5 rounded-2xl p-4 space-y-3 text-xs">
                                        <div>
                                            <span class="text-amber-400 font-bold uppercase tracking-wider block text-[10px] mb-1">Dicalonkan / Pemilih:</span>
                                            <p class="text-white font-semibold leading-relaxed bg-white/5 p-2.5 rounded-xl border border-white/5">
                                                <?= htmlspecialchars($c['nominator_name'] ?: '-') ?>
                                            </p>
                                        </div>
                                        <div class="border-t border-white/10 pt-2">
                                            <span class="text-emerald-400 font-bold uppercase tracking-wider block text-[10px] mb-1">Rincian Per-Rundayan:</span>
                                            <p class="text-emerald-300 font-medium leading-relaxed">
                                                <?= htmlspecialchars($c['breakdown_text'] ?: '-') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Vote Count Badge -->
                                <div class="mt-5 border-t border-white/10 pt-3 flex items-center justify-between">
                                    <span class="text-xs text-white/60 font-medium">Total Dukungan Suara</span>
                                    <span class="px-4 py-1.5 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-500/40 font-extrabold text-base shadow">
                                        <?= $c['votes_count'] ?> Suara
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination Cards Individu -->
                    <div class="pt-2">
                        <?= render_cards_pagination($total_cards_individu, $limit_cards_individu, $page_card_individu, 'page_card_individu', $page_card_individu === 'all') ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; // end individu cards ?>

            <!-- 2.2 KARTU RUNDAYAN -->
            <?php if ((!$show_only && $page_tbl_individu !== 'all' && $page_tbl_rundayan !== 'all') || $page_card_rundayan === 'all'): ?>
            <div class="bg-gradient-to-b from-[#112d30] to-[#0c1f21] border border-cyan-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-white/10 pb-4">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-bold text-cyan-300 flex items-center gap-2">
                            <i class="bi bi-people-fill"></i> Kartu Calon Kategori Rundayan
                        </h3>
                        <span class="text-xs font-semibold px-3 py-1 bg-cyan-500/10 text-cyan-300 rounded-full border border-cyan-500/30">
                            Total <?= $total_cards_rundayan ?> Calon Paket
                        </span>
                    </div>

                    <!-- Search Form Rundayan Cards -->
                    <div class="w-full sm:w-72 relative">
                        <form method="GET" action="<?= current_url() ?>">
                            <?php 
                            foreach ($_GET as $key => $val) {
                                if ($key !== 'search_rundayan' && $key !== 'page_card_rundayan') {
                                    echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                                }
                            }
                            ?>
                            <div class="relative flex items-center">
                                <i class="bi bi-search absolute left-3 text-white/40 text-xs"></i>
                                <input type="text" name="search_rundayan" id="input_search_cards_rundayan" value="<?= htmlspecialchars($search_rundayan ?? '') ?>" autocomplete="off" placeholder="Cari paket / pemilih / rundayan..." class="w-full bg-black/40 border border-cyan-500/30 rounded-xl py-1.5 pl-9 pr-8 text-xs text-white placeholder-white/40 focus:outline-none focus:border-cyan-400 transition-all">
                                <?php if (!empty($search_rundayan)): ?>
                                    <a href="<?= current_url() ?>" class="absolute right-2.5 text-white/40 hover:text-white text-xs"><i class="bi bi-x-circle-fill"></i></a>
                                <?php endif; ?>
                            </div>
                            <div id="search_cards_rundayan_box" class="absolute left-0 right-0 top-full mt-1 bg-[#10292c] border border-cyan-500/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                        </form>
                    </div>
                </div>

                <?php if (empty($rundayan_cards)): ?>
                    <p class="text-white/40 text-sm italic py-4 text-center">Belum ada kartu calon rundayan yang sesuai pencarian.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($rundayan_cards as $c): ?>
                            <div class="card-item-rundayan bg-gradient-to-br from-[#14373b] via-[#0e292d] to-[#0a1e20] border border-cyan-500/20 hover:border-cyan-400/60 rounded-3xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between transition-all duration-300 group hover:-translate-y-1">
                                <div class="space-y-4">
                                    <!-- Top Header Badges & Timestamp -->
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                            Kategori Rundayan
                                        </span>
                                        <?php if (!empty($c['created_at'])): ?>
                                            <span class="text-[11px] text-emerald-400/90 font-medium flex items-center gap-1">
                                                <i class="bi bi-clock-history"></i> <?= date('d M Y H:i', strtotime($c['created_at'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Candidate Name & Roles -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-white/50">Dicalonkan Sebagai</span>
                                            <span class="text-xs font-bold text-cyan-300 bg-cyan-500/10 px-2.5 py-0.5 rounded-full border border-cyan-500/30">
                                                <?= htmlspecialchars($c['roles_text']) ?>
                                            </span>
                                        </div>
                                        <h4 class="text-2xl font-display font-extrabold text-white group-hover:text-cyan-300 transition-colors">
                                            <?= htmlspecialchars($c['candidate_name']) ?>
                                        </h4>
                                    </div>

                                    <!-- Nominators & Breakdown Box -->
                                    <div class="bg-black/40 border border-white/5 rounded-2xl p-4 space-y-3 text-xs">
                                        <div>
                                            <span class="text-cyan-400 font-bold uppercase tracking-wider block text-[10px] mb-1">Dicalonkan / Pemilih:</span>
                                            <p class="text-white font-semibold leading-relaxed bg-white/5 p-2.5 rounded-xl border border-white/5">
                                                <?= htmlspecialchars($c['nominator_name'] ?: '-') ?>
                                            </p>
                                        </div>
                                        <div class="border-t border-white/10 pt-2">
                                            <span class="text-emerald-400 font-bold uppercase tracking-wider block text-[10px] mb-1">Rincian Per-Rundayan:</span>
                                            <p class="text-emerald-300 font-medium leading-relaxed">
                                                <?= htmlspecialchars($c['breakdown_text'] ?: '-') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Vote Count Badge -->
                                <div class="mt-5 border-t border-white/10 pt-3 flex items-center justify-between">
                                    <span class="text-xs text-white/60 font-medium">Total Dukungan Suara</span>
                                    <span class="px-4 py-1.5 rounded-xl bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 font-extrabold text-base shadow">
                                        <?= $c['votes_count'] ?> Suara
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination Cards Rundayan -->
                    <div class="pt-2">
                        <?= render_cards_pagination($total_cards_rundayan, $limit_cards_rundayan, $page_card_rundayan, 'page_card_rundayan', $page_card_rundayan === 'all') ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; // end rundayan cards ?>
        </div>

        <!-- SECTION 3: TABEL REKAPITULASI INDIVIDU & RUNDAYAN -->
        <?php
        // Table focus mode (uses global $show_only)
        $tbl_show_only = $show_only;
        ?>
        <?php if (!$show_only || $page_tbl_individu === 'all' || $page_tbl_rundayan === 'all'): ?>
        <div class="space-y-8">
            <?php if (!$show_only): ?>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Daftar Tabel Rekapitulasi</h2>
                    <p class="text-xs text-white/60">Arahkan kursor (hover) ke nama <span class="text-emerald-400 font-bold">Rundayan</span> untuk melihat nama-nama pengusul &amp; calonnya.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- 1. TABEL REKAP INDIVIDU -->
            <?php if (!$tbl_show_only || $tbl_show_only === 'individu'): ?>
            <div class="bg-gradient-to-b from-[#1b3638] to-[#122829] border border-amber-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="text-lg font-bold text-amber-300 flex items-center gap-2">
                        <i class="bi bi-person-badge-fill"></i> Tabel Pencalonan Individu (Approved)
                    </h3>
                    <!-- Search Input Tabel Individu -->
                    <div class="w-full sm:w-72 relative">
                        <div class="relative flex items-center">
                            <i class="bi bi-search absolute left-3 text-white/40 text-xs"></i>
                            <input type="text" id="search_table_individu_input" placeholder="Cari di tabel individu..." class="w-full bg-black/40 border border-amber-500/30 rounded-xl py-1.5 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-amber-400 transition-all">
                        </div>
                    </div>
                </div>

                <?php if (empty($individu_tbl_paginated) && $total_tbl_individu === 0): ?>
                    <p class="text-white/40 text-sm italic py-4">Belum ada data pencalonan individu yang disetujui.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table id="table_rekap_individu" class="w-full text-left border-collapse" style="min-width: 800px;">
                            <thead>
                                <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-wider">
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">No</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Nama Calon</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Sebagai Calon</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Pencalon / Nominator</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Rundayan</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap text-amber-300">Total Dukungan</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap text-emerald-400">Rincian Pemilih</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                <?php foreach ($individu_tbl_paginated as $index => $c): ?>
                                    <tr class="table-row-item hover:bg-white/5 transition-colors">
                                        <td class="py-3.5 pr-6 text-white/50 whitespace-nowrap">#<?= ($page_tbl_individu === 'all' ? $index + 1 : (($page_tbl_individu - 1) * $limit_tbl_individu) + $index + 1) ?></td>
                                        <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap"><?= htmlspecialchars($c['candidate_name']) ?></td>
                                        <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                             <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-300 border border-amber-500/30">
                                                 <?= htmlspecialchars($c['roles_text']) ?>
                                             </span>
                                        </td>
                                        <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['nominator_name']) ?></td>
                                        <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap">
                                            <span class="rundayan-hover text-emerald-300 font-semibold" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($c['ancestor_name'])) ?>')" onmouseleave="hideRundayanHover()">
                                                <?= htmlspecialchars($c['ancestor_name']) ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 pr-6 text-amber-300 font-bold whitespace-nowrap"><?= $c['votes_count'] ?> suara</td>
                                        <td class="py-3.5 pr-6 text-emerald-400 font-semibold whitespace-nowrap"><?= $c['breakdown_text'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Footer Tabel Individu -->
                    <div class="mt-4 flex flex-col items-center justify-between gap-3 border-t border-white/5 pt-4 sm:flex-row">
                        <span class="text-xs text-white/55">
                            Menampilkan <?= count($individu_tbl_paginated) ?> dari <?= $total_tbl_individu ?> data individu
                        </span>
                        <?= render_cards_pagination($total_tbl_individu, $limit_tbl_individu, $page_tbl_individu, 'page_tbl_individu', $page_tbl_individu === 'all') ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; // end tbl individu ?>

            <!-- 2. TABEL REKAP RUNDAYAN -->
            <?php if (!$tbl_show_only || $tbl_show_only === 'rundayan'): ?>
            <div class="bg-gradient-to-b from-[#112d30] to-[#0c1f21] border border-cyan-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="text-lg font-bold text-cyan-300 flex items-center gap-2">
                        <i class="bi bi-people-fill"></i> Tabel Pencalonan Rundayan (Approved)
                    </h3>
                    <!-- Search Input Tabel Rundayan -->
                    <div class="w-full sm:w-72 relative">
                        <div class="relative flex items-center">
                            <i class="bi bi-search absolute left-3 text-white/40 text-xs"></i>
                            <input type="text" id="search_table_rundayan_input" placeholder="Cari di tabel rundayan..." class="w-full bg-black/40 border border-cyan-500/30 rounded-xl py-1.5 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-cyan-400 transition-all">
                        </div>
                    </div>
                </div>

                <?php if (empty($rundayan_tbl_paginated) && $total_tbl_rundayan === 0): ?>
                    <p class="text-white/40 text-sm italic py-4">Belum ada data pencalonan rundayan yang disetujui.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table id="table_rekap_rundayan" class="w-full text-left border-collapse" style="min-width: 800px;">
                            <thead>
                                <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-wider">
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">No</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Nama Calon</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Sebagai Calon</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Pencalon / Nominator</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap">Rundayan</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap text-cyan-300">Total Dukungan</th>
                                    <th class="pb-3 pr-6 font-bold whitespace-nowrap text-emerald-400">Rincian Pemilih</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                <?php foreach ($rundayan_tbl_paginated as $index => $c): ?>
                                    <tr class="table-row-item hover:bg-white/5 transition-colors">
                                        <td class="py-3.5 pr-6 text-white/50 whitespace-nowrap">#<?= ($page_tbl_rundayan === 'all' ? $index + 1 : (($page_tbl_rundayan - 1) * $limit_tbl_rundayan) + $index + 1) ?></td>
                                        <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap"><?= htmlspecialchars($c['candidate_name']) ?></td>
                                        <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                             <span class="px-2.5 py-1 rounded-full bg-cyan-500/10 text-cyan-300 border border-cyan-500/30">
                                                 <?= htmlspecialchars($c['roles_text']) ?>
                                             </span>
                                        </td>
                                        <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['nominator_name']) ?></td>
                                        <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap">
                                            <span class="rundayan-hover text-emerald-300 font-semibold" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($c['ancestor_name'])) ?>')" onmouseleave="hideRundayanHover()">
                                                <?= htmlspecialchars($c['ancestor_name']) ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 pr-6 text-cyan-300 font-bold whitespace-nowrap"><?= $c['votes_count'] ?> suara</td>
                                        <td class="py-3.5 pr-6 text-emerald-400 font-semibold whitespace-nowrap"><?= $c['breakdown_text'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Footer Tabel Rundayan -->
                    <div class="mt-4 flex flex-col items-center justify-between gap-3 border-t border-white/5 pt-4 sm:flex-row">
                        <span class="text-xs text-white/55">
                            Menampilkan <?= count($rundayan_tbl_paginated) ?> dari <?= $total_tbl_rundayan ?> data rundayan
                        </span>
                        <?= render_cards_pagination($total_tbl_rundayan, $limit_tbl_rundayan, $page_tbl_rundayan, 'page_tbl_rundayan', $page_tbl_rundayan === 'all') ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; // end tbl rundayan ?>
        </div>
        <?php endif; // end !$show_only (section 3 tables) ?>

        <?php if (!$show_only): ?>
        <!-- SECTION 4: BAGAN SILSILAH PENCALONAN -->
        <div class="space-y-6 pt-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div>
                        <h2 class="font-display font-extrabold text-2xl text-white">Bagan Silsilah Hubungan Pencalonan</h2>
                        <p class="text-xs text-white/60">Struktur pohon hubungan pencalonan berdasarkan garis rundayan masing-masing.</p>
                    </div>
                    <!-- Toggle Switcher Individu / Rundayan -->
                    <div class="flex bg-black/40 p-1 rounded-xl border border-white/10 text-xs shrink-0 self-start sm:self-auto">
                        <button id="btn_bagan_type_individu" onclick="switchBaganType('individu')" class="px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow">
                            Individu
                        </button>
                        <button id="btn_bagan_type_rundayan" onclick="switchBaganType('rundayan')" class="px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all">
                            Rundayan
                        </button>
                    </div>
                </div>

                <!-- Search Form Bagan Silsilah -->
                <div class="w-full sm:w-72 relative">
                    <form method="GET" action="<?= current_url() ?>">
                        <?php 
                        foreach ($_GET as $key => $val) {
                            if ($key !== 'search_bagan') {
                                echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                            }
                        }
                        ?>
                        <div class="relative flex items-center">
                            <i class="bi bi-search absolute left-3 text-white/40 text-xs"></i>
                            <input type="text" name="search_bagan" id="input_search_bagan" value="<?= htmlspecialchars($search_bagan ?? '') ?>" autocomplete="off" placeholder="Cari di bagan silsilah..." class="w-full bg-black/40 border border-emerald-500/30 rounded-xl py-1.5 pl-9 pr-8 text-xs text-white placeholder-white/40 focus:outline-none focus:border-emerald-400 transition-all">
                            <?php if (!empty($search_bagan)): ?>
                                <a href="<?= current_url() ?>" class="absolute right-2.5 text-white/40 hover:text-white text-xs"><i class="bi bi-x-circle-fill"></i></a>
                            <?php endif; ?>
                        </div>
                        <div id="search_bagan_box" class="absolute left-0 right-0 top-full mt-1 bg-[#162926] border border-emerald-500/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
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
                <div id="container_bagan_individu" class="space-y-6">
                    <?php if (empty($grouped_bagan_individu)): ?>
                        <div class="bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-teal-700/30 rounded-3xl p-6 text-center text-white/40 text-sm italic">
                            Belum ada bagan pencalonan kategori Individu.
                        </div>
                    <?php else: ?>
                        <?php foreach ($grouped_bagan_individu as $ancestor => $cand_list): ?>
                            <div class="bagan-ancestor-card bg-gradient-to-b from-[#172b28] to-[#0f1d1b] border border-teal-700/30 rounded-3xl p-6 sm:p-8 shadow-2xl">
                                <h3 class="text-xl font-bold text-emerald-300 border-b border-teal-700/30 pb-3 mb-6 flex items-center gap-2">
                                    <i class="bi bi-diagram-3-fill"></i> Rundayan: 
                                    <span class="rundayan-hover text-white underline decoration-emerald-500/50" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($ancestor)) ?>')" onmouseleave="hideRundayanHover()">
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
                <div id="container_bagan_rundayan" class="space-y-6 hidden">
                    <?php if (empty($grouped_bagan_rundayan)): ?>
                        <div class="bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-teal-700/30 rounded-3xl p-6 text-center text-white/40 text-sm italic">
                            Belum ada bagan pencalonan kategori Rundayan.
                        </div>
                    <?php else: ?>
                        <?php foreach ($grouped_bagan_rundayan as $ancestor => $cand_list): ?>
                            <div class="bagan-ancestor-card bg-gradient-to-b from-[#172b28] to-[#0f1d1b] border border-teal-700/30 rounded-3xl p-6 sm:p-8 shadow-2xl">
                                <h3 class="text-xl font-bold text-cyan-300 border-b border-teal-700/30 pb-3 mb-6 flex items-center gap-2">
                                    <i class="bi bi-diagram-3-fill"></i> Rundayan: 
                                    <span class="rundayan-hover text-white underline decoration-cyan-500/50" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($ancestor)) ?>')" onmouseleave="hideRundayanHover()">
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

    </main>

    <!-- FLOATING HOVER TOOLTIP CARD FOR RUNDAYAN -->
    <div id="rundayanHoverTooltip" class="fixed z-50 hidden bg-[#142623] border border-emerald-500/40 rounded-2xl p-5 shadow-2xl max-w-sm w-80 text-left backdrop-blur-md pointer-events-none transition-all duration-200 opacity-0 transform scale-95">
        <div class="flex items-center justify-between border-b border-emerald-500/20 pb-2 mb-3">
            <h4 class="font-display font-bold text-sm text-emerald-300 flex items-center gap-1.5" id="hover_rundayan_title">
                <i class="bi bi-people-fill"></i> Detail Rundayan
            </h4>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30" id="hover_rundayan_votes">
                0 Suara
            </span>
        </div>
        
        <div class="space-y-3 text-xs">
            <div>
                <span class="text-white/40 uppercase tracking-wider font-bold block mb-1">Pengusul / Nominator:</span>
                <p class="text-white font-medium bg-black/30 rounded-xl p-2.5 leading-relaxed border border-white/5" id="hover_rundayan_nominators">-</p>
            </div>
            <div>
                <span class="text-white/40 uppercase tracking-wider font-bold block mb-1">Calon yang Diusulkan:</span>
                <p class="text-amber-300 font-medium bg-black/30 rounded-xl p-2.5 leading-relaxed border border-white/5" id="hover_rundayan_candidates">-</p>
            </div>
        </div>
    </div>

    <!-- HIGHCHARTS, SEARCH AUTOCOMPLETE & LIVE FILTER SCRIPT -->
    <script>
        const chartDataIndividu = <?= json_encode($chart_data_individu ?? []) ?>;
        const chartDataRundayan = <?= json_encode($chart_data_rundayan ?? []) ?>;
        const rundayanDetailMap = <?= json_encode($rundayan_detail_map ?? []) ?>;
        const allNamesList = <?= json_encode($all_names ?? []) ?>;

        let highChartInstance = null;

        function getDistinctColorsForData(dataSeries) {
            const presetPalette = [
                '#F59E0B', // Bright Amber
                '#06B6D4', // Vibrant Cyan
                '#EF4444', // Vivid Red
                '#10B981', // Emerald Green
                '#8B5CF6', // Electric Purple
                '#EC4899', // Hot Pink
                '#3B82F6', // Royal Blue
                '#84CC16', // Lime Green
                '#F97316', // Neon Orange
                '#14B8A6', // Deep Teal
                '#A855F7', // Vivid Violet
                '#EAB308', // Gold Yellow
                '#6366F1', // Indigo
                '#0284C7', // Sky Blue
                '#D97706', // Deep Amber
                '#059669', // Mint
                '#C026D3', // Magenta
                '#DC2626', // Crimson
                '#2563EB', // Cobalt Blue
                '#4D7C0F', // Olive Green
                '#B45309', // Rust Orange
                '#4F46E5', // Deep Indigo
                '#0891B2', // Ocean Blue
                '#DB2777'  // Rose Pink
            ];

            const count = dataSeries ? dataSeries.length : 0;
            if (count <= presetPalette.length) {
                return presetPalette.slice(0, Math.max(count, 1));
            }

            const colors = [];
            for (let i = 0; i < count; i++) {
                const hue = Math.round((i * 137.508) % 360);
                const saturation = 85 + (i % 3) * 5;
                const lightness = 50 + (i % 4) * 4;
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
                    style: { color: '#FFFFFF', fontFamily: 'Plus Jakarta Sans', fontWeight: '700', fontSize: '16px' }
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
                            enabled: window.innerWidth >= 640,
                            format: '<b>{point.name}</b><br>{point.y} suara ({point.percentage:.1f}%)',
                            style: { color: '#FFFFFF', textOutline: '2px #000000', fontFamily: 'Inter', fontSize: '12px', fontWeight: '700' }
                        }
                    }
                },
                legend: {
                    enabled: true,
                    maxHeight: 120,
                    navigation: {
                        enabled: true,
                        activeColor: '#10B981',
                        inactiveColor: 'rgba(255,255,255,0.3)',
                        arrowSize: 12,
                        style: {
                            color: '#FFFFFF',
                            fontSize: '12px',
                            fontWeight: 'bold'
                        }
                    },
                    labelFormat: window.innerWidth < 640 ? '<b>{name}</b>: {y} suara ({percentage:.0f}%)' : '{name}',
                    itemStyle: { color: '#FFFFFF', fontFamily: 'Inter', fontWeight: '600', fontSize: '12px' },
                    itemHoverStyle: { color: '#D4B571' }
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
                series: [{ name: 'Dukungan', data: dataSeries }]
            });
            setTimeout(() => { updateCustomLegendPaginationUI(false); }, 50);
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
            const containerIndividu = document.getElementById('container_bagan_individu');
            const containerRundayan = document.getElementById('container_bagan_rundayan');

            if (!containerIndividu || !containerRundayan) return;

            if (type === 'individu') {
                btnIndividu.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow";
                btnRundayan.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                containerIndividu.classList.remove('hidden');
                containerRundayan.classList.add('hidden');
            } else {
                btnRundayan.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-cyan-500 text-white shadow";
                btnIndividu.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                containerRundayan.classList.remove('hidden');
                containerIndividu.classList.add('hidden');
            }
        }

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
                    style: { color: '#FFFFFF', fontFamily: 'Plus Jakarta Sans', fontWeight: '800', fontSize: isMobile ? '15px' : '22px' }
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
                            format: '<b>{point.name}</b><br>{point.y} suara ({point.percentage:.1f}%)',
                            style: { color: '#FFFFFF', textOutline: '2px #000000', fontFamily: 'Inter', fontSize: '13px', fontWeight: '700' }
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

        function openChartFullscreen() {
            const modal = document.getElementById('chart_fullscreen_modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
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

        // HOVER RUNDAYAN TOOLTIP INTERACTION
        function showRundayanHover(e, ancName) {
            const tooltip = document.getElementById('rundayanHoverTooltip');
            const detail = rundayanDetailMap[ancName];

            document.getElementById('hover_rundayan_title').innerHTML = `<i class="bi bi-people-fill"></i> Rundayan: ${ancName}`;
            
            if (detail) {
                document.getElementById('hover_rundayan_votes').innerText = `${detail.total_votes} Suara`;
                document.getElementById('hover_rundayan_nominators').innerText = detail.nominators.join(', ') || '-';
                document.getElementById('hover_rundayan_candidates').innerText = detail.candidates.join(', ') || '-';
            } else {
                document.getElementById('hover_rundayan_votes').innerText = `0 Suara`;
                document.getElementById('hover_rundayan_nominators').innerText = `-`;
                document.getElementById('hover_rundayan_candidates').innerText = `-`;
            }

            let x = e.clientX + 15;
            let y = e.clientY + 15;

            if (x + 320 > window.innerWidth) x = e.clientX - 330;
            if (y + 200 > window.innerHeight) y = e.clientY - 210;

            tooltip.style.left = `${x}px`;
            tooltip.style.top = `${y}px`;

            tooltip.classList.remove('hidden');
            setTimeout(() => {
                tooltip.classList.remove('opacity-0', 'scale-95');
                tooltip.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function hideRundayanHover() {
            const tooltip = document.getElementById('rundayanHoverTooltip');
            tooltip.classList.remove('opacity-100', 'scale-100');
            tooltip.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                tooltip.classList.add('hidden');
            }, 150);
        }

        // AUTOCOMPLETE SUGGESTIONS SYSTEM
        function setupAutocomplete(inputId, boxId) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            if (!input || !box) return;

            input.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                if (query.length < 1) {
                    box.classList.add('hidden');
                    return;
                }

                const matches = allNamesList.filter(name => name.toLowerCase().includes(query)).slice(0, 8);
                if (matches.length === 0) {
                    box.classList.add('hidden');
                    return;
                }

                box.innerHTML = matches.map(name => `
                    <div class="px-4 py-2.5 text-xs text-white/90 hover:bg-emerald-500/20 hover:text-white cursor-pointer font-medium transition-colors" onclick="selectSuggestion('${inputId}', '${boxId}', '${name.replace(/'/g, "\\'")}')">
                        <i class="bi bi-search text-emerald-400 mr-2"></i> ${name}
                    </div>
                `).join('');

                box.classList.remove('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !box.contains(e.target)) {
                    box.classList.add('hidden');
                }
            });
        }

        function selectSuggestion(inputId, boxId, value) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            if (input) {
                input.value = value;
                input.dispatchEvent(new Event('input'));
            }
            if (box) box.classList.add('hidden');
        }

        // UNIVERSAL REAL-TIME LIVE FILTER SYSTEM FOR CARDS, TABLES & BAGAN
        function setupLiveFilter(inputId, elementSelector) {
            const input = document.getElementById(inputId);
            if (!input) return;

            input.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const items = document.querySelectorAll(elementSelector);
                items.forEach(item => {
                    const text = item.innerText.toLowerCase();
                    if (text.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            render3DPieChart(chartDataIndividu, 'Perolehan Suara Kandidat Individu');

            // Autocomplete setup
            setupAutocomplete('input_search_cards_individu', 'search_cards_individu_box');
            setupAutocomplete('input_search_cards_rundayan', 'search_cards_rundayan_box');
            setupAutocomplete('input_search_bagan', 'search_bagan_box');

            // Live Real-Time Instant Filtering (Without Page Reload)
            setupLiveFilter('input_search_cards_individu', '.card-item-individu');
            setupLiveFilter('input_search_cards_rundayan', '.card-item-rundayan');
            setupLiveFilter('search_table_individu_input', '#table_rekap_individu tbody tr.table-row-item');
            setupLiveFilter('search_table_rundayan_input', '#table_rekap_rundayan tbody tr.table-row-item');
            setupLiveFilter('input_search_bagan', '.bagan-ancestor-card');
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
