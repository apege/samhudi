<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('generate_otp')) {
    /**
     * Generate kode OTP numerik.
     * @param int $length
     * @return string
     */
    function generate_otp($length = 6)
    {
        $min = (int) str_pad('1', $length, '0');
        $max = (int) str_pad('9', $length, '9');
        return (string) random_int($min, $max);
    }
}

if (!function_exists('generate_reset_token')) {
    function generate_reset_token()
    {
        return bin2hex(random_bytes(32)); // 64 char token
    }
}

if (!function_exists('normalize_candidate_name')) {
    /**
     * Normalisasi nama calon (menghilangkan awalan sebutan seperti Teh, Kak, Mas, Neng, Pa, Dang, Ibu, Pak, H., Hj., dsb)
     * untuk keperluan pengelompokan (grouping) suara kandidat yang sama.
     * 
     * @param string $name
     * @return string
     */
    function normalize_candidate_name($name)
    {
        $clean = trim($name);
        if (empty($clean)) return '';

        // Kasus khusus atau nama panggilan tertentu (alias mapping)
        $aliases = [
            'teh mita' => 'Mita Granita Samhudi',
            't mita'   => 'Mita Granita Samhudi',
            'kak mita' => 'Mita Granita Samhudi',
            'mita'     => 'Mita Granita Samhudi',
            'mita granita' => 'Mita Granita Samhudi',
            'mita samhudi' => 'Mita Granita Samhudi',
            'mita granita samhudi' => 'Mita Granita Samhudi',
            'neng tania' => 'Tania',
            'tania'    => 'Tania',
            'mas noko'  => 'Noko',
            'pa noko'   => 'Noko',
            'pak noko'  => 'Noko',
            'dang shadiq pontoh' => 'Sadiq Pontoh',
            'shadiq pontoh'      => 'Sadiq Pontoh',
            'shadiq'             => 'Sadiq Pontoh',
            'sadiq'              => 'Sadiq Pontoh',
            'sadiq pontoh'       => 'Sadiq Pontoh',
            'syafiq pontoh'      => 'Syafiq Pontoh',
            'syafiq'             => 'Syafiq Pontoh',
            'syafik pontoh'      => 'Syafiq Pontoh',
            'syafik'             => 'Syafiq Pontoh',
            'ka syafik'          => 'Syafiq Pontoh',
            'ka syafiq'          => 'Syafiq Pontoh',
            'imelda ( milly )'   => 'Imelda',
            'imelda (milly)'     => 'Imelda',
            'teh mily istrina ka syafik' => 'Imelda',
            'milly'     => 'Imelda',
            'mily'      => 'Imelda',
            'keny'      => 'Kenny Wiani',
            'kenny'     => 'Kenny Wiani',
            'kenny wiani' => 'Kenny Wiani',
            'emir'      => 'Emir Hakim Samhudi',
            'kang emir' => 'Emir Hakim Samhudi',
            'emir hakim' => 'Emir Hakim Samhudi',
            'emir hakim samhudi' => 'Emir Hakim Samhudi',
            'caca'      => 'Caca A. Samhudi',
            'caca samhudi' => 'Caca A. Samhudi',
            'caca a. samhudi' => 'Caca A. Samhudi',
            'rizki samhudi' => 'Rizki Samhudi',
            'rizky samhudi' => 'Rizki Samhudi',
            'senri'     => 'Senri Samhudi',
            'senrie'    => 'Senri Samhudi',
            't senri'   => 'Senri Samhudi',
            't senrie'  => 'Senri Samhudi',
            'sendri'    => 'Senri Samhudi',
            'sendri samhudi' => 'Senri Samhudi',
            'senri samhudi'  => 'Senri Samhudi',
        ];

        $lower = strtolower($clean);
        if (isset($aliases[$lower])) {
            return $aliases[$lower];
        }

        // 1. Hapus sebutan depan (Teh, T, Kak, Mas, Kang, Neng, Pa, Pak, Dang, Hj, H, Ibu, Aa, Bi, Mang, M, dsb)
        $prefix_patterns = '/^(teh|t\.?|mas|kak|k\.?|kang|neng|pa|p\.?|pak|dang|hj\.?|h\.?|ibu|aa|bi|mang|m\.?)\s+/i';
        $stripped = preg_replace($prefix_patterns, '', $clean);

        // 2. Hapus keterangan keluarga di belakang seperti (Putra Kang Abas, Cucu Nin Ai, Istri Ka Syafik, dsb)
        $suffix_patterns = '/\s+(putra|putri|cucu|istri|anak|suami)\b.*/i';
        $stripped = preg_replace($suffix_patterns, '', $stripped);
        $stripped_lower = strtolower(trim($stripped));

        if (isset($aliases[$stripped_lower])) {
            return $aliases[$stripped_lower];
        }

        // 3. DAFTAR NAMA RESMI KANDIDAT SAMHUDI (CANONICAL NAMES)
        $known_canonical_names = [
            'Emir Hakim Samhudi',
            'Hilvan Samhudi',
            'Mita Granita Samhudi',
            'Senri Samhudi',
            'Rizki Samhudi',
            'Caca A. Samhudi',
            'Kenny Wiani',
            'Sadiq Pontoh',
            'Syafiq Pontoh',
            'Titania Arbia Ranadipura',
            'Linda Rindayani Ranadipura',
            'Latief Harnoko',
            'Norman Hirsanudin',
            'Anggis Rakhmi Alwi',
            'Mygia Rakhma',
            'Sendri Samhudi',
            'Soraya Samhudi'
        ];

        // Dapatkan kata pertama dari nama yang di-input (misal "Emir" dari "Emir Putra Kang Abas")
        $input_first_word = strtolower(explode(' ', $stripped_lower)[0]);

        foreach ($known_canonical_names as $canonical) {
            $c_lower = strtolower($canonical);
            $c_first_word = strtolower(explode(' ', $canonical)[0]);

            // Jika kata pertama cocok (misal "emir" == "emir" dari "Emir Hakim Samhudi"),
            // ATAU string input terkandung di dalam nama resmi kandidat (misal "emir hakim" di "emir hakim samhudi")
            if (
                $input_first_word === $c_first_word ||
                $stripped_lower === $c_lower ||
                strpos($c_lower, $stripped_lower) !== false ||
                strpos($stripped_lower, $c_lower) !== false
            ) {
                return $canonical;
            }
        }

        return ucwords(strtolower(trim($stripped)));
    }
}
