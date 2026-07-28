<?php
// Daftar nama bingkai yang cantik untuk ditampilkan ke user
$frame_labels = [
    'original'          => 'Tanpa Bingkai',
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
    'original'          => 'Bingkai Rata Rapi',
    'green_vines'       => 'Motif Daun Indah',
    'blue_wave'         => 'Renda Ombak Biru',
    'flowers_stitch'    => 'Motif Rajutan',
    'yellow_sunflowers' => 'Awan & Bunga Matahari',
    'green_dots'        => 'Motif Titik Bulat',
    'green_waves'       => 'Gelombang Hijau',
    'pink_glitter'      => 'Glitter Berkilau',
    'purple_stripes'    => 'Garis Ungu Estetik',
    'black_dots'        => 'Renda Hitam Unik',
    'orange_spirals'    => 'Spiral Klasik',
    'green_orange_wave' => 'Bingkai Ombak Jingga',
    'abstract_wavy'     => 'Paduan Warna Abstrak',
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
    'purple_stripes' => 'frame_purple_stripes.png',
    'black_dots'        => 'frame_black_dots.png',
    'orange_spirals'    => 'frame_orange_spirals.png',
    'green_orange_wave' => 'frame_green_orange_wave.png',
    'abstract_wavy'     => 'frame_abstract_wavy.png',
    'checkered'         => 'frame_checkered.png',
    'zigzag_colorful'   => 'frame_zigzag_colorful.png',
    'ethnic_red'        => 'frame_ethnic_red.png',
];

// Inisialisasi index foto aktif
$init_active_idx = 0;
$init_item       = $carousel_items[$init_active_idx] ?? [];
$init_color      = !empty($init_item['frame_color']) ? $init_item['frame_color'] : '#ffffff';
$init_frame      = !empty($init_item['frame_style']) ? $init_item['frame_style'] : 'original';
$is_init_framed  = ($init_frame !== 'original');
$init_overlay_bg = isset($frame_images[$init_frame]) ? base_url('assets/images/' . $frame_images[$init_frame]) : '';
?>

<!-- Import CSS Carousel & Admin Helper style -->
<link rel="stylesheet" href="<?= base_url('assets/style/style.css') ?>">

<style>
/* Custom webkit scrollbars */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #0d1e1f; }
::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

.preview-card-container {
    position: relative !important;
    width: 170px !important;
    height: 239px !important;
    background-color: #ffffff;
    border-radius: 4px !important;
    box-shadow: 0 8px 20px rgba(0,0,0,.3) !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
    transform: none !important;
    top: auto !important;
    left: auto !important;
}

.preview-card-container.with-frame {
    padding: 0 !important;
}

.preview-card-container.original {
    padding: 7px !important;
    background-color: #ffffff;
}

.preview-card-container img {
    width: 100% !important;
    object-fit: cover !important;
    display: block !important;
    border-radius: 2px !important;
}

.preview-card-container .card-frame-overlay {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 10 !important;
    background-size: 100% 100% !important;
    background-repeat: no-repeat !important;
    pointer-events: none !important;
}
</style>

