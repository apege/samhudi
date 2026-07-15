<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Banner Profil - Admin Panel</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#15201E',
                            medium: '#2E564F',
                            light: '#E49438',
                            accent: '#377C80'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php $this->load->view('admin/sidebar', ['active_menu' => 'banner_profil']); ?>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-8 py-5 flex items-center justify-between shrink-0 sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-medium/10 flex items-center justify-center text-brand-medium">
                        <i class="bi bi-images text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 font-display">Kelola Banner Profil</h1>
                        <p class="text-sm text-gray-500">Manajemen pilihan banner untuk profil user</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?= base_url('admin/banner_profil_add') ?>" class="bg-brand-medium hover:bg-brand-medium/90 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                        <i class="bi bi-plus-lg"></i> Tambah Banner
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8">
                <!-- Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3">
                        <i class="bi bi-check-circle-fill"></i> <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200 flex items-center gap-3">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if(!empty($banners)): ?>
                        <?php foreach($banners as $banner): ?>
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm group">
                            <div class="h-40 w-full bg-gray-100 relative">
                                <img src="<?= base_url($banner['image_path']) ?>" class="w-full h-full object-cover" alt="Banner">
                            </div>
                            <div class="p-4 flex justify-between items-center bg-white border-t border-gray-100">
                                <div class="text-xs text-gray-400"><i class="bi bi-clock"></i> <?= date('d M Y, H:i', strtotime($banner['created_at'])) ?></div>
                                <a href="<?= base_url('admin/banner_profil_delete/'.$banner['id']) ?>" onclick="return confirm('Hapus banner ini?')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full py-12 text-center text-gray-400 bg-white border border-dashed border-gray-300 rounded-xl">
                            <i class="bi bi-images text-4xl mb-2 block"></i>
                            <p>Belum ada banner profil. Silakan tambah banner baru.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
