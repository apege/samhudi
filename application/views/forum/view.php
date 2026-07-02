<?php
/**
 * @var object $forum
 * @var array $comments
 */
?>
<section class="max-w-4xl mx-auto px-6 py-10">
    <div class="bg-teal-900 rounded-3xl overflow-hidden shadow-lg">

        <div class="p-8 border-b border-teal-700/50">
            <a href="<?= base_url('forum') ?>" class="text-teal-300 hover:text-white text-sm font-body inline-flex items-center gap-1 mb-6 transition">
                <i class="ti ti-arrow-left"></i> Kembali ke Forum
            </a>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-teal-600 flex-shrink-0 flex items-center justify-center text-white font-display font-semibold text-base">
                    <?= strtoupper(substr($forum->author_name ?? 'A', 0, 1)) ?>
                </div>
                <div>
                    <p class="font-display font-semibold text-white text-sm">
                        <?= htmlspecialchars($forum->author_name ?? 'Anonim') ?>
                        <span class="text-teal-300 font-body font-normal text-xs ml-2"><?= $forum->created_at ?></span>
                    </p>
                    <h1 class="font-display font-bold text-white text-2xl mt-1 mb-3">
                        <?= htmlspecialchars($forum->title) ?>
                    </h1>
                    <p class="font-body text-teal-100 text-sm leading-relaxed">
                        <?= nl2br(htmlspecialchars($forum->content)) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-5 max-h-[400px] overflow-y-auto">
            <h3 class="font-display font-semibold text-white text-sm mb-2">
                Komentar (<?= count($comments) ?>)
            </h3>

            <?php if (empty($comments)): ?>
                <p class="text-teal-200/70 text-sm text-center py-6">Belum ada komentar.</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-full bg-teal-600 flex-shrink-0 flex items-center justify-center text-white font-display font-semibold text-sm">
                            <?= strtoupper(substr($comment->author_name ?? 'A', 0, 1)) ?>
                        </div>

                        <div class="flex-1">
                            <p class="font-display font-semibold text-white text-sm">
                                <?= htmlspecialchars($comment->author_name ?? 'Anonim') ?>
                            </p>
                            <p class="font-body text-teal-100 text-sm mt-0.5">
                                <?= nl2br(htmlspecialchars($comment->comment)) ?>
                            </p>
                            <button type="button"
                                    onclick="toggleReply(<?= $comment->id ?>)"
                                    class="font-body text-teal-300 text-sm hover:text-white transition mt-1">
                                Balas
                            </button>

                            <div id="reply-form-<?= $comment->id ?>" class="hidden mt-3">
                                <?= form_open('forum/comment/' . $forum->id) ?>
                                    <input type="hidden" name="parent_id" value="<?= $comment->id ?>">
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="comment" placeholder="Tulis balasan..."
                                               class="flex-1 bg-teal-800/60 text-white placeholder-teal-300 text-sm rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-600">
                                        <button type="submit"
                                                class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white hover:bg-teal-500 transition flex-shrink-0">
                                            <i class="ti ti-send text-sm"></i>
                                        </button>
                                    </div>
                                <?= form_close() ?>
                            </div>

                            <?php if (!empty($comment->replies)): ?>
                                <div class="mt-4 space-y-4 pl-4 border-l border-teal-700/50">
                                    <?php foreach ($comment->replies as $reply): ?>
                                        <div class="flex items-start gap-3">
                                            <div class="w-9 h-9 rounded-full bg-teal-700 flex-shrink-0 flex items-center justify-center text-white font-display font-semibold text-xs">
                                                <?= strtoupper(substr($reply->author_name ?? 'A', 0, 1)) ?>
                                            </div>
                                            <div>
                                                <p class="font-display font-semibold text-white text-xs">
                                                    <?= htmlspecialchars($reply->author_name ?? 'Anonim') ?>
                                                </p>
                                                <p class="font-body text-teal-100 text-sm mt-0.5">
                                                    <?= nl2br(htmlspecialchars($reply->comment)) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- KOLOM KETIK KOMENTAR BARU -->
        <?= form_open('forum/comment/' . $forum->id) ?>
            <div class="p-5 border-t border-teal-700/50">
                <?= form_error('comment', '<p class="text-red-400 text-xs mb-2">', '</p>') ?>
                <div class="flex items-center gap-3 bg-teal-800/60 rounded-full px-5 py-3">
                    <input type="text" name="comment" placeholder="Ketik..."
                           class="flex-1 bg-transparent text-white placeholder-teal-300 text-sm focus:outline-none">
                    <button type="submit"
                            class="w-9 h-9 rounded-full bg-teal-600 flex items-center justify-center text-white hover:bg-teal-500 transition flex-shrink-0">
                        <i class="ti ti-send text-base"></i>
                    </button>
                </div>
            </div>
        <?= form_close() ?>

    </div>
</section>

<script>
    function toggleReply(id) {
        var form = document.getElementById('reply-form-' + id);
        form.classList.toggle('hidden');
    }
</script>