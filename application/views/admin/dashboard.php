<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Keluarga H.M Samhudi</title>
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
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
                        gold: {
                            400: '#D4B571',
                            500: '#C29A4E',
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
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Slim Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #15201E; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="bg-teal-950 text-white font-body min-h-screen flex">

    <!-- ================= SIDEBAR ================= -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        
        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Body / Dashboard Content -->
        <div class="p-4 md:p-8 space-y-6 md:space-y-8">

            <!-- Welcome Message Widget -->
            <div class="relative overflow-hidden bg-gradient-to-r from-teal-900 to-teal-800 border border-teal-800 rounded-2xl p-8 flex items-center justify-between shadow-lg">
                <div class="space-y-2 z-10">
                    <h2 class="font-display font-extrabold text-2xl text-white">Halo, <?= htmlspecialchars($admin_name) ?>!</h2>
                    <p class="text-teal-300 text-sm max-w-xl">Halaman ini digunakan untuk mengelola data silsilah keluarga besar, persetujuan forum diskusi, publikasi berita terbaru, penginputan data yayasan, dan pengelolaan data wasiat.</p>
                </div>
                <i class="bi bi-shield-lock-fill text-8xl text-teal-700/20 absolute right-8 bottom-0"></i>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Stat Card 1 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Total Anggota</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_members) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Berita Aktif</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_news) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-newspaper"></i>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Forum Diskusi</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_forums) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Data Wasiat</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_wills) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                </div>

            </div>

            <!-- Aktivitas Terbaru Section -->
            <div class="bg-gradient-to-b from-[#374D49]/20 to-[#374D49]/5 border border-[#4D6B67]/20 rounded-2xl p-8 shadow-lg">
                <h3 class="font-display font-bold text-xl text-white mb-6">Aktivitas Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#4D6B67]/20">
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Aktivitas</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Pengguna</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Waktu</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Status</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php 
                            // Helper function to format date into Indonesian
                            if (!function_exists('format_indo_date')) {
                                function format_indo_date($datetime) {
                                    $months = [
                                        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                    ];
                                    $timestamp = strtotime($datetime);
                                    $d = date('j', $timestamp);
                                    $m = $months[(int)date('n', $timestamp)];
                                    $y = date('Y', $timestamp);
                                    return "$d $m $y";
                                }
                            }
                            ?>
                            <?php if (!empty($recent_activities)): ?>
                                <?php foreach ($recent_activities as $activity): ?>
                                    <tr>
                                        <td class="py-4 text-sm text-white/90 font-medium"><?= htmlspecialchars($activity['aktivitas']) ?></td>
                                        <td class="py-4 text-sm text-white/80"><?= htmlspecialchars($activity['pengguna']) ?></td>
                                        <td class="py-4 text-sm text-white/60"><?= format_indo_date($activity['waktu']) ?></td>
                                        <td class="py-4 text-sm">
                                            <span class="text-white/80"><?= htmlspecialchars($activity['status']) ?></span>
                                        </td>
                                        <td class="py-4 text-sm">
                                            <a href="<?= base_url('admin/' . $activity['tipe'] . '/detail/' . $activity['reff_id']) ?>" class="font-bold text-white hover:underline transition-all">Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Berita Highlight Preview -->
            <div class="bg-gradient-to-r from-yellow-500/10 to-yellow-600/5 border border-yellow-500/25 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-yellow-500/20 border border-yellow-500/30 flex items-center justify-center text-yellow-400">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-white text-sm">Berita Highlight</h3>
                            <p class="text-xs text-white/40">Berita yang tampil sebagai featured card di halaman publik</p>
                        </div>
                    </div>
                    <a href="<?= base_url('admin/berita') ?>" class="text-xs text-teal-400 hover:text-teal-300 flex items-center gap-1 transition-colors">
                        Kelola <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <?php if (!empty($highlighted_news)): ?>
                <div class="flex items-center gap-4 bg-white/5 border border-yellow-500/20 rounded-xl p-4">
                    <?php if (!empty($highlighted_news['thumbnail']) && file_exists('./' . $highlighted_news['thumbnail'])): ?>
                        <img src="<?= base_url($highlighted_news['thumbnail']) ?>"
                             alt="<?= htmlspecialchars($highlighted_news['title']) ?>"
                             class="w-20 h-16 object-cover rounded-lg border border-yellow-500/30 shrink-0">
                    <?php else: ?>
                        <div class="w-20 h-16 rounded-lg bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center shrink-0">
                            <i class="bi bi-newspaper text-yellow-400/50 text-2xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                <i class="bi bi-star-fill text-[8px]"></i> HIGHLIGHT AKTIF
                            </span>
                        </div>
                        <p class="font-semibold text-white text-sm leading-snug line-clamp-2">
                            <?= htmlspecialchars($highlighted_news['title']) ?>
                        </p>
                        <p class="text-xs text-white/40 mt-1">
                            Oleh <?= htmlspecialchars($highlighted_news['author_name'] ?? 'Admin') ?>
                        </p>
                    </div>
                    <a href="<?= base_url('admin/berita_highlight/' . $highlighted_news['id']) ?>"
                       onclick="return confirm('Cabut highlight berita ini?')"
                       title="Cabut Highlight"
                       class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-500/15 text-yellow-300 border border-yellow-500/30 hover:bg-yellow-500/30 transition-all">
                        <i class="bi bi-star-fill"></i> Cabut
                    </a>
                </div>
                <?php else: ?>
                <div class="flex flex-col items-center justify-center py-6 gap-2 text-white/30">
                    <i class="bi bi-star text-3xl"></i>
                    <p class="text-sm">Belum ada berita yang di-highlight.</p>
                    <a href="<?= base_url('admin/berita') ?>" class="text-xs text-teal-400 hover:text-teal-300 transition-colors">
                        → Pergi ke Kelola Berita untuk set highlight
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Banner Settings -->
            <div id="banner-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-images"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Banner Landing Page</h3>
                        <p class="text-xs text-teal-400">Drag & drop gambar atau klik upload dari file explorer</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('banner_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('banner_success') ?>
                </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('banner_error')): ?>
                <div class="bg-red-500/20 border border-red-500/40 text-red-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('banner_error') ?>
                </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" class="space-y-4" id="banner-form">
                    <input type="hidden" name="upload_banner" value="1">

                    <div class="relative group cursor-pointer" onclick="previewBanner(this)">
                        <img src="<?= base_url('assets/images/' . $selected_banner) ?>" class="w-full h-64 object-cover rounded-xl border border-teal-700" id="banner-preview-img">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-xl flex items-center justify-center">
                            <i class="bi bi-arrows-fullscreen text-white text-2xl opacity-0 group-hover:opacity-100 transition-all"></i>
                        </div>
                    </div>

                    <div class="drop-zone border-2 border-dashed border-teal-700 rounded-xl p-6 text-center cursor-pointer hover:border-teal-500 transition-all" onclick="document.getElementById('banner-upload').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleDrop(event)">
                        <i class="bi bi-cloud-arrow-up text-3xl text-teal-400"></i>
                        <p class="text-sm text-teal-300 mt-2">Klik atau drag & drop gambar banner</p>
                        <input id="banner-upload" type="file" name="banner_file" accept="image/*" class="hidden" onchange="previewFile(this, 'banner-preview-img')">
                    </div>

                    <div class="text-center">
                        <button type="submit" id="save-banner-btn" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Carousel Settings -->
            <div id="carousel-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-images"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Carousel Keluarga</h3>
                        <p class="text-xs text-teal-400">Kelola gambar & caption carousel di halaman utama</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('carousel_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('carousel_success') ?>
                </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('carousel_settings_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('carousel_settings_success') ?>
                </div>
                <?php endif; ?>
                <?php
                    $cs_frame = $carousel_settings['frame'] ?? 'original';
                    $cs_frame_color = $carousel_settings['frame_color'] ?? '#ffffff';
                    if (!preg_match('/^#[0-9a-fA-F]{3,6}$/', $cs_frame_color)) $cs_frame_color = '#ffffff';
                    $allowed_frames_view = ['original', 'blue_floral', 'green_vines', 'ethnic', 'gold'];
                    if (!in_array($cs_frame, $allowed_frames_view)) $cs_frame = 'original';

                    // Index foto aktif awal (prioritas: GET query string -> Session CI -> 0)
                    $sess_active = $this->session->userdata('admin_active_photo');
                    $init_active_idx = isset($_GET['active_photo']) ? (int)$_GET['active_photo'] : (($sess_active !== null) ? (int)$sess_active : 0);
                    if ($init_active_idx < 0 || $init_active_idx >= count($carousel_items)) {
                        $init_active_idx = 0;
                    }
                    
                    $frame_labels = [
                        'original'          => 'Polaroid',
                        'green_vines'       => 'Daun Hijau',
                        'blue_wave'         => 'Gelombang Biru',
                        'flowers_stitch'    => 'Bunga Rajut',
                        'yellow_sunflowers' => 'Bunga Matahari',
                        'green_dots'        => 'Bulatan Hijau',
                        'green_waves'       => 'Gelombang Hijau',
                        'pink_glitter'      => 'Pink Berkilau',
                        'purple_stripes'    => 'Garis Ungu',
                        'black_dots'        => 'Titik Hitam',
                        'orange_spirals'    => 'Spiral Oranye',
                        'green_orange_wave' => 'Awan Oranye',
                        'abstract_wavy'     => 'Gelombang Warna',
                        'checkered'         => 'Catur Hitam Putih',
                        'zigzag_colorful'   => 'Gelombang Warna-Warni',
                        'ethnic_red'        => 'Rajut Merah Etnik',
                    ];

                    $frame_descriptions = [
                        'original'          => 'Bingkai Putih',
                        'green_vines'       => 'Motif Hijau',
                        'blue_wave'         => 'Renda Biru',
                        'flowers_stitch'    => 'Motif Bunga Rajut',
                        'yellow_sunflowers' => 'Latar Awan Biru',
                        'green_dots'        => 'Motif Bulat',
                        'green_waves'       => 'Motif Gelombang',
                        'pink_glitter'      => 'Motif Pink',
                        'purple_stripes'    => 'Motif Garis',
                        'black_dots'        => 'Renda Hitam',
                        'orange_spirals'    => 'Motif Spiral',
                        'green_orange_wave' => 'Bingkai Gelombang',
                        'abstract_wavy'     => 'Warna Abstrak',
                        'checkered'         => 'Motif Catur Klasik',
                        'zigzag_colorful'   => 'Gelombang Warna Terang',
                        'ethnic_red'        => 'Rajutan Etnik Cantik',
                    ];

                    $frame_images = [
                        'green_vines'       => 'frame_green_vines.png',
                        'blue_wave'         => 'frame_blue_wave.png',
                        'flowers_stitch'    => 'frame_flowers_stitch.png',
                        'yellow_sunflowers' => 'frame_yellow_sunflowers.png',
                        'green_dots'        => 'frame_green_dots.png',
                        'green_waves'       => 'frame_green_waves.png',
                        'pink_glitter'      => 'frame_pink_glitter.png',
                        'purple_stripes'    => 'frame_purple_stripes.png',
                        'black_dots'        => 'frame_black_dots.png',
                        'orange_spirals'    => 'frame_orange_spirals.png',
                        'green_orange_wave' => 'frame_green_orange_wave.png',
                        'abstract_wavy'     => 'frame_abstract_wavy.png',
                        'checkered'         => 'frame_checkered.png',
                        'zigzag_colorful'   => 'frame_zigzag_colorful.png',
                        'ethnic_red'        => 'frame_ethnic_red.png',
                    ];

                    $init_item  = $carousel_items[$init_active_idx] ?? ($carousel_items[0] ?? []);
                    $init_color = !empty($init_item['frame_color']) ? $init_item['frame_color'] : '#ffffff';
                    $init_frame = !empty($init_item['frame_style']) ? $init_item['frame_style'] : 'original';
                    $is_init_framed = ($init_frame !== 'original');
                    
                    $init_overlay_bg = isset($frame_images[$init_frame]) ? base_url('assets/images/' . $frame_images[$init_frame]) : '';
                ?>

                <!-- Form terpisah untuk simpan model bingkai (mencegah HTML nested form) -->
                <form method="post" id="frame-settings-form" style="display:none;">
                    <input type="hidden" name="save_carousel_settings" value="1">
                    <input type="hidden" name="carousel_frame" id="global-frame-input" value="<?= htmlspecialchars($cs_frame) ?>">
                    <input type="hidden" name="carousel_frame_color" value="<?= htmlspecialchars($cs_frame_color) ?>">
                    <input type="hidden" name="active_photo_index" id="active-photo-index-input-settings" value="<?= $init_active_idx ?>">
                </form>

                <!-- ── FORM TERPUSAT CAROUSEL (PANEL EDIT WARNA LATAR PER-FOTO) ── -->
                <form method="post" enctype="multipart/form-data" class="space-y-6" id="carousel-form">
                    <input type="hidden" name="save_carousel" value="1">
                    <input type="hidden" name="active_photo_index" id="active-photo-index-input" value="<?= $init_active_idx ?>">

                    <!-- ── PANEL KONTROL EDIT WARNA LATAR (UNTUK FOTO TERPILIH) ── -->
                    <div class="bg-teal-800/30 border border-teal-700/80 rounded-2xl p-5 shadow-xl relative" id="photo-editor-panel">
                        
                        <!-- Header Panel Edit -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-5 border-b border-teal-700/60">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-teal-600/40 text-teal-300 border border-teal-500/40 flex items-center justify-center font-bold">
                                    <i class="bi bi-palette-fill"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white uppercase tracking-wide flex items-center gap-2">
                                        PANEL EDIT WARNA BINGKAI <span class="text-amber-400 font-mono text-xs px-2 py-0.5 rounded bg-teal-900/80 border border-amber-400/40" id="editor-active-tag">FOTO <?= $init_active_idx + 1 ?></span>
                                    </h4>
                                    <p class="text-[11px] text-teal-300">Pilih foto dari daftar di bawah untuk mengubah warna latar pinggirannya di sini.</p>
                                </div>
                            </div>

                            <!-- Selector Foto Mana Yang Mau Dieksekusi -->
                            <div class="flex items-center gap-2 bg-teal-900/60 p-1.5 rounded-xl border border-teal-700">
                                <label class="text-xs text-teal-300 font-semibold pl-2 flex items-center gap-1 whitespace-nowrap">
                                    <i class="bi bi-hand-index-thumb"></i> Edit Foto:
                                </label>
                                <select id="active-photo-selector" onchange="selectPhotoToEdit(parseInt(this.value))" class="bg-teal-800 border border-teal-600 rounded-lg px-3 py-1 text-white text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-teal-400 cursor-pointer">
                                    <?php foreach ($carousel_items as $i => $item): 
                                        $uploader = isset($item['uploader_name']) ? ' (' . $item['uploader_name'] . ')' : ' (Admin)';
                                    ?>
                                    <option value="<?= $i ?>" <?= ($i === $init_active_idx) ? 'selected' : '' ?>>Foto <?= $i + 1 ?><?= htmlspecialchars($uploader) ?>: <?= htmlspecialchars(mb_strimwidth($item['caption'], 0, 20, '...')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row gap-6 items-start w-full">

                            <!-- Sisi Kiri: Pilihan Warna & Pilihan Model Bingkai -->
                            <div class="flex-1 min-w-0 space-y-5 w-full">
                                
                                <!-- 1. Pilih Warna Latar Foto Terpilih -->
                                <div class="space-y-2">
                                    <label class="text-xs text-teal-300 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="bi bi-palette-fill text-teal-400"></i> 1. Pilih Warna Latar Pinggiran (Foto Terpilih)
                                    </label>
                                    
                                    <div class="flex items-center gap-4 bg-teal-900/40 p-4 rounded-xl border border-teal-700/60">
                                        <input type="color" id="top-color-picker"
                                               value="<?= htmlspecialchars($init_color) ?>"
                                               class="w-14 h-14 rounded-xl border-2 border-teal-400 cursor-pointer bg-transparent p-0.5 flex-shrink-0"
                                               oninput="setActivePhotoColor(this.value)">
                                        
                                        <div class="flex-1 space-y-2.5">
                                            <div class="bg-teal-950/80 border border-teal-700 rounded-lg px-3 py-1 flex justify-between items-center max-w-[200px]">
                                                <span class="text-white/50 text-[10px] uppercase font-mono">Kode Hex</span>
                                                <span id="top-color-hex" class="text-white font-mono text-xs font-bold"><?= htmlspecialchars($init_color) ?></span>
                                            </div>
                                            
                                            <!-- Presets warna cepat -->
                                            <div class="flex gap-2 flex-wrap items-center">
                                                <?php
                                                $presets = [
                                                    '#ffffff' => 'Putih',
                                                    '#FFF8F0' => 'Krem',
                                                    '#F5EBE2' => 'Coklat Muda',
                                                    '#D4B896' => 'Coklat Pastel',
                                                    '#C8A84E' => 'Emas',
                                                    '#1B3835' => 'Teal Gelap',
                                                    '#0F211F' => 'Hijau Hitam',
                                                    '#1a1a1a' => 'Hitam',
                                                ];
                                                foreach ($presets as $hex => $name): ?>
                                                <button type="button"
                                                        onclick="setActivePhotoColor('<?= $hex ?>')"
                                                        title="<?= $name ?>"
                                                        class="w-6 h-6 rounded-full border-2 border-teal-600 hover:scale-110 hover:border-white transition-all flex-shrink-0"
                                                        style="background:<?= $hex ?>"></button>
                                                <?php endforeach; ?>
                                                <button type="button" onclick="setActivePhotoColor('#ffffff')"
                                                        class="text-[10px] text-teal-300 hover:text-white px-2.5 py-1 rounded-md border border-teal-600 hover:border-teal-400 transition-all font-semibold">
                                                    Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Pilih Model Bingkai (Foto Terpilih) -->
                                <div class="space-y-2 pt-2 border-t border-teal-700/50 w-full min-w-0 overflow-hidden">
                                    <div class="flex items-center justify-between">
                                        <label class="text-xs text-teal-300 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="bi bi-aspect-ratio-fill text-amber-400"></i> 2. Pilih Model Bingkai (Foto Terpilih)
                                        </label>
                                        <span class="text-[10px] text-teal-400 font-semibold flex items-center gap-1">
                                            Geser Opsi <i class="bi bi-arrow-right-short"></i>
                                        </span>
                                    </div>

                                    <!-- Horizontal Scroll Container -->
                                    <div class="flex items-center gap-3 overflow-x-auto pb-3 pt-1 w-full max-w-full">
                                        
                                        <!-- Opsi 1: Original Polaroid (Default) -->
                                        <div id="frame-option-card-original" onclick="setActivePhotoFrame('original')"
                                             class="relative flex items-center gap-2.5 p-2.5 rounded-xl border-2 cursor-pointer transition-all min-w-[170px] flex-shrink-0 <?= ($init_frame === 'original') ? 'border-amber-400 bg-amber-400/10' : 'border-teal-700/60 bg-teal-900/40 hover:border-teal-500' ?>">
                                            <div class="w-9 h-11 bg-white border border-gray-300 rounded shadow-sm p-1 flex flex-col justify-between flex-shrink-0">
                                                <div class="bg-teal-700/30 w-full h-6 rounded-sm"></div>
                                                <div class="w-full h-1 bg-gray-300 rounded-full mx-auto"></div>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-bold text-white mb-0.5">Polaroid</h5>
                                                <p class="text-[10px] text-teal-300">Bingkai Putih</p>
                                            </div>
                                        </div>

                                        <!-- Opsi Bingkai PNG Dinamis -->
                                        <?php foreach ($frame_images as $f_key => $f_file): ?>
                                        <div id="frame-option-card-<?= $f_key ?>" onclick="setActivePhotoFrame('<?= $f_key ?>')"
                                             class="relative flex items-center gap-2.5 p-2.5 rounded-xl border-2 cursor-pointer transition-all min-w-[170px] flex-shrink-0 <?= ($init_frame === $f_key) ? 'border-amber-400 bg-amber-400/10' : 'border-teal-700/60 bg-teal-900/40 hover:border-teal-500' ?>">
                                            <div class="w-9 h-11 relative rounded overflow-hidden shadow-sm flex-shrink-0" style="background-image: url('<?= base_url('assets/images/' . $f_file) ?>'); background-size: cover; background-position: center;">
                                                <div class="w-full h-full p-1">
                                                    <div class="bg-teal-700/30 w-full h-full rounded-sm"></div>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-bold text-white mb-0.5"><?= $frame_labels[$f_key] ?></h5>
                                                <p class="text-[10px] text-teal-300"><?= $frame_descriptions[$f_key] ?></p>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>

                                    </div>
                                </div>

                            </div>

                            <!-- Sisi Kanan: Live Preview Tampilan Foto & Bingkai -->
                            <div class="w-full lg:w-56 flex flex-col items-center gap-2 lg:border-l lg:border-teal-700/60 lg:pl-6 flex-shrink-0">
                                <div class="flex items-center gap-1 text-[10px] text-teal-300 font-bold uppercase tracking-wider">
                                    <i class="bi bi-eye-fill"></i> PREVIEW TAMPILAN FOTO
                                </div>
                                <div class="bg-teal-900/40 rounded-xl p-3 border border-teal-700 flex items-center justify-center w-full min-h-[250px]">
                                    <div id="live-frame-preview" class="carousel-card <?= $is_init_framed ? 'carousel-card-with-frame' : 'carousel-card-original' ?>"
                                         style="position:relative; width:170px; transform:none; top:auto; left:auto;
                                                background-color:<?= $is_init_framed ? 'transparent' : htmlspecialchars($init_color) ?>;
                                                padding:<?= $is_init_framed ? '0 !important' : '10px !important' ?>;
                                                border-radius:0; border:none !important; box-shadow:0 8px 20px rgba(0,0,0,.3);
                                                transition:none; cursor:default; box-sizing:border-box;">
                                        
                                        <!-- Overlay Frame PNG Live -->
                                        <div id="preview-overlay" class="card-frame-overlay"
                                             style="<?= $is_init_framed ? "display:block; background-image:url('" . $init_overlay_bg . "');" : "display:none;" ?> position:absolute; top:0; left:0; width:100%; height:100%; z-index:10; background-size:100% 100%; pointer-events:none;"></div>

                                        <img id="preview-card-img" src="<?= base_url('assets/images/' . ($init_item['file'] ?? 'family/family1.png')) ?>"
                                             style="width:100% !important; height:<?= $is_init_framed ? '230px' : '210px' ?> !important; padding:<?= $is_init_framed ? '8px 6px 8px 6px' : '0' ?> !important; object-fit:cover !important; display:block; border-radius:2px !important; position:relative; z-index:1;">
                                        
                                        <div class="carousel-caption" id="live-frame-caption"
                                             style="position:absolute !important; bottom:<?= $is_init_framed ? '12px' : '28px' ?> !important; left:10px !important; right:10px !important; margin:0 auto; text-align:center; font-family:'Brittany Signature', cursive; font-size:14px !important; color:<?= $is_init_framed ? '#ffffff' : '#4a4a4a' ?> !important; text-shadow:<?= $is_init_framed ? '0px 2px 5px rgba(0,0,0,0.95)' : 'none' ?> !important; z-index:15;">
                                            <?= htmlspecialchars($init_item['caption'] ?? 'Keluarga') ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Simpan Carousel -->
                                <button type="submit" name="save_carousel" value="1" class="w-full bg-amber-400 hover:bg-amber-300 text-teal-950 font-bold px-4 py-2.5 rounded-xl transition-all shadow-md text-xs flex items-center justify-center gap-1.5 cursor-pointer mt-1">
                                    <i class="bi bi-check-circle-fill text-sm"></i> Simpan Perubahan Carousel
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- ── DAFTAR FOTO CAROUSEL ── -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-teal-300 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-grid-fill"></i> Daftar Foto Carousel
                            </h4>
                            <span class="text-[11px] text-teal-400">Klik "Edit Foto Ini" untuk mengubah bingkai & warna latarnya</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="carousel-grid">
                            <?php foreach ($carousel_items as $i => $item):
                                $item_color = !empty($item['frame_color']) ? $item['frame_color'] : '#ffffff';
                                $item_frame = !empty($item['frame_style']) ? $item['frame_style'] : 'original';
                            ?>
                            <div class="bg-teal-800/40 border border-teal-700 rounded-xl p-4 space-y-3 carousel-item-card transition-all" id="carousel-card-item-<?= $i ?>">
                                
                                <!-- Hidden input simpan source, file, frame_color & frame_style per-item -->
                                <input type="hidden" name="carousel_item_source[]" value="<?= htmlspecialchars($item['source'] ?? 'admin') ?>">
                                <input type="hidden" name="carousel_item_file[]" value="<?= htmlspecialchars($item['file']) ?>">
                                <input type="hidden" name="carousel_item_color[]" id="item-color-input-<?= $i ?>" value="<?= htmlspecialchars($item_color) ?>">
                                <input type="hidden" name="carousel_item_frame[]" id="item-frame-input-<?= $i ?>" value="<?= htmlspecialchars($item_frame) ?>">

                                <!-- Preview Gambar & Fullscreen Modal -->
                                <div class="relative group cursor-pointer" onclick="previewCarousel(this)">
                                    <img src="<?= base_url('assets/images/' . $item['file']) ?>" class="w-full h-36 object-cover rounded-lg border border-teal-700">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">
                                        <i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>
                                    </div>
                                </div>

                                <!-- Info Pengunggah -->
                                <div class="flex items-center justify-between text-[10px] text-teal-400">
                                    <span>Pengunggah:</span>
                                    <span class="font-bold text-amber-400"><?= htmlspecialchars($item['uploader_name'] ?? 'Admin') ?></span>
                                </div>

                                <!-- Input Caption -->
                                <div>
                                    <label class="text-[10px] text-teal-400 font-semibold block mb-1">Caption Foto:</label>
                                    <input type="text" name="captions[]" value="<?= htmlspecialchars($item['caption']) ?>" 
                                           oninput="updateActivePhotoCaption(<?= $i ?>, this.value)"
                                           class="w-full bg-teal-800 border border-teal-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Caption Foto">
                                </div>

                                <!-- Tombol Eksekusi Edit Warna Foto Ini -->
                                <button type="button" onclick="selectPhotoToEdit(<?= $i ?>)" id="btn-select-card-<?= $i ?>" 
                                        class="w-full py-2 bg-teal-700/60 hover:bg-teal-600 text-teal-200 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all border border-teal-500/50">
                                    <i class="bi bi-palette-fill"></i> Edit Bingkai & Warna
                                </button>

                                <!-- Ganti Foto & Hapus Card -->
                                <div class="flex gap-2">
                                    <div class="drop-zone-carousel flex-1 border-2 border-dashed border-teal-700 rounded-lg p-3 text-center cursor-pointer hover:border-teal-500 transition-all text-xs" onclick="document.getElementById('carousel-upload-<?= $i ?>').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleCarouselDrop(event, <?= $i ?>)">
                                        <i class="bi bi-cloud-arrow-up text-teal-400"></i>
                                        <p class="text-teal-400 mt-1">Ganti Foto</p>
                                        <input id="carousel-upload-<?= $i ?>" type="file" name="carousel_file[]" accept="image/*" class="hidden" onchange="previewCarouselInput(this, <?= $i ?>)">
                                    </div>
                                    <button type="button" onclick="deleteCarousel(<?= $i ?>)" class="px-3 py-1.5 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/40 transition-all text-xs">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <input type="hidden" name="delete_index" value="">
                    
                    <!-- ── TOMBOL SIMPAN TUNGGAL ── -->
                    <div class="flex gap-3 pt-4 border-t border-teal-700/60">
                        <button type="submit" id="save-carousel-btn" name="save_carousel" value="1" class="bg-teal-500 text-white font-display font-semibold px-8 py-3 rounded-full hover:bg-teal-400 transition-all shadow-lg text-sm flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-base"></i> Simpan Perubahan Carousel
                        </button>
                        <button type="button" onclick="addCarouselCard()" class="border border-dashed border-teal-600 text-teal-400 hover:text-white font-display font-semibold px-6 py-3 rounded-full hover:bg-teal-800/50 transition-all text-sm flex items-center gap-2">
                            <i class="bi bi-plus-lg"></i> Tambah Foto Baru
                        </button>
                    </div>
                </form>
            </div>

            <!-- Intro Text Settings -->
            <div id="intro-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-4 md:p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-quote"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Teks Intro</h3>
                        <p class="text-xs text-teal-400">Edit teks sambutan di halaman utama (bagian foto + card)</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('intro_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('intro_success') ?>
                </div>
                <?php endif; ?>

                <form method="post" class="space-y-4" id="intro-form">
                    <input type="hidden" name="save_intro" value="1">
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Teks Intro</label>
                        <textarea name="intro_text" rows="5" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Tulis teks intro..."><?= htmlspecialchars($intro_text) ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Nama Pengirim</label>
                        <input type="text" name="intro_sender" value="<?= htmlspecialchars($intro_sender) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="From (nama)">
                    </div>
                    <div class="text-center">
                        <button type="submit" id="save-intro-btn" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sambutan Text Settings -->
            <div id="sambutan-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-4 md:p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Teks Sambutan</h3>
                        <p class="text-xs text-teal-400">Edit teks sambutan di halaman utama</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('sambutan_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('sambutan_success') ?>
                </div>
                <?php endif; ?>

                <form method="post" class="space-y-4" id="sambutan-form">
                    <input type="hidden" name="save_sambutan" value="1">
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Judul</label>
                        <input type="text" name="sambutan_title" value="<?= htmlspecialchars($sambutan_title) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Paragraf (masing-masing di baris terpisah)</label>
                        <?php $par_text = implode("\n\n", is_array($sambutan_pars) ? $sambutan_pars : []); ?>
                        <textarea name="sambutan_pars" rows="8" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm"><?= htmlspecialchars($par_text) ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Penutup</label>
                        <input type="text" name="sambutan_closing" value="<?= htmlspecialchars($sambutan_closing) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Pengirim</label>
                        <input type="text" name="sambutan_sender" value="<?= htmlspecialchars($sambutan_sender) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm">
                    </div>
                    <div class="text-center">
                        <button type="submit" id="save-sambutan-btn" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan Sambutan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lokasi Pemakaman Settings -->
            <div id="makam-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-4 md:p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Lokasi Pemakaman</h3>
                        <p class="text-xs text-teal-400">Edit alamat, link maps, dan foto pemakaman</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('makam_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('makam_success') ?>
                </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" class="space-y-4" id="makam-form">
                    <input type="hidden" name="save_makam" value="1">
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Alamat</label>
                        <textarea name="makam_address" rows="3" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Alamat pemakaman..."><?= htmlspecialchars($makam_address) ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Link Embed (buat peta)</label>
                        <input type="text" name="makam_maps_url" value="<?= htmlspecialchars($makam_maps_url) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="https://www.google.com/maps/embed?pb=...">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Link Google Maps (buat tombol Lihat Detail & Rute)</label>
                        <input type="text" name="makam_maps_link" value="<?= htmlspecialchars($makam_maps_link) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="https://www.google.com/maps/search/?api=1&query=...">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Foto Pemakaman</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3" id="makam-photo-grid">
                            <?php foreach ($makam_photos as $i => $photo): ?>
                            <div class="bg-teal-800/40 border border-teal-700 rounded-xl p-3 space-y-2 relative">
                                <button type="button" onclick="deleteMakamPhoto(<?= $i ?>)" class="absolute top-1 right-1 w-6 h-6 bg-red-500/80 hover:bg-red-500 text-white rounded-full flex items-center justify-center text-sm leading-none z-10">&times;</button>
                                <div class="relative group cursor-pointer" onclick="previewCarousel(this)">
                                    <img src="<?= base_url($photo) ?>" class="w-full h-24 object-cover rounded-lg border border-teal-700">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">
                                        <i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <label class="text-sm text-teal-400 font-semibold mb-1 block">Tambah Foto Baru</label>
                            <div class="drop-zone border-2 border-dashed border-teal-700 rounded-xl p-4 text-center cursor-pointer hover:border-teal-500 transition-all" onclick="document.getElementById('makam-photo-new').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleMakamDrop(event)">
                                <i class="bi bi-cloud-arrow-up text-2xl text-teal-400"></i>
                                <p class="text-sm text-teal-300 mt-1">Klik atau drag & drop foto</p>
                                <input id="makam-photo-new" type="file" name="makam_photo_new[]" accept="image/*" multiple class="hidden" onchange="handleMakamFiles(this)">
                            </div>
                            <div id="makam-new-previews" class="row g-2 mt-2"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" id="save-makam-btn" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </main>

    <!-- Modal Preview -->
    <div id="banner-modal" class="fixed inset-0 bg-black/80 z-[9999] hidden items-center justify-center" onclick="if(event.target===this)closePreview()">
        <button onclick="closePreview()" class="absolute top-6 right-6 text-white text-4xl hover:text-gray-300 transition-all">&times;</button>
        <img id="banner-modal-img" class="max-w-[90vw] max-h-[90vh] rounded-2xl shadow-2xl">
    </div>

    <script>
        function previewBanner(el) {
            const img = el.querySelector('img');
            const modal = document.getElementById('banner-modal');
            const modalImg = document.getElementById('banner-modal-img');
            modalImg.src = img.src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            const modal = document.getElementById('banner-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    const zone = e.target.closest('.drop-zone');
    zone.classList.remove('border-teal-400', 'bg-teal-800/50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const fileInput = document.getElementById('banner-upload');
        if (fileInput) {
            fileInput.files = files;
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('banner-preview-img').src = ev.target.result;
            };
            reader.readAsDataURL(files[0]);
        }
    }
}

function previewFile(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewCarousel(el) {
    const img = el.querySelector('img');
    const modal = document.getElementById('banner-modal');
    const modalImg = document.getElementById('banner-modal-img');
    modalImg.src = img.src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

const frameImagesMap = {
    'green_vines': '<?= base_url("assets/images/frame_green_vines.png") ?>',
    'blue_wave': '<?= base_url("assets/images/frame_blue_wave.png") ?>',
    'flowers_stitch': '<?= base_url("assets/images/frame_flowers_stitch.png") ?>',
    'yellow_sunflowers': '<?= base_url("assets/images/frame_yellow_sunflowers.png") ?>',
    'green_dots': '<?= base_url("assets/images/frame_green_dots.png") ?>',
    'green_waves': '<?= base_url("assets/images/frame_green_waves.png") ?>',
    'pink_glitter': '<?= base_url("assets/images/frame_pink_glitter.png") ?>',
    'purple_stripes': '<?= base_url("assets/images/frame_purple_stripes.png") ?>',
    'black_dots': '<?= base_url("assets/images/frame_black_dots.png") ?>',
    'orange_spirals': '<?= base_url("assets/images/frame_orange_spirals.png") ?>',
    'green_orange_wave': '<?= base_url("assets/images/frame_green_orange_wave.png") ?>',
    'abstract_wavy': '<?= base_url("assets/images/frame_abstract_wavy.png") ?>',
    'checkered': '<?= base_url("assets/images/frame_checkered.png") ?>',
    'zigzag_colorful': '<?= base_url("assets/images/frame_zigzag_colorful.png") ?>',
    'ethnic_red': '<?= base_url("assets/images/frame_ethnic_red.png") ?>'
};

let currentActiveIdx = <?= $init_active_idx ?>;

function selectPhotoToEdit(idx) {
    const cards = document.querySelectorAll('.carousel-item-card');
    if (cards.length === 0) return;
    currentActiveIdx = idx;

    // Simpan posisi index foto aktif ke hidden input di kedua form
    const activeInput = document.getElementById('active-photo-index-input');
    if (activeInput) activeInput.value = idx;

    const activeInputSettings = document.getElementById('active-photo-index-input-settings');
    if (activeInputSettings) activeInputSettings.value = idx;

    // Update tag di header editor panel
    const tag = document.getElementById('editor-active-tag');
    if (tag) tag.textContent = 'FOTO ' + (idx + 1);

    // Update dropdown selector
    const sel = document.getElementById('active-photo-selector');
    if (sel) sel.value = idx;

    // Highlighting kartu di daftar bawah
    cards.forEach((card, i) => {
        const btn = document.getElementById('btn-select-card-' + i);
        if (i === idx) {
            card.classList.add('ring-2', 'ring-teal-400', 'border-teal-400', 'bg-teal-800/80');
            card.classList.remove('bg-teal-800/40');
            if (btn) {
                btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Sedang Di-edit';
                btn.className = 'w-full py-2 bg-teal-500 text-teal-950 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 transition-all shadow-md';
            }
        } else {
            card.classList.remove('ring-2', 'ring-teal-400', 'border-teal-400', 'bg-teal-800/80');
            card.classList.add('bg-teal-800/40');
            if (btn) {
                btn.innerHTML = '<i class="bi bi-palette-fill"></i> Edit Foto Ini';
                btn.className = 'w-full py-2 bg-teal-700/60 hover:bg-teal-600 text-teal-200 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all border border-teal-500/50';
            }
        }
    });

    // Ambil nilai color & frame dari hidden input kartu terpilih
    const hiddenColor = document.getElementById('item-color-input-' + idx);
    const colorHex    = hiddenColor ? hiddenColor.value : '#ffffff';

    const hiddenFrame = document.getElementById('item-frame-input-' + idx);
    const frameStyle  = hiddenFrame ? hiddenFrame.value : 'original';

    // Update color picker & hex code di top panel
    const topColorPicker = document.getElementById('top-color-picker');
    const topColorHex    = document.getElementById('top-color-hex');
    if (topColorPicker) topColorPicker.value = colorHex;
    if (topColorHex)    topColorHex.textContent = colorHex;

    // Update tombol opsi frame di top panel (support multiple options)
    ['original', 'green_vines', 'blue_wave', 'flowers_stitch', 'yellow_sunflowers', 'green_dots', 'green_waves', 'pink_glitter', 'purple_stripes', 'black_dots', 'orange_spirals', 'green_orange_wave', 'abstract_wavy', 'checkered', 'zigzag_colorful', 'ethnic_red'].forEach(function(opt) {
        const optionCard = document.getElementById('frame-option-card-' + opt);
        if (optionCard) {
            const isMatch = (opt === frameStyle);
            if (isMatch) {
                optionCard.classList.remove('border-teal-700/60', 'bg-teal-900/40');
                optionCard.classList.add('border-amber-400', 'bg-amber-400/10');
            } else {
                optionCard.classList.remove('border-amber-400', 'bg-amber-400/10');
                optionCard.classList.add('border-teal-700/60', 'bg-teal-900/40');
            }
        }
    });

    // Refresh Live Preview
    refreshLivePreview(idx, colorHex, frameStyle);
}

function refreshLivePreview(idx, colorHex, frameStyle) {
    const cards = document.querySelectorAll('.carousel-item-card');
    const card  = cards[idx];
    if (!card) return;

    if (!frameStyle) {
        const hiddenFrame = document.getElementById('item-frame-input-' + idx);
        frameStyle = hiddenFrame ? hiddenFrame.value : 'original';
    }

    const imgEl     = card.querySelector('.relative.group img');
    const captionEl = card.querySelector('input[name="captions[]"]');

    const preview        = document.getElementById('live-frame-preview');
    const overlay        = document.getElementById('preview-overlay');
    const previewImg     = document.getElementById('preview-card-img');
    const previewCaption = document.getElementById('live-frame-caption');

    if (previewImg && imgEl)         previewImg.src = imgEl.src;
    if (previewCaption && captionEl) previewCaption.textContent = captionEl.value || 'Keluarga';

    if (preview && overlay && previewImg) {
        const isFramed = (frameStyle !== 'original');
        if (isFramed && frameImagesMap[frameStyle]) {
            preview.classList.remove('carousel-card-original');
            preview.classList.add('carousel-card-with-frame');
            preview.style.padding = '0';
            preview.style.backgroundColor = 'transparent';

            overlay.style.backgroundImage = "url('" + frameImagesMap[frameStyle] + "')";
            overlay.style.display = 'block';

            previewImg.style.padding = '8px 6px 8px 6px';
            previewImg.style.height = '230px';

            if (previewCaption) {
                previewCaption.style.bottom = '12px';
                previewCaption.style.color = '#ffffff';
                previewCaption.style.textShadow = '0px 2px 5px rgba(0,0,0,0.95)';
            }
        } else {
            preview.classList.remove('carousel-card-with-frame');
            preview.classList.add('carousel-card-original');
            preview.style.padding = '10px';
            preview.style.backgroundColor = colorHex || '#ffffff';

            overlay.style.backgroundImage = 'none';
            overlay.style.display = 'none';

            previewImg.style.padding = '0';
            previewImg.style.height = '210px';

            if (previewCaption) {
                previewCaption.style.bottom = '28px';
                previewCaption.style.color = '#4a4a4a';
                previewCaption.style.textShadow = 'none';
            }
        }
    }
}

function setActivePhotoColor(colorHex) {
    const hiddenColor = document.getElementById('item-color-input-' + currentActiveIdx);
    if (hiddenColor) hiddenColor.value = colorHex;

    selectPhotoToEdit(currentActiveIdx);
}

function setActivePhotoFrame(frameStyle) {
    const hiddenFrame = document.getElementById('item-frame-input-' + currentActiveIdx);
    if (hiddenFrame) hiddenFrame.value = frameStyle;

    selectPhotoToEdit(currentActiveIdx);
}

function updateActivePhotoCaption(idx, captionText) {
    // Update selector dropdown label
    const sel = document.getElementById('active-photo-selector');
    if (sel && sel.options[idx]) {
        let shortText = captionText.length > 20 ? captionText.substring(0,20) + '...' : captionText;
        let optionText = sel.options[idx].text;
        let uploaderPart = optionText.match(/\(.*\)/) ? optionText.match(/\(.*\)/)[0] : ' (Admin)';
        sel.options[idx].text = 'Foto ' + (idx + 1) + uploaderPart + ': ' + (shortText || 'Keluarga');
    }

    if (idx === currentActiveIdx) {
        const previewCaption = document.getElementById('live-frame-caption');
        if (previewCaption) previewCaption.textContent = captionText || 'Keluarga';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Jalankan selectPhotoToEdit untuk restore state foto aktif
    selectPhotoToEdit(<?= $init_active_idx ?>);

    // Intercept SETIAP form submit — paksa active_photo_index selalu sinkron ke currentActiveIdx
    const carouselForm = document.getElementById('carousel-form');
    if (carouselForm) {
        carouselForm.addEventListener('submit', function() {
            const inp = document.getElementById('active-photo-index-input');
            if (inp) inp.value = currentActiveIdx;
        });
    }
    const frameSettingsForm = document.getElementById('frame-settings-form');
    if (frameSettingsForm) {
        frameSettingsForm.addEventListener('submit', function() {
            const inp = document.getElementById('active-photo-index-input-settings');
            if (inp) inp.value = currentActiveIdx;
        });
    }
});

function addCarouselCard() {
    const grid = document.getElementById('carousel-grid');
    const idx  = grid.children.length;
    const card = document.createElement('div');
    card.className = 'bg-teal-800/40 border border-teal-700 rounded-xl p-4 space-y-3 carousel-item-card transition-all';
    card.id = 'carousel-card-item-' + idx;
    card.innerHTML = `
        <input type="hidden" name="carousel_item_source[]" value="admin">
        <input type="hidden" name="carousel_item_file[]" value="">
        <input type="hidden" name="carousel_item_color[]" id="item-color-input-${idx}" value="#ffffff">
        <input type="hidden" name="carousel_item_frame[]" id="item-frame-input-${idx}" value="original">

        <div class="relative group cursor-pointer" onclick="previewCarousel(this)">
            <div class="w-full h-36 bg-teal-800 rounded-lg border border-dashed border-teal-600 flex items-center justify-center text-teal-500 text-xs">Preview Foto</div>
        </div>

        <!-- Info Pengunggah -->
        <div class="flex items-center justify-between text-[10px] text-teal-400">
            <span>Pengunggah:</span>
            <span class="font-bold text-amber-400">Admin</span>
        </div>

        <div>
            <label class="text-[10px] text-teal-400 font-semibold block mb-1">Caption Foto:</label>
            <input type="text" name="captions[]" value="Keluarga" oninput="updateActivePhotoCaption(${idx}, this.value)" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Caption Foto">
        </div>

        <button type="button" onclick="selectPhotoToEdit(${idx})" id="btn-select-card-${idx}" class="w-full py-2 bg-teal-700/60 hover:bg-teal-600 text-teal-200 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all border border-teal-500/50">
            <i class="bi bi-palette-fill"></i> Edit Bingkai & Warna
        </button>

        <div class="flex gap-2">
            <div class="drop-zone-carousel flex-1 border-2 border-dashed border-teal-700 rounded-lg p-3 text-center cursor-pointer hover:border-teal-500 transition-all text-xs" onclick="document.getElementById('carousel-upload-new-${idx}').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleCarouselDrop(event, -1)">
                <i class="bi bi-cloud-arrow-up text-teal-400"></i>
                <p class="text-teal-400 mt-1">Pilih Foto</p>
                <input id="carousel-upload-new-${idx}" type="file" name="carousel_file[]" accept="image/*" class="hidden" onchange="previewCarouselInput(this, -1)">
            </div>
            <button type="button" onclick="this.closest('.carousel-item-card').remove(); const f = document.getElementById('carousel-form'); if (f && typeof f.checkChanges === 'function') f.checkChanges();" class="px-3 py-1.5 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/40 transition-all text-xs">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    grid.appendChild(card);

    // Update dropdown selector
    const sel = document.getElementById('active-photo-selector');
    if (sel) {
        const opt = document.createElement('option');
        opt.value = idx;
        opt.textContent = 'Foto ' + (idx + 1) + ' (Admin): Keluarga';
        sel.appendChild(opt);
    }

    selectPhotoToEdit(idx);

    const f = document.getElementById('carousel-form');
    if (f && typeof f.checkChanges === 'function') f.checkChanges();
}

function handleCarouselDrop(e, idx) {
    e.preventDefault();
    e.stopPropagation();
    const zone = e.target.closest('.drop-zone-carousel');
    zone.classList.remove('border-teal-400', 'bg-teal-800/50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const inputId = idx >= 0 ? 'carousel-upload-' + idx : zone.querySelector('input[type="file"]').id;
        const input = document.getElementById(inputId);
        if (input) {
            input.files = files;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const box = zone.closest('.space-y-3');
                const existingImg = box.querySelector('.relative.group');
                if (existingImg) {
                    existingImg.querySelector('img').src = ev.target.result;
                } else {
                    const placeholder = box.querySelector('div:first-child');
                    if (placeholder) {
                        placeholder.outerHTML =
                            '<div class="relative group cursor-pointer" onclick="previewCarousel(this)">' +
                                '<img src="' + ev.target.result + '" class="w-full h-36 object-cover rounded-lg border border-teal-700">' +
                                '<div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">' +
                                    '<i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>' +
                                '</div>' +
                            '</div>';
                    }
                }
            };
            reader.readAsDataURL(files[0]);
        }
    }
}

function previewCarouselInput(input, idx) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const box = input.closest('.space-y-3');
            const wrapper = box.querySelector('.relative.group');
            if (wrapper) {
                wrapper.querySelector('img').src = e.target.result;
            } else {
                const placeholder = box.querySelector('div:first-child');
                if (placeholder) {
                    placeholder.outerHTML =
                        '<div class="relative group cursor-pointer" onclick="previewCarousel(this)">' +
                            '<img src="' + e.target.result + '" class="w-full h-36 object-cover rounded-lg border border-teal-700">' +
                            '<div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">' +
                                '<i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>' +
                            '</div>' +
                        '</div>';
                }
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

var makamNewFiles = [];

function deleteMakamPhoto(idx) {
    window.location.href = '<?= base_url('admin') ?>?delete_makam_photo=' + idx + '#makam-section';
}

function renderMakamPreviews() {
    var container = document.getElementById('makam-new-previews');
    container.innerHTML = '';
    makamNewFiles.forEach(function(file, idx) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var col = document.createElement('div');
            col.className = 'inline-block mr-2 mb-2 align-top';
            col.style.cssText = 'width:calc(20% - 0.5rem);min-width:100px;';
            col.innerHTML = '<div style="position:relative;border-radius:8px;overflow:hidden;">' +
                '<img src="' + e.target.result + '" style="width:100%;height:80px;object-fit:cover;border-radius:8px;border:1px solid rgba(77,107,103,.3);display:block;">' +
                '<button type="button" onclick="removeMakamNewFile(' + idx + ')" style="position:absolute;top:2px;right:2px;width:20px;height:20px;background:rgba(225,67,67,.85);color:#fff;border:none;border-radius:50%;font-size:14px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>' +
                '</div>';
            container.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
    const form = document.getElementById('makam-form');
    if (form && typeof form.checkChanges === 'function') form.checkChanges();
}

function handleMakamFiles(input) {
    if (input.files && input.files.length > 0) {
        for (var i = 0; i < input.files.length; i++) {
            makamNewFiles.push(input.files[i]);
        }
        input.value = '';
        renderMakamPreviews();
    }
}

function handleMakamDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    var zone = e.target.closest('.drop-zone');
    zone.classList.remove('border-teal-400', 'bg-teal-800/50');
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
        for (var i = 0; i < e.dataTransfer.files.length; i++) {
            makamNewFiles.push(e.dataTransfer.files[i]);
        }
        renderMakamPreviews();
    }
}

function removeMakamNewFile(idx) {
    makamNewFiles.splice(idx, 1);
    renderMakamPreviews();
}

// Before form submit, populate file input with makamNewFiles
document.querySelector('form input[name="save_makam"]').closest('form').addEventListener('submit', function(e) {
    if (makamNewFiles.length > 0) {
        var dt = new DataTransfer();
        makamNewFiles.forEach(function(f) { dt.items.add(f); });
        document.getElementById('makam-photo-new').files = dt.files;
    }
});

// Validate carousel submission (ensure new items have photos)
document.getElementById('carousel-form').addEventListener('submit', function(e) {
    const isDelete = this.querySelector('input[name="delete_carousel"]');
    if (isDelete) return; // Skip validation on delete

    // Remove existing client-side error alert if present
    const existingAlert = document.getElementById('carousel-error-alert');
    if (existingAlert) {
        existingAlert.remove();
    }

    const newInputs = this.querySelectorAll('input[id^="carousel-upload-new-"]');
    let hasEmpty = false;
    newInputs.forEach(input => {
        const zone = input.closest('.drop-zone-carousel');
        if (!input.files || input.files.length === 0) {
            hasEmpty = true;
            if (zone) {
                zone.classList.add('border-red-500');
                zone.classList.remove('border-teal-700');
            }
        } else {
            if (zone) {
                zone.classList.remove('border-red-500');
                zone.classList.add('border-teal-700');
            }
        }
    });

    if (hasEmpty) {
        e.preventDefault();
        
        // Create error alert div
        const alertDiv = document.createElement('div');
        alertDiv.id = 'carousel-error-alert';
        alertDiv.className = 'bg-red-500/20 border border-red-500/40 text-red-200 px-5 py-3 rounded-lg text-sm mb-4';
        alertDiv.innerText = 'Gagal menyimpan: Harap pilih/unggah foto untuk semua item carousel baru yang ditambahkan!';
        
        // Insert it before the form
        this.parentNode.insertBefore(alertDiv, this);
        
        // Scroll to the carousel section
        document.getElementById('carousel-section').scrollIntoView({ behavior: 'smooth' });
    }
});

// Forms Change Tracking Utility
function initFormChangeTracker(formId, saveBtnId) {
    const form = document.getElementById(formId);
    const saveBtn = document.getElementById(saveBtnId);
    if (!form || !saveBtn) return;

    // Capture initial states of all non-hidden inputs
    const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea, select');
    const initialStates = [];
    inputs.forEach(input => {
        if (input.type === 'file') {
            initialStates.push({ element: input, value: '', filesLength: 0 });
        } else {
            initialStates.push({ element: input, value: input.value });
        }
    });

    // Special count variable for Carousel dynamic items
    let initialCount = 0;
    if (formId === 'carousel-form') {
        initialCount = form.querySelectorAll('input[name="captions[]"]').length;
    }

    function checkChanges() {
        let hasChanges = false;

        // 1. Check Carousel list count
        if (formId === 'carousel-form') {
            const currentCount = form.querySelectorAll('input[name="captions[]"]').length;
            if (currentCount !== initialCount) {
                hasChanges = true;
            }
        }

        // 2. Check Makam draft list length
        if (formId === 'makam-form' && typeof makamNewFiles !== 'undefined' && makamNewFiles.length > 0) {
            hasChanges = true;
        }

        // 3. Compare values of captured inputs
        if (!hasChanges) {
            for (let state of initialStates) {
                const el = state.element;
                if (!document.body.contains(el)) continue;

                if (el.type === 'file') {
                    if (el.files && el.files.length > 0) {
                        hasChanges = true;
                        break;
                    }
                } else {
                    if (el.value !== state.value) {
                        hasChanges = true;
                        break;
                    }
                }
            }
        }

        // 4. Verify dynamically added elements
        if (!hasChanges) {
            const currentInputs = form.querySelectorAll('input:not([type="hidden"]), textarea, select');
            if (currentInputs.length !== initialStates.length) {
                hasChanges = true;
            } else {
                for (let input of currentInputs) {
                    const tracked = initialStates.some(state => state.element === input);
                    if (!tracked) {
                        if (input.type === 'file' && input.files && input.files.length > 0) {
                            hasChanges = true;
                            break;
                        } else if (input.type !== 'file' && input.value !== '') {
                            hasChanges = true;
                            break;
                        }
                    }
                }
            }
        }

        // Toggle state of button
        if (hasChanges) {
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            saveBtn.disabled = true;
            saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Bind change/input event listeners
    form.addEventListener('input', checkChanges);
    form.addEventListener('change', checkChanges);

    // Initial check
    checkChanges();

    // Export checkChanges on the form element
    form.checkChanges = checkChanges;
}

document.addEventListener("DOMContentLoaded", function() {
    initFormChangeTracker('banner-form', 'save-banner-btn');
    initFormChangeTracker('carousel-form', 'save-carousel-btn');
    initFormChangeTracker('intro-form', 'save-intro-btn');
    initFormChangeTracker('sambutan-form', 'save-sambutan-btn');
    initFormChangeTracker('makam-form', 'save-makam-btn');
});

function deleteCarousel(index) {
    const form = document.getElementById('carousel-form');
    const saveBtn = form.querySelector('button[name="save_carousel"]');
    if (saveBtn) saveBtn.disabled = true;
    const saveInput = form.querySelector('input[name="save_carousel"]');
    if (saveInput) saveInput.remove();
    const exists = form.querySelector('input[name="delete_carousel"]');
    if (exists) exists.remove();
    const inp = document.createElement('input');
    inp.type = 'hidden';
    inp.name = 'delete_carousel';
    inp.value = '1';
    form.appendChild(inp);
    const idxInp = document.createElement('input');
    idxInp.type = 'hidden';
    idxInp.name = 'delete_index';
    idxInp.value = index;
    form.appendChild(idxInp);
    form.submit();
}


// ── Carousel Frame Color Preview ──
function updateFrameColorPreview(color) {
    var hexEl = document.getElementById('frame_color_hex');
    if (hexEl) hexEl.textContent = color;

    var preview = document.getElementById('live-frame-preview');
    if (preview) {
        preview.style.backgroundColor = color;
        // Auto caption text color based on brightness
        var hex = color.replace('#','');
        if (hex.length === 3) hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
        var r = parseInt(hex.substr(0,2),16);
        var g = parseInt(hex.substr(2,2),16);
        var b = parseInt(hex.substr(4,2),16);
        var brightness = (r*299 + g*587 + b*114) / 1000;
        var caption = document.getElementById('live-frame-caption');
        if (caption) caption.style.color = brightness > 128 ? '#1f1f1f' : '#ffffff';
    }
}

function setFrameColor(hex) {
    var input = document.getElementById('carousel_frame_color');
    if (input) {
        input.value = hex;
        updateFrameColorPreview(hex);
    }
}

// ── Fungsi Pemilihan Bingkai Global Secara Visual ──
function selectGlobalFrame(frameStyle) {
    // Simpan nilai ke input hidden
    const input = document.getElementById('global-frame-input');
    if (input) input.value = frameStyle;

    // Highlight opsi terpilih
    ['original', 'ethnic'].forEach(function(opt) {
        const card = document.getElementById('frame-option-card-' + opt);
        if (card) {
            if (opt === frameStyle) {
                card.classList.remove('border-teal-700/60', 'bg-teal-900/40');
                card.classList.add('border-amber-400', 'bg-amber-400/10');
            } else {
                card.classList.remove('border-amber-400', 'bg-amber-400/10');
                card.classList.add('border-teal-700/60', 'bg-teal-900/40');
            }
        }
    });

    // Live update preview card
    const preview = document.getElementById('live-frame-preview');
    const overlay = document.getElementById('preview-overlay');
    const img     = document.getElementById('preview-card-img');
    const caption = document.getElementById('live-frame-caption');

    if (preview && overlay && img) {
        if (frameStyle === 'ethnic') {
            preview.classList.remove('carousel-card-original');
            preview.classList.add('carousel-card-with-frame');
            preview.style.padding = '0 !important';
            preview.style.backgroundColor = 'transparent';

            overlay.style.backgroundImage = "url('<?= base_url('assets/images/frame_ethnic.png') ?>')";
            overlay.style.display = 'block';

            img.style.padding = '8px 6px 8px 6px';
            img.style.height = '230px';

            if (caption) {
                caption.style.bottom = '12px';
                caption.style.color = '#ffffff';
                caption.style.textShadow = '0px 2px 5px rgba(0,0,0,0.95)';
            }
        } else {
            preview.classList.remove('carousel-card-with-frame');
            preview.classList.add('carousel-card-original');
            preview.style.padding = '10px !important';
            preview.style.backgroundColor = '#ffffff';

            overlay.style.backgroundImage = 'none';
            overlay.style.display = 'none';

            img.style.padding = '0';
            img.style.height = '210px';

            if (caption) {
                caption.style.bottom = '28px';
                caption.style.color = '#4a4a4a';
                caption.style.textShadow = 'none';
            }
        }
    }
}





    </script>
</body>
</html>
