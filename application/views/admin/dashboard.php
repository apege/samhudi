<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Keluarga H.M Samhudi</title>
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
        <div class="p-8 space-y-8">

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
                            <?php else: ?>
                                <tr>
                                    <td class="py-4 text-sm text-white/90 font-medium">Membuat Postingan Forum</td>
                                    <td class="py-4 text-sm text-white/80">Auli</td>
                                    <td class="py-4 text-sm text-white/60">1 Juni 2026</td>
                                    <td class="py-4 text-sm">
                                        <span class="text-white/80">Menunggu</span>
                                    </td>
                                    <td class="py-4 text-sm">
                                        <a href="#" class="font-bold text-white hover:underline transition-all">Detail</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 text-sm text-white/90 font-medium">Menambahkan Data Family</td>
                                    <td class="py-4 text-sm text-white/80">Alif</td>
                                    <td class="py-4 text-sm text-white/60">15 Juni 2026</td>
                                    <td class="py-4 text-sm">
                                        <span class="text-white/80">Berhasil</span>
                                    </td>
                                    <td class="py-4 text-sm">
                                        <a href="#" class="font-bold text-white hover:underline transition-all">Detail</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4 text-sm text-white/90 font-medium">Membuat Postingan Forum</td>
                                    <td class="py-4 text-sm text-white/80">Richa</td>
                                    <td class="py-4 text-sm text-white/60">28 Mei 2026</td>
                                    <td class="py-4 text-sm">
                                        <span class="text-white/80">Berhasil</span>
                                    </td>
                                    <td class="py-4 text-sm">
                                        <a href="#" class="font-bold text-white hover:underline transition-all">Detail</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

</body>
</html>
