<?php
$carousel_items = json_decode(file_get_contents(FCPATH . 'assets/carousel-config.json'), true) ?: [];

// Gabungkan file carousel milik user
$user_files = glob(FCPATH . 'assets/carousel-user-*.json');
if ($user_files) {
    foreach ($user_files as $ufile) {
        $uitems = json_decode(file_get_contents($ufile), true);
        if (is_array($uitems)) {
            $carousel_items = array_merge($carousel_items, $uitems);
        }
    }
}

$carousel_settings = file_exists(FCPATH . 'assets/carousel-settings.json')
    ? json_decode(file_get_contents(FCPATH . 'assets/carousel-settings.json'), true)
    : [];
$cs_frame       = $carousel_settings['frame']       ?? 'original';
$cs_frame_color = $carousel_settings['frame_color'] ?? '#ffffff';
// Sanitize
$allowed_frames = ['original', 'ethnic', 'gold'];
if (!in_array($cs_frame, $allowed_frames)) $cs_frame = 'original';
if (!preg_match('/^#[0-9a-fA-F]{3,6}$/', $cs_frame_color)) $cs_frame_color = '#ffffff';

$rgb = sscanf($cs_frame_color, '#%02x%02x%02x');
$brightness = $rgb ? (($rgb[0]*299 + $rgb[1]*587 + $rgb[2]*114) / 1000) : 200;
$caption_color = ($brightness > 128) ? '#1f1f1f' : '#ffffff';
?>
<style>
    .carousel-section {
        width: 100% !important;
        max-width: 100vw !important;
        overflow: hidden !important;
        position: relative;
    }
    .carousel-container {
        max-width: 100%;
        overflow: hidden;
    }
</style>

<section class="carousel-section w-full overflow-x-hidden reveal reveal-scale-up">
    <div id="carousel" class="carousel-container">

        <?php $rotations = [-10, 10, -5, 8, -13, 7, -4]; $i = 0; ?>
        <?php foreach ($carousel_items as $item):
            // Warna latar per item
            $card_color = !empty($item['frame_color']) ? $item['frame_color'] : '#ffffff';
            if (!preg_match('/^#[0-9a-fA-F]{3,6}$/', $card_color)) $card_color = '#ffffff';
            
            $rgb = sscanf($card_color, '#%02x%02x%02x');
            $brightness = $rgb ? (($rgb[0]*299 + $rgb[1]*587 + $rgb[2]*114) / 1000) : 200;
            $caption_color = ($brightness > 128) ? '#1f1f1f' : '#ffffff';

            $item_frame = !empty($item['frame_style']) ? $item['frame_style'] : 'original';
            $is_framed = ($item_frame !== 'original');
            
            $frame_url = '';
            if ($item_frame === 'blue_wave') {
                $frame_url = base_url('assets/images/frame_blue_wave.png');
            } else if ($item_frame === 'green_vines') {
                $frame_url = base_url('assets/images/frame_green_vines.png');
            } else if ($item_frame === 'flowers_stitch') {
                $frame_url = base_url('assets/images/frame_flowers_stitch.png');
            } else if ($item_frame === 'yellow_sunflowers') {
                $frame_url = base_url('assets/images/frame_yellow_sunflowers.png');
            } else if ($item_frame === 'green_dots') {
                $frame_url = base_url('assets/images/frame_green_dots.png');
            } else if ($item_frame === 'green_waves') {
                $frame_url = base_url('assets/images/frame_green_waves.png');
            } else if ($item_frame === 'pink_glitter') {
                $frame_url = base_url('assets/images/frame_pink_glitter.png');
            } else if ($item_frame === 'purple_stripes') {
                $frame_url = base_url('assets/images/frame_purple_stripes.png');
            } else if ($item_frame === 'black_dots') {
                $frame_url = base_url('assets/images/frame_black_dots.png');
            } else if ($item_frame === 'orange_spirals') {
                $frame_url = base_url('assets/images/frame_orange_spirals.png');
            } else if ($item_frame === 'green_orange_wave') {
                $frame_url = base_url('assets/images/frame_green_orange_wave.png');
            } else if ($item_frame === 'abstract_wavy') {
                $frame_url = base_url('assets/images/frame_abstract_wavy.png');
            } else if ($item_frame === 'checkered') {
                $frame_url = base_url('assets/images/frame_checkered.png');
            } else if ($item_frame === 'zigzag_colorful') {
                $frame_url = base_url('assets/images/frame_zigzag_colorful.png');
            } else if ($item_frame === 'ethnic_red') {
                $frame_url = base_url('assets/images/frame_ethnic_red.png');
            }
        ?>
        <div class="card carousel-card <?= $is_framed ? 'carousel-card-with-frame' : 'carousel-card-original' ?>"
             data-rot="<?= $rotations[$i % count($rotations)] ?>"
             style="<?= $is_framed ? 'background-color: transparent !important;' : 'background-color: ' . htmlspecialchars($card_color) . ' !important;' ?>">

            <?php if ($is_framed): ?>
                <div class="card-frame-overlay" style="background-image: url('<?= $frame_url ?>');"></div>
            <?php endif; ?>

            <img src="<?= base_url('assets/images/' . $item['file']) ?>" class="carousel-img">
            <div class="carousel-caption" style="color: <?= $caption_color ?>;">
                <?= htmlspecialchars($item['caption']) ?>
            </div>
        </div>
        <?php $i++; ?>
        <?php endforeach; ?>

    </div>

    <!-- Tombol Selengkapnya / Lihat Semua Foto -->
    <div class="flex justify-center mt-12 mb-6">
        <a href="<?= base_url('home/gallery') ?>" class="px-6 py-2.5 rounded-full border border-[var(--border-gold)] text-[var(--accent-gold)] hover:bg-[var(--accent-gold)] hover:text-[var(--bg-body)] font-semibold transition-all duration-300 flex items-center gap-2 shadow-lg backdrop-blur-sm bg-white/5">
            <i class="bi bi-grid-3x3-gap-fill text-lg"></i> Lihat Semua Foto
        </a>
    </div>
</section>