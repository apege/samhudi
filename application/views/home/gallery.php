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
?>

<div class="min-h-screen bg-[var(--bg-body)] py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Ornamen Latar Belakang -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-[var(--accent-gold)]/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-[var(--accent-gold)]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Header Halaman -->
        <div class="text-center mb-12 reveal reveal-scale-up">
            <h1 class="font-display font-bold text-4xl sm:text-5xl text-[var(--text-heading)] mb-4 tracking-wide">
                Galeri Foto Keluarga
            </h1>
            <div class="w-24 h-1 bg-[var(--accent-gold)] mx-auto rounded-full mb-4"></div>
            <p class="text-sm sm:text-base text-[var(--text-muted)] max-w-2xl mx-auto">
                Kumpulan dokumentasi foto dan memori berharga dari Keluarga Besar H.M. Samhudi.
            </p>
        </div>

        <!-- Tombol Kembali Ke Beranda -->
        <div class="mb-8 flex justify-start reveal reveal-fade-in">
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[var(--border-gold)] text-[var(--accent-gold)] hover:bg-[var(--accent-gold)] hover:text-[var(--bg-body)] font-semibold text-sm transition-all duration-300 shadow-md backdrop-blur-sm bg-white/5">
                <i class="bi bi-arrow-left-short text-xl"></i> Kembali ke Beranda
            </a>
        </div>

        <!-- Grid Galeri Foto -->
        <?php if (!empty($carousel_items)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 reveal reveal-fade-in">
                <?php foreach ($carousel_items as $item): ?>
                    <div class="group relative bg-[var(--bg-card)] border border-[var(--border-card)] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:border-[var(--accent-gold)]/50 transition-all duration-500 flex flex-col cursor-pointer transform hover:-translate-y-1.5"
                         onclick="viewFullscreenImage('<?= base_url('assets/images/' . $item['file']) ?>', '<?= htmlspecialchars($item['caption']) ?>')">
                        
                        <!-- Kontainer Foto -->
                        <div class="aspect-[4/3] w-full overflow-hidden bg-black/40 relative">
                            <img src="<?= base_url('assets/images/' . $item['file']) ?>" 
                                 alt="<?= htmlspecialchars($item['caption']) ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <!-- Overlay Hover -->
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <span class="bg-black/60 text-[var(--accent-gold)] p-3 rounded-full border border-[var(--accent-gold)] shadow-lg backdrop-blur-sm transform scale-90 group-hover:scale-100 transition-transform duration-300">
                                    <i class="bi bi-zoom-in text-xl"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Caption Card -->
                        <div class="p-4 text-center flex-grow flex items-center justify-center bg-[var(--bg-card)] border-t border-[var(--border-card)]/50">
                            <p class="text-sm font-semibold text-[var(--text-body)] font-display line-clamp-2 leading-relaxed">
                                <?= htmlspecialchars($item['caption']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Jika Kosong -->
            <div class="text-center py-20 bg-[var(--bg-card)] border border-[var(--border-card)] rounded-3xl reveal reveal-fade-in">
                <i class="bi bi-image text-5xl text-[var(--accent-gold)]/40 mb-4 block"></i>
                <h3 class="text-lg font-bold text-[var(--text-heading)]">Belum Ada Foto</h3>
                <p class="text-xs text-[var(--text-muted)] mt-1">Foto galeri carousel belum diunggah.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Fullscreen Lightbox Modal -->
<div id="carouselLightbox" class="fixed inset-0 z-[10000] hidden flex items-center justify-center bg-black/95 p-4 transition-all duration-300" onclick="closeCarouselLightbox()">
    <button class="absolute top-6 right-6 w-12 h-12 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-all z-20 shadow-lg border border-white/5">
        <i class="bi bi-x-lg text-xl"></i>
    </button>
    <div class="relative max-w-5xl max-h-[90vh] flex flex-col items-center justify-center z-10" onclick="event.stopPropagation()">
        <img id="lightboxImg" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-xl shadow-2xl border border-white/5">
        <p id="lightboxCaption" class="text-white font-display text-lg text-center mt-4 tracking-wide px-6 py-2 bg-black/40 rounded-full backdrop-blur-sm border border-white/5"></p>
    </div>
</div>

<script>
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
    document.body.style.overflow = '';
}
</script>