<main class="min-h-screen bg-[#0d1e1f] py-10 px-4 sm:px-6 lg:px-8 relative overflow-hidden text-white">
    <!-- Background Ornaments -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-[var(--accent-gold)]/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-[var(--accent-gold)]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-6xl mx-auto relative z-10">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-teal-900 border border-teal-700/60 flex items-center justify-center text-[var(--accent-gold)] shadow-md">
                    <i class="bi bi-images text-2xl"></i>
                </div>
                <div>
                    <h1 class="font-display font-bold text-2xl text-white">Kelola Foto Carousel Saya</h1>
                    <p class="text-xs text-teal-400">Unggah foto keluarga dan atur jenis bingkai pilihanmu di sini.</p>
                </div>
            </div>
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[var(--border-gold)] text-[var(--accent-gold)] hover:bg-[var(--accent-gold)] hover:text-[#0d1e1f] font-semibold text-sm transition-all duration-300 shadow-md bg-white/5 backdrop-blur-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        <!-- Alert messages -->
        <?php if ($this->session->flashdata('success_msg') || $success_msg): ?>
        <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 px-5 py-3 rounded-xl text-sm mb-6 flex items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($this->session->flashdata('success_msg') ?: $success_msg) ?>
        </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_msg') || $error_msg): ?>
        <div class="bg-red-500/20 border border-red-500/40 text-red-200 px-5 py-3 rounded-xl text-sm mb-6 flex items-center gap-2">
            <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($this->session->flashdata('error_msg') ?: $error_msg) ?>
        </div>
        <?php endif; ?>

        <!-- Halaman Utama Mode Khas Admin -->
        <div class="bg-teal-900/40 border border-teal-800 rounded-2xl p-6 shadow-lg space-y-8">
            
            <?php if (!empty($carousel_items)): ?>
            <!-- Form Update Border/Frame Terpusat -->
            <form action="<?= base_url('user_carousel/update') ?>" method="POST" id="carousel-editor-form">
                <input type="hidden" name="index" id="active-photo-index-input" value="<?= $init_active_idx ?>">
                <input type="hidden" name="frame_style" id="active-frame-style-input" value="<?= htmlspecialchars($init_frame) ?>">
                
                <!-- Panel Editor Atas -->
                <div class="bg-teal-800/30 border border-teal-700/80 rounded-2xl p-5 shadow-xl relative">
                    
                    <!-- Header Panel Edit -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-5 border-b border-teal-700/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-teal-600/40 text-teal-300 border border-teal-500/40 flex items-center justify-center">
                                <i class="bi bi-palette-fill"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white uppercase tracking-wide flex items-center gap-2">
                                    PANEL EDIT BINGKAI FOTO <span class="text-amber-400 font-mono text-xs px-2 py-0.5 rounded bg-teal-900/80 border border-amber-400/40" id="editor-active-tag">FOTO 1</span>
                                </h4>
                                <p class="text-[11px] text-teal-300">Pilih salah satu foto kamu di bagian bawah untuk mengedit tampilannya di sini.</p>
                            </div>
                        </div>

                        <!-- Selector Foto dropdown -->
                        <div class="flex items-center gap-2 bg-teal-900/60 p-1.5 rounded-xl border border-teal-700">
                            <label class="text-xs text-teal-300 font-semibold pl-2 flex items-center gap-1 whitespace-nowrap">
                                <i class="bi bi-hand-index-thumb"></i> Pilih Foto:
                            </label>
                            <select id="active-photo-selector" onchange="selectPhotoToEdit(parseInt(this.value))" class="bg-teal-800 border border-teal-600 rounded-lg px-3 py-1 text-white text-xs font-semibold focus:outline-none cursor-pointer">
                                <?php foreach ($carousel_items as $i => $item): ?>
                                <option value="<?= $i ?>" <?= ($i === $init_active_idx) ? 'selected' : '' ?>>Foto <?= $i + 1 ?>: <?= htmlspecialchars(mb_strimwidth($item['caption'], 0, 20, '...')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-6 items-start w-full">
                        <!-- Sisi Kiri: Input Caption & Pilihan Bingkai -->
                        <div class="flex-1 min-w-0 space-y-5 w-full">
                            <!-- Input Caption -->
                            <div class="space-y-2">
                                <label class="text-xs text-teal-300 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-pencil-fill text-teal-400"></i> 1. Tulis Caption / Nama Foto
                                </label>
                                <input type="text" name="caption" id="active-caption-input" 
                                       value="<?= htmlspecialchars($init_item['caption'] ?? '') ?>" 
                                       oninput="updateActivePhotoCaption(this.value)"
                                       placeholder="Tulis caption foto..." 
                                       class="w-full bg-[#0d1e1f] border border-teal-700/80 focus:border-[var(--accent-gold)] rounded-xl px-4 py-3 text-sm text-white outline-none transition-all">
                            </div>

                            <!-- Pilih Warna Latar Foto Terpilih -->
                            <div class="space-y-2 pt-2 border-t border-teal-700/50" id="color-picker-container">
                                <label class="text-xs text-teal-300 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bi bi-palette-fill text-teal-400"></i> 2. Pilih Warna Latar Bingkai (Foto Rata)
                                </label>
                                
                                <div class="flex items-center gap-4 bg-teal-900/40 p-4 rounded-xl border border-teal-700/60">
                                    <input type="color" id="top-color-picker" name="frame_color"
                                           value="<?= htmlspecialchars($init_color) ?>"
                                           class="w-12 h-12 rounded-xl border-2 border-teal-400 cursor-pointer bg-transparent p-0.5 flex-shrink-0"
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
                                                    class="w-5 h-5 rounded-full border border-teal-600 hover:scale-110 hover:border-white transition-all flex-shrink-0"
                                                    style="background:<?= $hex ?>"></button>
                                            <?php endforeach; ?>
                                            <button type="button" onclick="setActivePhotoColor('#ffffff')"
                                                    class="text-[9px] text-teal-300 hover:text-white px-2 py-0.5 rounded border border-teal-600 hover:border-teal-400 transition-all font-semibold">
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilihan Bingkai Opsi Geser -->
                            <div class="space-y-2 pt-2 border-t border-teal-700/50">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs text-teal-300 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="bi bi-aspect-ratio-fill text-amber-400"></i> 3. Pilih Model Bingkai
                                    </label>
                                    <span class="text-[10px] text-teal-400 font-semibold flex items-center gap-1">
                                        Geser Opsi <i class="bi bi-arrow-right-short"></i>
                                    </span>
                                </div>

                                <!-- Horizontal Scroll Pilihan Bingkai -->
                                <div class="flex items-center gap-3 overflow-x-auto pb-3 pt-1 w-full max-w-full">
                                    
                                    <!-- Original -->
                                    <div id="frame-option-card-original" onclick="setActivePhotoFrame('original')"
                                         class="relative flex items-center gap-2.5 p-2.5 rounded-xl border-2 cursor-pointer transition-all min-w-[170px] flex-shrink-0 <?= ($init_frame === 'original') ? 'border-amber-400 bg-amber-400/10' : 'border-teal-700/60 bg-teal-900/40 hover:border-teal-500' ?>">
                                        <div class="w-9 h-11 bg-white border border-gray-300 rounded shadow-sm p-1 flex flex-col justify-between flex-shrink-0">
                                            <div class="bg-teal-700/30 w-full h-6 rounded-sm"></div>
                                            <div class="w-full h-1 bg-gray-300 rounded-full mx-auto"></div>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-white mb-0.5">Tanpa Bingkai</h5>
                                            <p class="text-[10px] text-teal-300">Border Putih Rata</p>
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

                        <!-- Sisi Kanan: Live Preview Tampilan -->
                        <div class="w-full lg:w-56 flex flex-col items-center gap-2 lg:border-l lg:border-teal-700/60 lg:pl-6 flex-shrink-0">
                            <div class="flex items-center gap-1 text-[10px] text-teal-300 font-bold uppercase tracking-wider">
                                <i class="bi bi-eye-fill"></i> PREVIEW FOTO
                            </div>
                            <div class="bg-teal-900/40 rounded-xl p-3 border border-teal-700 flex items-center justify-center w-full min-h-[250px]">
                                <div id="live-frame-preview" class="preview-card-container <?= $is_init_framed ? 'with-frame' : 'original' ?>"
                                     style="position:relative; width:170px; height:239px; background-color:#ffffff; border-radius:4px; border:none !important; box-shadow:0 8px 20px rgba(0,0,0,.3); transition:none; cursor:default; box-sizing:border-box; overflow:hidden;">
                                    
                                    <!-- Overlay Frame PNG Live -->
                                    <div id="preview-overlay" class="card-frame-overlay"
                                         style="<?= $is_init_framed ? "display:block; background-image:url('" . $init_overlay_bg . "');" : "display:none;" ?> position:absolute; top:0; left:0; width:100%; height:100%; z-index:10; background-size:100% 100%; pointer-events:none;"></div>

                                    <img id="preview-card-img" src="<?= base_url('assets/images/' . ($init_item['file'] ?? '')) ?>"
                                         style="width:100% !important; height:<?= $is_init_framed ? '239px' : '225px' ?> !important; padding:<?= $is_init_framed ? '8px 7px 8px 7px' : '0' ?> !important; object-fit:cover !important; display:block; border-radius:2px !important; position:relative; z-index:1;">
                                    
                                    <div class="carousel-caption" id="live-frame-caption"
                                         style="position:absolute !important; bottom:<?= $is_init_framed ? '12px' : '16px' ?> !important; left:10px !important; right:10px !important; margin:0 auto; text-align:center; font-family:'Brittany Signature', cursive; font-size:14px !important; color:#ffffff !important; text-shadow:0px 2px 6px rgba(0,0,0,0.95), 0px 0px 10px rgba(0,0,0,0.85) !important; z-index:15;">
                                        <?= htmlspecialchars($init_item['caption'] ?? 'Keluarga') ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Simpan Perubahan -->
                            <button type="submit" class="w-full bg-[var(--accent-gold)] hover:opacity-90 text-[#0d1e1f] font-bold px-4 py-2.5 rounded-xl transition-all shadow-md text-xs flex items-center justify-center gap-1.5 cursor-pointer mt-1">
                                <i class="bi bi-check-circle-fill"></i> Simpan Bingkai & Caption
                            </button>
                        </div>
                    </div>

                </div>
            </form>
            <?php endif; ?>

            <!-- DAFTAR KARTU FOTO USER -->
            <div class="space-y-4 pt-4 border-t border-teal-800">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-teal-300 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="bi bi-grid-fill"></i> Daftar Foto Carousel Saya (<?= count($carousel_items) ?> / 10)
                    </h4>
                    <span class="text-[11px] text-teal-400">Klik "Edit Foto Ini" untuk mengatur bingkai dan namanya</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="carousel-grid">
                    
                    <!-- KARTU TAMBAH FOTO BARU -->
                    <?php if (count($carousel_items) < 10): ?>
                    <div class="bg-teal-950/40 border-2 border-dashed border-teal-800 rounded-xl p-4 flex flex-col items-center justify-center text-center min-h-[280px] hover:border-[var(--accent-gold)] transition-all group cursor-pointer"
                         onclick="document.getElementById('upload-photo-modal-trigger').click()">
                        <i class="bi bi-cloud-arrow-up text-3xl text-teal-400 group-hover:text-[var(--accent-gold)] transition-colors mb-2"></i>
                        <span class="text-xs font-bold text-white group-hover:text-[var(--accent-gold)] transition-all">Tambah Foto Baru</span>
                        <p class="text-[10px] text-teal-400 mt-1 max-w-[150px]">Upload foto baru untuk dimasukkan ke carousel</p>
                    </div>
                    <?php endif; ?>

                    <!-- LOOP DAFTAR FOTO USER -->
                    <?php foreach ($carousel_items as $i => $item):
                        $item_frame = !empty($item['frame_style']) ? $item['frame_style'] : 'original';
                    ?>
                    <div class="bg-teal-800/40 border border-teal-700 rounded-xl p-4 space-y-3 carousel-item-card transition-all" id="carousel-card-item-<?= $i ?>">
                        <input type="hidden" id="item-frame-input-<?= $i ?>" value="<?= htmlspecialchars($item_frame) ?>">
                        <input type="hidden" id="item-color-input-<?= $i ?>" value="<?= htmlspecialchars($item['frame_color'] ?? '#ffffff') ?>">
                        <input type="hidden" id="item-caption-input-<?= $i ?>" value="<?= htmlspecialchars($item['caption']) ?>">

                        <!-- Preview Gambar -->
                        <div class="relative group rounded-lg overflow-hidden h-36 bg-black/20">
                            <img src="<?= base_url('assets/images/' . $item['file']) ?>" class="w-full h-full object-cover">
                            <?php if ($item_frame !== 'original' && isset($frame_images[$item_frame])): ?>
                            <div class="absolute inset-0" style="background-image:url('<?= base_url('assets/images/' . $frame_images[$item_frame]) ?>'); background-size: 100% 100%; pointer-events: none;"></div>
                            <?php endif; ?>
                        </div>

                        <!-- Label Caption -->
                        <div class="text-center">
                            <p class="text-xs font-bold text-white truncate px-1" id="grid-caption-text-<?= $i ?>"><?= htmlspecialchars($item['caption']) ?></p>
                            <p class="text-[10px] text-teal-400"><?= $frame_labels[$item_frame] ?></p>
                        </div>

                        <!-- Tombol Pilih Edit -->
                        <button type="button" onclick="selectPhotoToEdit(<?= $i ?>)" id="btn-select-card-<?= $i ?>" 
                                class="w-full py-2 bg-teal-700/60 hover:bg-teal-600 text-teal-200 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all border border-teal-500/50">
                            <i class="bi bi-palette-fill"></i> Edit Foto Ini
                        </button>

                        <!-- Tombol Hapus Form -->
                        <form action="<?= base_url('user_carousel/delete') ?>" method="POST" 
                              onsubmit="return confirm('Apakah anda yakin ingin menghapus foto ini dari carousel?')">
                            <input type="hidden" name="index" value="<?= $i ?>">
                            <button type="submit" class="w-full py-1.5 bg-red-500/10 border border-red-500/30 text-red-300 rounded-lg text-xs hover:bg-red-500/30 transition-all">
                                <i class="bi bi-trash"></i> Hapus Foto
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>
    </div>
</main>

<!-- Hidden Upload Trigger and Modal -->
<button id="upload-photo-modal-trigger" class="hidden" onclick="toggleUploadModal(true)"></button>
<div id="upload-photo-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#0d1e1f] border border-teal-800 w-full max-w-md rounded-2xl overflow-hidden p-6 space-y-4 text-white">
        <div class="flex items-center justify-between border-b border-teal-800 pb-3">
            <h3 class="font-display font-bold text-base flex items-center gap-2">
                <i class="bi bi-cloud-arrow-up text-[var(--accent-gold)]"></i> Upload Foto Baru
            </h3>
            <button onclick="toggleUploadModal(false)" class="text-teal-400 hover:text-white text-xl font-bold">&times;</button>
        </div>
        
        <form action="<?= base_url('user_carousel/add') ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- File Input Drag-Drop -->
            <div id="modal-drop-zone" class="border-2 border-dashed border-teal-800 hover:border-[var(--accent-gold)] rounded-xl p-6 text-center cursor-pointer transition-all">
                <img id="modal-preview-img" class="hidden w-full max-h-40 object-cover rounded-lg mb-2">
                <div id="modal-placeholder">
                    <i class="bi bi-cloud-upload text-3xl text-teal-400/60 mb-2 block"></i>
                    <p class="text-xs font-semibold text-white">Pilih file gambar foto</p>
                    <p class="text-[10px] text-teal-400 mt-1">Format: JPG, PNG, WEBP (Maks 5MB)</p>
                </div>
                <input type="file" id="modal-file-input" name="photo" accept="image/*" class="hidden" required>
            </div>

            <!-- Caption -->
            <div class="space-y-1">
                <label class="text-xs font-semibold text-teal-300">Caption / Nama Foto</label>
                <input type="text" name="caption" placeholder="Tulis nama / caption foto..." required
                       class="w-full bg-teal-900/60 border border-teal-800 rounded-lg px-3.5 py-2 text-sm outline-none text-white focus:border-[var(--accent-gold)]">
            </div>

            <!-- Frame Selection -->
            <div class="space-y-1">
                <label class="text-xs font-semibold text-teal-300">Pilih Bingkai Default</label>
                <select name="frame_style" class="w-full bg-teal-900/60 border border-teal-800 rounded-lg px-3.5 py-2 text-sm outline-none text-white focus:border-[var(--accent-gold)]">
                    <?php foreach ($frame_labels as $f_key => $f_val): ?>
                    <option value="<?= $f_key ?>"><?= $f_val ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-[var(--accent-gold)] text-[#0d1e1f] font-bold py-2.5 rounded-xl hover:opacity-90 active:scale-95 transition-all text-xs flex items-center justify-center gap-1.5">
                <i class="bi bi-upload"></i> Upload & Tampilkan
            </button>
        </form>
    </div>
</div>

<script>
let currentActiveIdx = <?= $init_active_idx ?>;

// List frame style mapping ke file PNG untuk live preview JS
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

function selectPhotoToEdit(idx) {
    const cards = document.querySelectorAll('.carousel-item-card');
    if (cards.length === 0) return;
    currentActiveIdx = idx;

    // Sinkron hidden inputs & tag text
    const activeIdxInput = document.getElementById('active-photo-index-input');
    if (activeIdxInput) activeIdxInput.value = idx;
    
    const tag = document.getElementById('editor-active-tag');
    if (tag) tag.textContent = 'FOTO ' + (idx + 1);

    const selector = document.getElementById('active-photo-selector');
    if (selector) selector.value = idx;

    // Highlighting kartu active di grid bawah
    cards.forEach((card, i) => {
        const btn = document.getElementById('btn-select-card-' + i);
        if (i === idx) {
            card.classList.add('ring-2', 'ring-amber-400', 'border-amber-400', 'bg-teal-800/80');
            card.classList.remove('bg-teal-800/40');
            if (btn) {
                btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Sedang Di-edit';
                btn.className = 'w-full py-2 bg-amber-400 text-teal-950 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 transition-all shadow-md';
            }
        } else {
            card.classList.remove('ring-2', 'ring-amber-400', 'border-amber-400', 'bg-teal-800/80');
            card.classList.add('bg-teal-800/40');
            if (btn) {
                btn.innerHTML = '<i class="bi bi-palette-fill"></i> Edit Foto Ini';
                btn.className = 'w-full py-2 bg-teal-700/60 hover:bg-teal-600 text-teal-200 rounded-lg text-xs font-semibold flex items-center justify-center gap-1.5 transition-all border border-teal-500/50';
            }
        }
    });

    // Ambil konfigurasi saat ini dari hidden elements kartu tersebut
    const frameStyle = document.getElementById('item-frame-input-' + idx).value || 'original';
    const captionText = document.getElementById('item-caption-input-' + idx).value || '';

    // Ambil warna bingkai dari hidden input
    const colorHex = document.getElementById('item-color-input-' + idx).value || '#ffffff';

    // Set value caption input di editor atas
    document.getElementById('active-caption-input').value = captionText;
    document.getElementById('active-frame-style-input').value = frameStyle;
    
    // Set warna picker di editor atas
    const colorPicker = document.getElementById('top-color-picker');
    const colorHexLabel = document.getElementById('top-color-hex');
    if (colorPicker) colorPicker.value = colorHex;
    if (colorHexLabel) colorHexLabel.textContent = colorHex;

    // Update highlight tombol horizontal frame
    const allFrameKeys = ['original', 'green_vines', 'blue_wave', 'flowers_stitch', 'yellow_sunflowers', 'green_dots', 'green_waves', 'pink_glitter', 'purple_stripes', 'black_dots', 'orange_spirals', 'green_orange_wave', 'abstract_wavy', 'checkered', 'zigzag_colorful', 'ethnic_red'];
    allFrameKeys.forEach(function(opt) {
        const optionCard = document.getElementById('frame-option-card-' + opt);
        if (optionCard) {
            if (opt === frameStyle) {
                optionCard.classList.remove('border-teal-700/60', 'bg-teal-900/40');
                optionCard.classList.add('border-amber-400', 'bg-amber-400/10');
            } else {
                optionCard.classList.remove('border-amber-400', 'bg-amber-400/10');
                optionCard.classList.add('border-teal-700/60', 'bg-teal-900/40');
            }
        }
    });

    // Hide/show color picker container depending on frameStyle
    const cpContainer = document.getElementById('color-picker-container');
    if (cpContainer) {
        cpContainer.style.display = (frameStyle === 'original') ? 'block' : 'none';
    }

    // Refresh live preview gambar & bingkai
    refreshLivePreview(idx, frameStyle, captionText, colorHex);
}

function refreshLivePreview(idx, frameStyle, captionText, colorHex) {
    const cards = document.querySelectorAll('.carousel-item-card');
    const card  = cards[idx];
    if (!card) return;

    if (!colorHex) {
        colorHex = document.getElementById('item-color-input-' + idx).value || '#ffffff';
    }

    const imgEl          = card.querySelector('img');
    const preview        = document.getElementById('live-frame-preview');
    const overlay        = document.getElementById('preview-overlay');
    const previewImg     = document.getElementById('preview-card-img');
    const previewCaption = document.getElementById('live-frame-caption');

    if (previewImg && imgEl)         previewImg.src = imgEl.src;
    if (previewCaption)              previewCaption.textContent = captionText || 'Keluarga';

    if (preview && overlay && previewImg) {
        const isFramed = (frameStyle !== 'original');
        
        if (isFramed && frameImagesMap[frameStyle]) {
            preview.className = 'preview-card-container with-frame';
            preview.style.padding = '0';
            preview.style.backgroundColor = 'transparent';

            overlay.style.backgroundImage = "url('" + frameImagesMap[frameStyle] + "')";
            overlay.style.display = 'block';

            previewImg.style.padding = '8px 7px 8px 7px';
            previewImg.style.height = '239px';
            
            if (previewCaption) {
                previewCaption.style.bottom = '12px';
                previewCaption.style.color = '#ffffff';
                previewCaption.style.textShadow = '0px 2px 6px rgba(0,0,0,0.95), 0px 0px 10px rgba(0,0,0,0.85)';
            }
        } else {
            preview.className = 'preview-card-container original';
            preview.style.padding = '7px';
            preview.style.backgroundColor = colorHex;

            overlay.style.backgroundImage = 'none';
            overlay.style.display = 'none';

            previewImg.style.padding = '0';
            previewImg.style.height = '225px';

            if (previewCaption) {
                previewCaption.style.bottom = '16px';
                previewCaption.style.color = '#ffffff';
                previewCaption.style.textShadow = '0px 2px 6px rgba(0,0,0,0.95), 0px 0px 10px rgba(0,0,0,0.85)';
            }
        }
    }
}

function setActivePhotoColor(colorHex) {
    document.getElementById('item-color-input-' + currentActiveIdx).value = colorHex;
    document.getElementById('top-color-hex').textContent = colorHex;
    document.getElementById('top-color-picker').value = colorHex;
    
    const frameStyle = document.getElementById('active-frame-style-input').value;
    const captionText = document.getElementById('active-caption-input').value;
    refreshLivePreview(currentActiveIdx, frameStyle, captionText, colorHex);
}

function setActivePhotoFrame(frameStyle) {
    document.getElementById('active-frame-style-input').value = frameStyle;
    document.getElementById('item-frame-input-' + currentActiveIdx).value = frameStyle;
    selectPhotoToEdit(currentActiveIdx);
}

function updateActivePhotoCaption(captionText) {
    document.getElementById('item-caption-input-' + currentActiveIdx).value = captionText;
    
    // Update label di grid bawah
    const gridLabel = document.getElementById('grid-caption-text-' + currentActiveIdx);
    if (gridLabel) gridLabel.textContent = captionText || 'Keluarga';

    // Update label di selector dropdown atas
    const selector = document.getElementById('active-photo-selector');
    if (selector && selector.options[currentActiveIdx]) {
        let shortText = captionText.length > 20 ? captionText.substring(0, 20) + '...' : captionText;
        selector.options[currentActiveIdx].text = 'Foto ' + (currentActiveIdx + 1) + ': ' + (shortText || 'Keluarga');
    }

    const previewCaption = document.getElementById('live-frame-caption');
    if (previewCaption) previewCaption.textContent = captionText || 'Keluarga';
}

// Modal handling
function toggleUploadModal(show) {
    const modal = document.getElementById('upload-photo-modal');
    if (show) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    } else {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Drag & drop upload in modal
const modalDropZone = document.getElementById('modal-drop-zone');
const modalFileInput = document.getElementById('modal-file-input');
const modalPreviewImg = document.getElementById('modal-preview-img');
const modalPlaceholder = document.getElementById('modal-placeholder');

if (modalDropZone && modalFileInput) {
    modalDropZone.addEventListener('click', () => modalFileInput.click());
    modalDropZone.addEventListener('dragover', e => {
        e.preventDefault();
        modalDropZone.classList.add('border-amber-400');
    });
    modalDropZone.addEventListener('dragleave', () => {
        modalDropZone.classList.remove('border-amber-400');
    });
    modalDropZone.addEventListener('drop', e => {
        e.preventDefault();
        modalFileInput.files = e.dataTransfer.files;
        showModalPreview(e.dataTransfer.files[0]);
    });
    modalFileInput.addEventListener('change', () => {
        if (modalFileInput.files[0]) showModalPreview(modalFileInput.files[0]);
    });
}

function showModalPreview(file) {
    const reader = new FileReader();
    reader.onload = e => {
        modalPreviewImg.src = e.target.result;
        modalPreviewImg.classList.remove('hidden');
        modalPlaceholder.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

document.addEventListener('DOMContentLoaded', function() {
    selectPhotoToEdit(0);
});
</script>
