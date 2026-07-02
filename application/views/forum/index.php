<?php
/**
 * @var array $forums
 */
?>
<div class="min-h-screen" style="background-color: #F5EBE2;">
<section class="max-w-6xl mx-auto px-6 py-10">
    <div class="bg-teal-900 rounded-3xl overflow-hidden shadow-lg">
        <div class="flex flex-col md:flex-row min-h-[500px]">

            <!-- PANEL KIRI -->
            <div class="w-full md:w-1/3 p-8 border-r border-teal-700/50">
                <a href="<?= base_url('forum/create') ?>"
                   class="block text-center bg-white text-teal-900 font-display font-semibold text-sm px-5 py-3 rounded-full shadow-sm hover:bg-gray-100 transition mb-8">
                    Mulai Diskusi
                </a>

                <h3 class="font-display font-semibold text-white text-sm mb-3">Deskripsi</h3>
                <p class="font-body text-teal-200/80 text-sm leading-relaxed">
                    Website ini dibuat sebagai sarana silaturahmi
                    antar keluarga besar H.M. Samhudi. Melalui forum
                    diskusi ini, seluruh anggota keluarga dapat
                    berbagi kabar, informasi, dan bertukar pikiran
                    seputar kegiatan keluarga besar.
                </p>
            </div>

            <!-- PANEL KANAN -->
            <div class="w-full md:w-2/3 flex flex-col">

                <div class="flex-1 overflow-y-auto p-6 space-y-5">
                    <?php if (empty($forums)): ?>
                        <p class="text-teal-200/70 text-sm text-center py-10">
                            Belum ada topik diskusi. Jadilah yang pertama!
                        </p>
                    <?php else: ?>
                        <?php foreach ($forums as $forum): ?>
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-full bg-teal-600 flex-shrink-0 flex items-center justify-center text-white font-display font-semibold text-sm">
                                    <?= strtoupper(substr($forum->author_name ?? 'A', 0, 1)) ?>
                                </div>

                                <div class="flex-1">
                                    <p class="font-display font-semibold text-white text-sm">
                                        <?= htmlspecialchars($forum->author_name ?? 'Anonim') ?>
                                    </p>
                                    <p class="font-display font-semibold text-white text-base mt-0.5">
                                        <?= htmlspecialchars($forum->title) ?>
                                    </p>
                                    <a href="<?= base_url('forum/view/' . $forum->id) ?>"
                                       class="font-body text-teal-300 text-sm hover:text-white transition">
                                        Balas
                                    </a>
                                </div>

                                <div class="flex items-center gap-3 text-teal-300 flex-shrink-0">
                                    <button class="hover:text-white transition" type="button">
                                        <i class="ti ti-thumb-up text-lg"></i>
                                    </button>
                                    <button class="hover:text-white transition" type="button">
                                        <i class="ti ti-thumb-down text-lg"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- KOLOM CHAT BAWAH -->
                <div class="p-5 border-t border-teal-700/50">
                    <div class="flex items-center gap-3 bg-teal-800/60 rounded-full px-5 py-3">
                        <input type="text" readonly placeholder="Ketik..."
                               onclick="window.location.href='<?= base_url('forum/create') ?>'"
                               class="flex-1 bg-transparent text-white placeholder-teal-300 text-sm focus:outline-none cursor-pointer">
                        <a href="<?= base_url('forum/create') ?>"
                           class="w-9 h-9 rounded-full bg-teal-600 flex items-center justify-center text-white hover:bg-teal-500 transition flex-shrink-0">
                            <i class="ti ti-send text-base"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
</div>