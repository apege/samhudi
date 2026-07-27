<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Log | Admin Keluarga H.M Samhudi</title>
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
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

        <!-- Body Content -->
        <div class="p-4 md:p-8 space-y-6">

            <!-- Title -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight">History Log</h1>
                    <p class="text-xs md:text-sm text-teal-400 mt-1">Catatan riwayat aktivitas para admin dan pengguna.</p>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-teal-900/60 border border-teal-800 rounded-2xl shadow-lg overflow-hidden flex flex-col">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-teal-950 text-teal-400 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold border-b border-teal-800 whitespace-nowrap">Waktu</th>
                                <th class="p-4 font-bold border-b border-teal-800 whitespace-nowrap">Pengguna</th>
                                <th class="p-4 font-bold border-b border-teal-800 whitespace-nowrap">Aktivitas</th>
                                <th class="p-4 font-bold border-b border-teal-800 whitespace-nowrap">IP Address</th>
                                <th class="p-4 font-bold border-b border-teal-800 whitespace-nowrap hidden md:table-cell">Browser & OS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-teal-800">
                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): 
                                    $role_class = 'bg-teal-800 text-teal-300';
                                    $role_text  = strtoupper($log->role ?? 'SISTEM');
                                    if (in_array(strtolower($log->role ?? ''), ['admin', 'super_admin', 'superadmin'])) {
                                        $role_class = 'bg-gold-500/20 text-gold-400 border border-gold-500/30';
                                    } elseif (strtolower($log->role ?? '') === 'member') {
                                        $role_class = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                                    } elseif (strtolower($log->role ?? '') === 'guest') {
                                        $role_class = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';
                                    }

                                    $action_text = $log->action;
                                    $action_class = 'text-teal-100';
                                    $icon_class = 'bi-activity text-teal-400';
                                    if (stripos($action_text, 'gagal') !== false || stripos($action_text, 'salah') !== false || stripos($action_text, 'menolak') !== false || stripos($action_text, 'error') !== false) {
                                        $action_class = 'text-rose-400 font-medium';
                                        $icon_class = 'bi-exclamation-triangle-fill text-rose-500';
                                    } elseif (stripos($action_text, 'berhasil') !== false || stripos($action_text, 'menyetujui') !== false || stripos($action_text, 'login berhasil') !== false || stripos($action_text, 'menambahkan') !== false) {
                                        $action_class = 'text-emerald-300 font-medium';
                                        $icon_class = 'bi-check-circle-fill text-emerald-400';
                                    } elseif (stripos($action_text, 'menghapus') !== false || stripos($action_text, 'hapus') !== false || stripos($action_text, 'logout') !== false) {
                                        $action_class = 'text-amber-400 font-medium';
                                        $icon_class = 'bi-info-circle-fill text-amber-400';
                                    }
                                ?>
                                    <tr class="hover:bg-teal-800/30 transition-colors">
                                        <td class="p-4 text-sm text-teal-100 whitespace-nowrap">
                                            <?= date('d M Y, H:i', strtotime($log->created_at)) ?>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-semibold text-white"><?= htmlspecialchars($log->nama ?? 'Sistem') ?></span>
                                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] rounded-md font-bold uppercase w-fit <?= $role_class ?>"><?= htmlspecialchars($role_text) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-sm min-w-[250px] <?= $action_class ?>">
                                            <div class="flex items-center gap-2">
                                                <i class="<?= $icon_class ?> shrink-0"></i>
                                                <span><?= htmlspecialchars($action_text) ?></span>
                                            </div>
                                        </td>
                                        <td class="p-4 text-sm text-teal-300 font-mono text-xs">
                                            <?= htmlspecialchars($log->ip_address ?? '-') ?>
                                        </td>
                                        <td class="p-4 text-xs text-teal-400 hidden md:table-cell max-w-[200px] truncate" title="<?= htmlspecialchars($log->user_agent) ?>">
                                            <?= htmlspecialchars($log->user_agent ?? '-') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-teal-400 text-sm">Belum ada riwayat aktivitas yang tercatat.</td>
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
