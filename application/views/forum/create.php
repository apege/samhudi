<section class="max-w-3xl mx-auto px-6 py-10">
    <div class="bg-teal-900 rounded-3xl overflow-hidden shadow-lg p-8">

        <a href="<?= base_url('forum') ?>" class="text-teal-300 hover:text-white text-sm font-body inline-flex items-center gap-1 mb-6 transition">
            <i class="ti ti-arrow-left"></i> Kembali ke Forum
        </a>

        <h1 class="font-display font-bold text-white text-2xl mb-6">Mulai Diskusi Baru</h1>

        <?= form_open('forum/create') ?>

            <div class="mb-5">
                <label class="block font-display font-semibold text-teal-200 text-sm mb-2">Judul Topik</label>
                <?= form_input([
                    'name' => 'title',
                    'value' => set_value('title'),
                    'class' => 'w-full bg-teal-800/60 text-white placeholder-teal-300 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-600',
                    'placeholder' => 'Judul diskusi kamu...'
                ]) ?>
                <?= form_error('title', '<p class="text-red-400 text-xs mt-1">', '</p>') ?>
            </div>

            <div class="mb-6">
                <label class="block font-display font-semibold text-teal-200 text-sm mb-2">Isi Diskusi</label>
                <?= form_textarea([
                    'name' => 'content',
                    'rows' => 6,
                    'value' => set_value('content'),
                    'class' => 'w-full bg-teal-800/60 text-white placeholder-teal-300 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-600 resize-none',
                    'placeholder' => 'Tulis apa yang ingin kamu diskusikan...'
                ]) ?>
                <?= form_error('content', '<p class="text-red-400 text-xs mt-1">', '</p>') ?>
            </div>

            <button type="submit"
                    class="bg-white text-teal-900 font-display font-semibold text-sm px-6 py-3 rounded-full shadow-sm hover:bg-gray-100 transition">
                Posting Topik
            </button>

        <?= form_close() ?>

    </div>
</section>