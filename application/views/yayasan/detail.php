<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$page_type = $page_type ?? 'individu';
$is_rundayan = ($page_type === 'rundayan');
$theme_primary = $is_rundayan ? 'cyan' : 'amber';
$theme_dark_text = $is_rundayan ? 'text-slate-950' : 'text-teal-950';
$detail_base_url = $is_rundayan ? 'rundayan/detail/' : 'anggota/detail/';
$back_url = $is_rundayan ? base_url('rundayan') : base_url('anggota');
$candidate_detail_url = base_url($detail_base_url . $candidate['id']);
?>

<main class="min-h-screen bg-gradient-to-b from-[#274d4f] via-[#1a3638] to-[#0f2122] text-white pt-32 sm:pt-36 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Back Link -->
        <a href="<?= $back_url ?>" class="inline-flex items-center gap-2 text-<?= $theme_primary ?>-400 hover:text-<?= $theme_primary ?>-300 font-semibold mb-8 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar Calon
        </a>

        <!-- Main Card -->
        <div class="bg-gradient-to-br from-<?= $is_rundayan ? '[#112d30] to-[#0c1f21]' : '[#1b3638] to-[#122829]' ?> border border-<?= $is_rundayan ? 'cyan-500/20' : 'white/10' ?> rounded-3xl p-5 sm:p-8 shadow-2xl relative overflow-hidden text-center animate-fade-in">
            <!-- Decorative circle -->
            <div class="absolute -right-24 -top-24 w-48 h-48 bg-<?= $theme_primary ?>-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-24 -bottom-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl"></div>

            <span class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-<?= $theme_primary ?>-500/20 text-<?= $theme_primary ?>-400 border border-<?= $theme_primary ?>-500/30">Detail Kandidat</span>
            
            <h1 class="mt-6 text-3xl sm:text-4xl font-display font-bold text-white tracking-tight leading-tight">
                <?= htmlspecialchars($candidate['candidate_name']) ?>
            </h1>

            <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-8 mt-6 mb-8 text-white/80">
                <div class="bg-[#112426] border border-white/5 rounded-2xl px-6 py-4 flex-1">
                    <span class="text-xs text-white/50 block mb-1">Pencalon / Nominator</span>
                    <strong class="text-base text-<?= $theme_primary ?>-300"><?= htmlspecialchars($candidate['nominator_name']) ?></strong>
                </div>
                <div class="bg-[#112426] border border-white/5 rounded-2xl px-6 py-4 flex-1">
                    <span class="text-xs text-white/50 block mb-1">Undayan / Buyut</span>
                    <strong class="text-base text-<?= $theme_primary ?>-300"><?= htmlspecialchars($candidate['ancestor_name']) ?></strong>
                </div>
            </div>

            <!-- Silsilah Chain Diagram -->
            <div class="mt-8 mb-8 border border-<?= $theme_primary ?>-500/20 bg-<?= $theme_primary ?>-500/5 rounded-3xl p-6 text-left">
                <h3 class="text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider mb-6 text-center tracking-widest">Bagan Alur Pencalonan</h3>
                <div class="flex flex-col items-center">
                    <?php 
                    // Render parent chain (if any)
                    foreach ($parent_chain as $p): ?>
                        <div class="flex flex-col items-center w-full">
                            <?php if (isset($p['virtual']) && $p['virtual']): ?>
                                <div class="px-5 py-2.5 bg-white/5 border border-white/5 rounded-2xl text-center max-w-xs w-full">
                                    <span class="text-[9px] uppercase font-bold text-white/40 block">Anggota Keluarga Samhudi</span>
                                    <strong class="text-white/80 text-sm"><?= htmlspecialchars($p['candidate_name']) ?></strong>
                                </div>
                            <?php else: ?>
                                <a href="<?= base_url($detail_base_url.$p['id']) ?>" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-center max-w-xs w-full transition-all hover:scale-105 block no-underline text-white">
                                    <span class="text-[9px] uppercase font-bold text-<?= $theme_primary ?>-400/80 block">Mencalonkan <?= htmlspecialchars($p['candidate_name']) ?></span>
                                    <strong class="text-white text-sm"><?= htmlspecialchars($p['candidate_name']) ?></strong>
                                </a>
                            <?php endif; ?>
                            <div class="h-8 w-0.5 bg-<?= $theme_primary ?>-500/30 my-1 flex items-center justify-center">
                                <i class="bi bi-chevron-down text-<?= $theme_primary ?>-400 text-xs mt-1"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Current Candidate (Highlight) -->
                    <div class="flex flex-col items-center w-full">
                        <div class="px-6 py-4 bg-<?= $theme_primary ?>-500/15 border-2 border-<?= $theme_primary ?>-500 rounded-2xl text-center shadow-lg shadow-<?= $theme_primary ?>-500/10 max-w-xs w-full scale-105">
                            <span class="text-[9px] uppercase font-extrabold text-<?= $theme_primary ?>-400 block tracking-wider">Kandidat Detail</span>
                            <strong class="text-white text-base font-bold"><?= htmlspecialchars($candidate['candidate_name']) ?></strong>
                            <span class="text-[10px] text-<?= $theme_primary ?>-300/85 block mt-0.5">Keturunan: <?= htmlspecialchars($candidate['ancestor_name']) ?></span>
                        </div>
                    </div>

                    <!-- Nominated by this Candidate (Children) -->
                    <?php if (!empty($nominated_by_this)): ?>
                        <div class="h-8 w-0.5 bg-<?= $theme_primary ?>-500/30 my-1 flex items-center justify-center">
                            <i class="bi bi-chevron-down text-<?= $theme_primary ?>-400 text-xs mt-1"></i>
                        </div>
                        <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-4 w-full mt-1">
                            <?php foreach ($nominated_by_this as $child): ?>
                                <a href="<?= base_url($detail_base_url.$child['id']) ?>" class="px-5 py-2.5 bg-[#112426] hover:bg-[#1a3638] border border-white/10 rounded-2xl text-center transition-all block no-underline text-white hover:scale-105">
                                    <span class="text-[9px] uppercase font-bold text-<?= $theme_primary ?>-400 block">Dicalonkan Oleh <?= htmlspecialchars($candidate['candidate_name']) ?></span>
                                    <strong class="text-white text-sm"><?= htmlspecialchars($child['candidate_name']) ?></strong>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($candidate['description'])): ?>
                <div class="text-left bg-white/5 border border-white/5 rounded-2xl p-6 mb-8">
                    <h3 class="text-sm font-semibold text-white/50 uppercase tracking-wider mb-2">Visi / Keterangan</h3>
                    <p class="text-white/80 leading-relaxed"><?= nl2br(htmlspecialchars($candidate['description'])) ?></p>
                </div>
            <?php endif; ?>

            <!-- QR Section -->
            <div class="border-t border-white/10 pt-8 flex flex-col items-center">
                <h3 class="text-lg font-bold text-<?= $theme_primary ?>-300 mb-4">Scan QR Code</h3>
                
                <div class="bg-white p-3.5 rounded-2xl inline-block shadow-xl mb-6">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($back_url) ?>" 
                          alt="QR Code" class="w-48 h-48">
                </div>

                <div class="w-full max-w-md relative mb-8">
                    <input type="text" id="shareUrl" readonly value="<?= $back_url ?>" 
                           class="w-full pl-4 pr-16 py-3 bg-[#112426] border border-white/10 rounded-xl text-white text-xs text-center select-all">
                    <button onclick="copyLink()" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 <?= $theme_dark_text ?> font-bold rounded-lg text-xs transition-all">
                        Copy
                    </button>
                </div>
                <p id="copyFeedback" class="text-emerald-400 text-xs -mt-6 mb-6 opacity-0 transition-opacity">Link berhasil disalin!</p>

                 <!-- Support Count Card -->
                 <?php if ($is_authorized): ?>
                     <div class="bg-[#112426]/50 border border-white/5 rounded-3xl p-5 w-full max-w-xs flex items-center justify-between gap-4 mb-4">
                         <div class="text-left">
                             <span class="text-xs text-white/50 block">Jumlah Dukungan</span>
                             <div class="flex items-baseline gap-1 mt-0.5">
                                 <span class="text-3xl font-display font-extrabold text-emerald-400"><?= $candidate['votes_count'] ?></span>
                                 <span class="text-xs text-emerald-400/70">suara / usulan</span>
                             </div>
                         </div>
                         <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                             <i class="bi bi-people-fill text-lg"></i>
                         </div>
                     </div>
                 <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    function copyTextToClipboard(text) {
        if (!navigator.clipboard) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('Fallback copy failed', err);
            }
            document.body.removeChild(textArea);
            return Promise.resolve();
        }
        return navigator.clipboard.writeText(text);
    }

    function copyLink() {
        const copyText = document.getElementById('shareUrl');
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        
        copyTextToClipboard(copyText.value).then(() => {
            const feedback = document.getElementById('copyFeedback');
            feedback.classList.remove('opacity-0');
            feedback.classList.add('opacity-100');
            setTimeout(() => {
                feedback.classList.add('opacity-0');
                feedback.classList.remove('opacity-100');
            }, 2000);
        });
    }
</script>
