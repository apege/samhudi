<?php
$carousel_items    = json_decode(file_get_contents(FCPATH . 'assets/carousel-config.json'), true);
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
        ?>
        <div class="card carousel-card carousel-card-original"
             data-rot="<?= $rotations[$i % count($rotations)] ?>"
             style="background-color: <?= htmlspecialchars($card_color) ?> !important;">

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
        <button onclick="openCarouselGalleryModal()" class="px-6 py-2.5 rounded-full border border-[var(--border-gold)] text-[var(--accent-gold)] hover:bg-[var(--accent-gold)] hover:text-[var(--bg-body)] font-semibold transition-all duration-300 flex items-center gap-2 shadow-lg backdrop-blur-sm bg-white/5">
            <i class="bi bi-grid-3x3-gap-fill text-lg"></i> Lihat Semua Foto
        </button>
    </div>
</section>

<!-- Modal Gallery -->
<div id="carouselGalleryModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 transition-all duration-300">
    <!-- Backdrop with blur -->
    <div class="absolute inset-0 bg-black/75 backdrop-blur-md transition-opacity duration-300" onclick="closeCarouselGalleryModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-[var(--bg-card)] border border-[var(--border-gold)] w-full max-w-6xl max-h-[85vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden transform scale-95 opacity-0 transition-all duration-300 z-10">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-[var(--border-card)]">
            <div>
                <h3 class="font-display font-bold text-2xl text-[var(--text-heading)]">Galeri Foto Keluarga</h3>
                <p class="text-xs text-[var(--text-muted)] mt-1">Kumpulan dokumentasi foto Keluarga Besar H.M. Samhudi</p>
            </div>
            <button onclick="closeCarouselGalleryModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-[var(--border-card)]/10 text-[var(--text-heading)] hover:bg-[var(--border-gold)] hover:text-white transition-all">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php foreach ($carousel_items as $item): ?>
                <div class="group relative bg-[var(--bg-card)] border border-[var(--border-card)] rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col cursor-pointer" onclick="viewFullscreenImage('<?= base_url('assets/images/' . $item['file']) ?>', '<?= htmlspecialchars($item['caption']) ?>')">
                    <div class="aspect-[4/3] w-full overflow-hidden bg-black/5">
                        <img src="<?= base_url('assets/images/' . $item['file']) ?>" alt="<?= htmlspecialchars($item['caption']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-3 text-center flex-grow flex items-center justify-center bg-[var(--bg-card)]">
                        <p class="text-sm font-medium text-[var(--text-body)] font-display line-clamp-2"><?= htmlspecialchars($item['caption']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Fullscreen Lightbox Modal -->
<div id="carouselLightbox" class="fixed inset-0 z-[10000] hidden flex items-center justify-center bg-black/95 p-4 transition-all duration-300" onclick="closeCarouselLightbox()">
    <button class="absolute top-4 right-4 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-all z-20">
        <i class="bi bi-x-lg text-xl"></i>
    </button>
    <div class="relative max-w-5xl max-h-[90vh] flex flex-col items-center justify-center z-10" onclick="event.stopPropagation()">
        <img id="lightboxImg" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded shadow-2xl">
        <p id="lightboxCaption" class="text-white font-display text-lg text-center mt-4 tracking-wide px-4"></p>
    </div>
</div>

<script>
function openCarouselGalleryModal() {
    const modal = document.getElementById('carouselGalleryModal');
    const content = modal.querySelector('.relative');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    // Force reflow
    modal.offsetHeight;
    
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
    document.body.style.overflow = 'hidden'; // prevent scrolling behind
}

function closeCarouselGalleryModal() {
    const modal = document.getElementById('carouselGalleryModal');
    const content = modal.querySelector('.relative');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }, 300);
}

function viewFullscreenImage(src, caption) {
    const lightbox = document.getElementById('carouselLightbox');
    const img = document.getElementById('lightboxImg');
    const captionEl = document.getElementById('lightboxCaption');
    
    img.src = src;
    captionEl.textContent = caption;
    
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeCarouselLightbox() {
    const lightbox = document.getElementById('carouselLightbox');
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    if (!document.getElementById('carouselGalleryModal').classList.contains('hidden')) {
        // Keep overflow hidden if gallery is still open
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}
</script>