<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yayasan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'html']);
    }

    public function index()
    {
        redirect('yayasan/rekapitulasi');
    }

    public function rekapitulasi()
    {
        // Fetch approved candidates for recap
        $this->db->where('status', 'approved');
        $raw_approved = $this->db->get('yayasan_candidates')->result_array();

        $grouped = [];
        $rundayan_detail_map = [];

        foreach ($raw_approved as $c) {
            $displayName = normalize_candidate_name($c['candidate_name']);
            $candType    = $c['type'] ?? 'individu';
            
            // Clean role resolution
            $r_raw = trim($c['description']);
            if (preg_match('/bendahara/i', $r_raw)) {
                $role = 'Bendahara';
            } elseif (preg_match('/sekretaris/i', $r_raw)) {
                $role = 'Sekretaris';
            } else {
                $role = 'Ketua';
            }

            $nom  = trim($c['nominator_name']);
            $anc  = trim($c['ancestor_name']);
            $key  = $candType . '_' . strtolower(trim($displayName));

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id'             => $c['id'],
                    'candidate_name' => $displayName,
                    'ancestor_name'  => $c['ancestor_name'],
                    'type'           => $candType,
                    'nominators'     => [$nom],
                    'ancestors'      => [$anc],
                    'votes_count'    => 1,
                    'ancestor_breakdown' => [$anc => 1],
                    'roles'          => [$role],
                    'role_counts'    => [$role => 1],
                    'created_at'     => $c['created_at']
                ];
            } else {
                $grouped[$key]['nominators'][] = $nom;
                $grouped[$key]['ancestors'][]  = $anc;
                $grouped[$key]['votes_count'] += 1;
                
                if (!isset($grouped[$key]['ancestor_breakdown'][$anc])) {
                    $grouped[$key]['ancestor_breakdown'][$anc] = 1;
                } else {
                    $grouped[$key]['ancestor_breakdown'][$anc] += 1;
                }
                $grouped[$key]['roles'][] = $role;
                if (!isset($grouped[$key]['role_counts'][$role])) {
                    $grouped[$key]['role_counts'][$role] = 1;
                } else {
                    $grouped[$key]['role_counts'][$role] += 1;
                }
            }

            // Map detail per rundayan for Hover feature
            $anc_list = array_map('trim', explode(',', $anc));
            foreach ($anc_list as $single_anc) {
                if (empty($single_anc)) continue;
                if (!isset($rundayan_detail_map[$single_anc])) {
                    $rundayan_detail_map[$single_anc] = [
                        'ancestor_name' => $single_anc,
                        'nominators'    => [],
                        'candidates'    => [],
                        'total_votes'   => 0
                    ];
                }
                $candRole  = trim($c['description']);
                $candEntry = $displayName . " (" . $candRole . ")";
                $rundayan_detail_map[$single_anc]['nominators'][] = $nom;
                $rundayan_detail_map[$single_anc]['candidates'][] = $candEntry;
            }
        }

        // Clean & unique detail map per rundayan
        foreach ($rundayan_detail_map as $anc_key => $data) {
            $rundayan_detail_map[$anc_key]['nominators'] = array_values(array_unique($data['nominators']));
            $rundayan_detail_map[$anc_key]['candidates'] = array_values(array_unique($data['candidates']));
            $rundayan_detail_map[$anc_key]['total_votes'] = count($rundayan_detail_map[$anc_key]['nominators']);
        }

        $individu_candidates = [];
        $rundayan_candidates = [];

        foreach ($grouped as $key => $g) {
            $g['nominator_name'] = implode(', ', array_unique($g['nominators']));
            $g['ancestor_name']  = implode(', ', array_unique($g['ancestors']));
            
            $unique_roles = array_values(array_unique($g['roles']));
            $g['roles_text'] = !empty($unique_roles) ? implode(', ', $unique_roles) : 'Ketua';
            
            $breakdowns = [];
            foreach ($g['ancestor_breakdown'] as $anc_name => $count) {
                $breakdowns[] = htmlspecialchars($anc_name) . " (" . $count . " suara)";
            }
            $g['breakdown_text'] = implode(', ', $breakdowns);

            if ($g['type'] === 'rundayan') {
                $rundayan_candidates[] = $g;
            } else {
                $individu_candidates[] = $g;
            }
        }

        usort($individu_candidates, function($a, $b) {
            return $b['votes_count'] <=> $a['votes_count'];
        });

        // Urutan Rundayan sesuai ketetapan yayasan
        // 1. Tuti Suprapti Samhudi
        // 2. Kartini Samhudi
        // 3. Enden Kardinah
        // 4. Kamil Samhudi
        $rundayan_order = [
            'tuti suprapti samhudi' => 1,
            'kartini samhudi'       => 2,
            'enden kardinah'        => 3,
            'kamil samhudi'         => 4,
        ];
        usort($rundayan_candidates, function($a, $b) use ($rundayan_order) {
            $anc_a = strtolower(trim($a['ancestor_name']));
            $anc_b = strtolower(trim($b['ancestor_name']));
            $order_a = $rundayan_order[$anc_a] ?? 999;
            $order_b = $rundayan_order[$anc_b] ?? 999;
            if ($order_a !== $order_b) {
                return $order_a <=> $order_b;
            }
            // Jika rundayan sama, urutkan berdasarkan votes terbanyak
            return $b['votes_count'] <=> $a['votes_count'];
        });

        // Search filters
        $search_individu = $this->input->get('search_individu', TRUE) ?? '';
        if (!empty($search_individu)) {
            $individu_candidates = array_values(array_filter($individu_candidates, function($c) use ($search_individu) {
                return stripos($c['candidate_name'], $search_individu) !== false ||
                       stripos($c['nominator_name'], $search_individu) !== false ||
                       stripos($c['ancestor_name'], $search_individu) !== false;
            }));
        }

        $search_rundayan = $this->input->get('search_rundayan', TRUE) ?? '';
        if (!empty($search_rundayan)) {
            $rundayan_candidates = array_values(array_filter($rundayan_candidates, function($c) use ($search_rundayan) {
                return stripos($c['candidate_name'], $search_rundayan) !== false ||
                       stripos($c['nominator_name'], $search_rundayan) !== false ||
                       stripos($c['ancestor_name'], $search_rundayan) !== false;
            }));
        }

        // Pagination for Individu Cards (3 per page for mobile optimization)
        $total_cards_individu = count($individu_candidates);
        $limit_cards_individu = 3;
        $page_card_individu_raw = $this->input->get('page_card_individu');
        if ($page_card_individu_raw === 'all') {
            $page_card_individu   = 'all';
            $limit_cards_individu = $total_cards_individu ?: 1;
            $individu_cards_paginated = $individu_candidates;
        } else {
            $page_card_individu   = $page_card_individu_raw ? (int) $page_card_individu_raw : 1;
            $offset_card_individu = ($page_card_individu - 1) * $limit_cards_individu;
            $individu_cards_paginated = array_slice($individu_candidates, $offset_card_individu, $limit_cards_individu);
        }

        // Pagination for Rundayan Cards (3 per page for mobile optimization)
        $total_cards_rundayan = count($rundayan_candidates);
        $limit_cards_rundayan = 3;
        $page_card_rundayan_raw = $this->input->get('page_card_rundayan');
        if ($page_card_rundayan_raw === 'all') {
            $page_card_rundayan   = 'all';
            $limit_cards_rundayan = $total_cards_rundayan ?: 1;
            $rundayan_cards_paginated = $rundayan_candidates;
        } else {
            $page_card_rundayan   = $page_card_rundayan_raw ? (int) $page_card_rundayan_raw : 1;
            $offset_card_rundayan = ($page_card_rundayan - 1) * $limit_cards_rundayan;
            $rundayan_cards_paginated = array_slice($rundayan_candidates, $offset_card_rundayan, $limit_cards_rundayan);
        }

        // Pagination for Section 3 Table: Individu (5 per page)
        $total_tbl_individu   = count($individu_candidates);
        $limit_tbl_individu   = 5;
        $page_tbl_individu_raw = $this->input->get('page_tbl_individu');
        if ($page_tbl_individu_raw === 'all') {
            $page_tbl_individu  = 'all';
            $limit_tbl_individu = $total_tbl_individu ?: 1;
            $individu_tbl_paginated = $individu_candidates;
        } else {
            $page_tbl_individu  = $page_tbl_individu_raw ? (int) $page_tbl_individu_raw : 1;
            $offset_tbl_individu = ($page_tbl_individu - 1) * $limit_tbl_individu;
            $individu_tbl_paginated = array_slice($individu_candidates, $offset_tbl_individu, $limit_tbl_individu);
        }

        // Pagination for Section 3 Table: Rundayan (5 per page)
        $total_tbl_rundayan   = count($rundayan_candidates);
        $limit_tbl_rundayan   = 5;
        $page_tbl_rundayan_raw = $this->input->get('page_tbl_rundayan');
        if ($page_tbl_rundayan_raw === 'all') {
            $page_tbl_rundayan  = 'all';
            $limit_tbl_rundayan = $total_tbl_rundayan ?: 1;
            $rundayan_tbl_paginated = $rundayan_candidates;
        } else {
            $page_tbl_rundayan  = $page_tbl_rundayan_raw ? (int) $page_tbl_rundayan_raw : 1;
            $offset_tbl_rundayan = ($page_tbl_rundayan - 1) * $limit_tbl_rundayan;
            $rundayan_tbl_paginated = array_slice($rundayan_candidates, $offset_tbl_rundayan, $limit_tbl_rundayan);
        }

        $search_bagan = $this->input->get('search_bagan', TRUE) ?? '';
        $approved_filtered = $raw_approved;
        if (!empty($search_bagan)) {
            $approved_filtered = array_filter($raw_approved, function($c) use ($search_bagan) {
                return stripos($c['candidate_name'], $search_bagan) !== false ||
                       stripos($c['nominator_name'], $search_bagan) !== false ||
                       stripos($c['ancestor_name'], $search_bagan) !== false;
            });
        }

        // Data for 3D Pie Chart - Single slice per candidate (Name-only) with role_counts for custom legend
        $chart_data_individu_map = [];
        foreach ($individu_candidates as $c) {
            $normName = normalize_candidate_name($c['candidate_name']);
            $nameKey  = strtolower(trim($normName));
            if (!isset($chart_data_individu_map[$nameKey])) {
                $chart_data_individu_map[$nameKey] = [
                    'name'         => $normName,
                    'y'            => (int) $c['votes_count'],
                    'nominators'   => [$c['nominator_name']],
                    'ancestors'    => [$c['ancestor_name']],
                    'roles'        => $c['roles_text'],
                    'role_counts'  => $c['role_counts'] ?? [],
                    'breakdown'    => [$c['breakdown_text']]
                ];
            } else {
                $chart_data_individu_map[$nameKey]['y'] += (int) $c['votes_count'];
                $chart_data_individu_map[$nameKey]['nominators'][] = $c['nominator_name'];
                $chart_data_individu_map[$nameKey]['ancestors'][]  = $c['ancestor_name'];
                $chart_data_individu_map[$nameKey]['breakdown'][]  = $c['breakdown_text'];
            }
        }
        $chart_data_individu = [];
        foreach ($chart_data_individu_map as $item) {
            $item['nominators'] = implode(', ', array_unique(explode(', ', implode(', ', $item['nominators']))));
            $item['ancestors']  = implode(', ', array_unique(explode(', ', implode(', ', $item['ancestors']))));
            $item['breakdown']  = implode(', ', array_unique(explode(', ', implode(', ', $item['breakdown']))));
            $chart_data_individu[] = $item;
        }

        $chart_data_rundayan_map = [];
        foreach ($rundayan_candidates as $c) {
            $normName = normalize_candidate_name($c['candidate_name']);
            $nameKey  = strtolower(trim($normName));
            if (!isset($chart_data_rundayan_map[$nameKey])) {
                $chart_data_rundayan_map[$nameKey] = [
                    'name'         => $normName,
                    'y'            => (int) $c['votes_count'],
                    'nominators'   => [$c['nominator_name']],
                    'ancestors'    => [$c['ancestor_name']],
                    'roles'        => $c['roles_text'],
                    'role_counts'  => $c['role_counts'] ?? [],
                    'breakdown'    => [$c['breakdown_text']]
                ];
            } else {
                $chart_data_rundayan_map[$nameKey]['y'] += (int) $c['votes_count'];
                $chart_data_rundayan_map[$nameKey]['nominators'][] = $c['nominator_name'];
                $chart_data_rundayan_map[$nameKey]['ancestors'][]  = $c['ancestor_name'];
                $chart_data_rundayan_map[$nameKey]['breakdown'][]  = $c['breakdown_text'];
            }
        }
        $chart_data_rundayan = [];
        foreach ($chart_data_rundayan_map as $item) {
            $item['nominators'] = implode(', ', array_unique(explode(', ', implode(', ', $item['nominators']))));
            $item['ancestors']  = implode(', ', array_unique(explode(', ', implode(', ', $item['ancestors']))));
            $item['breakdown']  = implode(', ', array_unique(explode(', ', implode(', ', $item['breakdown']))));
            $chart_data_rundayan[] = $item;
        }

        usort($chart_data_individu, function($a, $b) { return $b['y'] <=> $a['y']; });
        usort($chart_data_rundayan, function($a, $b) { return $b['y'] <=> $a['y']; });

        // Fetch all distinct candidate names, nominator names, and ancestor names for autocomplete suggestions
        $noms = $this->db->select('nominator_name as name')->get('yayasan_candidates')->result_array();
        $cands = $this->db->select('candidate_name as name')->get('yayasan_candidates')->result_array();
        $ancs = $this->db->select('ancestor_name as name')->get('yayasan_candidates')->result_array();
        
        $all_names_list = [];
        foreach (array_merge($noms, $cands, $ancs) as $r) {
            if (!empty($r['name'])) {
                $all_names_list[] = trim($r['name']);
            }
        }
        $all_names = array_values(array_unique($all_names_list));

        // Master 14 Rundayan Samhudi beserta Nama PJ / Koordinator Penginput
        $master_14_rundayan = [
            'HIDAYAT SAMHUDI'                => 'Emir',
            'HM. SALEH SAMHUDI'              => 'C Nia',
            "Hj SA'ADIAH SAMHUDI"            => 'C Ina',
            'H. AMIDIN SAMHUDI'              => 'Caca',
            'BUSTOMI (TOMI) SAMHUDI'        => 'Gina',
            'ABDUL FATAH (UTUN) SAMHUDI'     => 'Herry',
            'Hj DJUMENAH (CUCU) SAMHUDI'     => 'Yenny',
            'Hj NANI SOMARNI (ENAN) SAMHUDI' => 'Febby',
            'Hj MARIAM (MARI) SAMHUDI'       => 'Tania',
            'H. ABDUL HAMID (ACEP) SAMHUDI'  => 'Hilda',
            'Tuti Suprapti Samhudi'          => 'Ike',
            'Kartini Samhudi'                => 'Tedi',
            'Enden Kardinah'                 => 'Deni',
            'Kamil Samhudi'                  => 'Enong'
        ];

        // Track perolehan inputan & jumlah pemilih unik per rundayan
        $rundayan_vote_counts  = [];
        $rundayan_voter_names  = [];
        foreach ($raw_approved as $c) {
            $nom       = trim($c['nominator_name'] ?? '');
            $candType  = $c['type'] ?? 'individu';
            $anc_spl   = array_map('trim', explode(',', $c['ancestor_name']));

            foreach ($anc_spl as $single_anc) {
                if (empty($single_anc)) continue;
                foreach ($master_14_rundayan as $m_anc => $pj_name) {
                    if (stripos($single_anc, $m_anc) !== false || stripos($m_anc, $single_anc) !== false) {
                        $key_low = strtolower($m_anc);
                        if (!isset($rundayan_vote_counts[$key_low])) {
                            $rundayan_vote_counts[$key_low] = 0;
                            $rundayan_voter_names[$key_low] = [];
                        }
                        // Vote count khusus form rundayan
                        if ($candType === 'rundayan') {
                            $rundayan_vote_counts[$key_low] += 1;
                        }
                        // Voter count dari SEMUA penombok asal rundayan tersebut
                        if (!empty($nom)) {
                            $rundayan_voter_names[$key_low][] = strtolower($nom);
                        }
                    }
                }
            }
        }

        $rundayan_input_status = [];
        foreach ($master_14_rundayan as $m_anc => $pj_name) {
            $key_low   = strtolower($m_anc);
            $vote_cnt  = $rundayan_vote_counts[$key_low] ?? 0;
            $voter_cnt = isset($rundayan_voter_names[$key_low]) ? count(array_unique($rundayan_voter_names[$key_low])) : 0;
            $has_input = $vote_cnt > 0;
            $rundayan_input_status[] = [
                'name'       => $m_anc,
                'pj'         => $pj_name,
                'has_input'  => $has_input,
                'vote_count' => $vote_cnt,
                'voter_count'=> $voter_cnt
            ];
        }

        // Build modal data keyed EXACTLY by master rundayan names (reliable JS lookup)
        $rundayan_modal_data = [];
        foreach ($master_14_rundayan as $m_anc => $pj_name) {
            $rundayan_modal_data[$m_anc] = ['candidates' => [], 'nominators' => [], 'total_votes' => 0];
        }
        foreach ($raw_approved as $c) {
            // KHUSUS KATEGORI RUNDAYAN SAJA!
            if (($c['type'] ?? 'individu') !== 'rundayan') continue;

            $anc_spl     = array_map('trim', explode(',', $c['ancestor_name'] ?? ''));
            $candRole    = trim($c['description'] ?? '');
            $cName       = trim($c['candidate_name'] ?? '');
            // Normalize display name sama seperti di grouped loop
            $parts = preg_split('/\s+/', $cName);
            $initials = [];
            foreach (array_slice($parts, 0, -1) as $p) { $initials[] = strtoupper(substr($p,0,1)); }
            $last = end($parts);
            $dispName = empty($initials) ? $last : implode('.', $initials) . '. ' . $last;
            $candEntry = $dispName . ' (' . $candRole . ')';
            $nom       = trim($c['nominator_name'] ?? '');

            foreach ($anc_spl as $single_anc) {
                if (empty($single_anc)) continue;
                foreach ($master_14_rundayan as $m_anc => $pj_name) {
                    if (stripos($single_anc, $m_anc) !== false || stripos($m_anc, $single_anc) !== false) {
                        $rundayan_modal_data[$m_anc]['candidates'][] = $candEntry;
                        $rundayan_modal_data[$m_anc]['nominators'][] = $nom;
                    }
                }
            }
        }
        foreach ($rundayan_modal_data as $k => $v) {
            // Keep full candidate list so votes count matches vote_count
            $rundayan_modal_data[$k]['total_votes'] = count($v['candidates']);
        }

        $data = [
            'page_title'            => 'Rekapitulasi Pemilihan Ketua Yayasan - Dewan Pembina',
            'candidates'            => $raw_approved,
            'approved_candidates'   => $approved_filtered,
            'individu_candidates'   => $individu_candidates,
            'rundayan_candidates'   => $rundayan_candidates,
            
            // Paginated Cards
            'individu_cards'        => $individu_cards_paginated,
            'total_cards_individu'  => $total_cards_individu,
            'limit_cards_individu'  => $limit_cards_individu,
            'page_card_individu'   => $page_card_individu,

            'rundayan_cards'        => $rundayan_cards_paginated,
            'total_cards_rundayan'  => $total_cards_rundayan,
            'limit_cards_rundayan'  => $limit_cards_rundayan,
            'page_card_rundayan'   => $page_card_rundayan,

            'search_individu'       => $search_individu,
            'search_rundayan'       => $search_rundayan,
            'search_bagan'          => $search_bagan,
            'all_names'             => $all_names,
            'chart_data_individu'   => $chart_data_individu,
            'chart_data_rundayan'   => $chart_data_rundayan,
            'rundayan_detail_map'   => $rundayan_detail_map,

            // Paginated Section 3 Tables
            'individu_tbl_paginated' => $individu_tbl_paginated,
            'total_tbl_individu'     => $total_tbl_individu,
            'limit_tbl_individu'     => $limit_tbl_individu,
            'page_tbl_individu'      => $page_tbl_individu,

            'rundayan_tbl_paginated' => $rundayan_tbl_paginated,
            'total_tbl_rundayan'     => $total_tbl_rundayan,
            'limit_tbl_rundayan'     => $limit_tbl_rundayan,
            'page_tbl_rundayan'      => $page_tbl_rundayan,

            // Ringkasan Suara Masuk & Status 14 Rundayan
            'total_suara_masuk'           => count($raw_approved),
            'total_suara_individu'        => array_sum(array_column($chart_data_individu, 'y')),
            'total_suara_rundayan'        => array_sum(array_column($chart_data_rundayan, 'y')),
            'total_pemilih_individu'      => count(array_unique(array_map('strtolower', array_map('trim', array_column(array_filter($raw_approved, function($c){ return ($c['type'] ?? 'individu') !== 'rundayan'; }), 'nominator_name'))))),
            'total_pemilih_rundayan'      => count(array_unique(array_map('strtolower', array_map('trim', array_column(array_filter($raw_approved, function($c){ return ($c['type'] ?? 'individu') === 'rundayan'; }), 'nominator_name'))))),
            'total_pemilih_keseluruhan'   => count(array_unique(array_map('strtolower', array_map('trim', array_column($raw_approved, 'nominator_name'))))),
            'rundayan_input_status'       => $rundayan_input_status,
            'rundayan_modal_data'         => $rundayan_modal_data
        ];

        $this->load->view('yayasan/rekapitulasi', $data);
    }
}
