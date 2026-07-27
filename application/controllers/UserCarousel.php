<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserCarousel extends CI_Controller
{
    private $allowed_frames = [
        'original', 'green_vines', 'blue_wave', 'flowers_stitch',
        'yellow_sunflowers', 'green_dots', 'green_waves', 'pink_glitter',
        'purple_stripes', 'black_dots', 'orange_spirals', 'green_orange_wave', 'abstract_wavy',
        'checkered', 'zigzag_colorful', 'ethnic_red'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

        // Wajib login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/');
            return;
        }
    }

    /**
     * Path ke file JSON carousel milik user tertentu
     */
    private function _user_json_path($user_id)
    {
        return FCPATH . 'assets/carousel-user-' . (int)$user_id . '.json';
    }

    /**
     * Baca carousel milik user
     */
    private function _get_user_items($user_id)
    {
        $path = $this->_user_json_path($user_id);
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?: [];
    }

    /**
     * Simpan carousel milik user
     */
    private function _save_user_items($user_id, $items)
    {
        file_put_contents($this->_user_json_path($user_id), json_encode(array_values($items)));
    }

    /**
     * Halaman manajemen foto carousel user
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $items   = $this->_get_user_items($user_id);

        $data = [
            'carousel_items'  => $items,
            'allowed_frames'  => $this->allowed_frames,
            'user_id'         => $user_id,
            'full_name'       => $this->session->userdata('full_name'),
            'success_msg'     => $this->session->flashdata('success_msg'),
            'error_msg'       => $this->session->flashdata('error_msg'),
        ];

        $this->load->view('templates/header');
        $this->load->view('partials/navbar');
        $this->load->view('user/carousel_manage', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Tambah foto baru
     */
    public function add()
    {
        $user_id = $this->session->userdata('user_id');

        if (empty($_FILES['photo']['name'])) {
            $this->session->set_flashdata('error_msg', 'Pilih foto terlebih dahulu.');
            redirect('user_carousel');
            return;
        }

        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $this->session->set_flashdata('error_msg', 'Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
            redirect('user_carousel');
            return;
        }

        if ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
            $this->session->set_flashdata('error_msg', 'Ukuran file maksimal 5MB.');
            redirect('user_carousel');
            return;
        }

        // Batasi max 10 foto per user
        $items = $this->_get_user_items($user_id);
        if (count($items) >= 10) {
            $this->session->set_flashdata('error_msg', 'Maksimal 10 foto per akun.');
            redirect('user_carousel');
            return;
        }

        $upload_path = FCPATH . 'assets/images/user_carousel/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0777, true);

        $new_name = 'u' . $user_id . '_' . time() . '.' . $ext;
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path . $new_name)) {
            $this->session->set_flashdata('error_msg', 'Gagal upload foto. Coba lagi.');
            redirect('user_carousel');
            return;
        }

        $caption     = trim($this->input->post('caption', TRUE)) ?: 'Keluarga';
        $frame_style = $this->input->post('frame_style', TRUE);
        if (!in_array($frame_style, $this->allowed_frames)) $frame_style = 'original';

        $frame_color = $this->input->post('frame_color', TRUE) ?: '#ffffff';
        if (!preg_match('/^#[0-9a-fA-F]{3,6}$/', $frame_color)) $frame_color = '#ffffff';

        $items[] = [
            'file'        => 'user_carousel/' . $new_name,
            'caption'     => $caption,
            'frame_style' => $frame_style,
            'frame_color' => $frame_color,
        ];
        $this->_save_user_items($user_id, $items);

        $this->session->set_flashdata('success_msg', 'Foto berhasil ditambahkan ke carousel!');
        redirect('user_carousel');
    }

    /**
     * Update border / caption foto milik sendiri
     */
    public function update()
    {
        $user_id = $this->session->userdata('user_id');
        $index   = (int) $this->input->post('index');
        $items   = $this->_get_user_items($user_id);

        if (!isset($items[$index])) {
            $this->session->set_flashdata('error_msg', 'Foto tidak ditemukan.');
            redirect('user_carousel');
            return;
        }

        $frame_style = $this->input->post('frame_style', TRUE);
        if (!in_array($frame_style, $this->allowed_frames)) $frame_style = 'original';

        $frame_color = $this->input->post('frame_color', TRUE) ?: ($items[$index]['frame_color'] ?? '#ffffff');
        if (!preg_match('/^#[0-9a-fA-F]{3,6}$/', $frame_color)) $frame_color = '#ffffff';

        $caption = trim($this->input->post('caption', TRUE)) ?: ($items[$index]['caption'] ?? 'Keluarga');

        $items[$index]['frame_style'] = $frame_style;
        $items[$index]['frame_color'] = $frame_color;
        $items[$index]['caption']     = $caption;

        $this->_save_user_items($user_id, $items);
        $this->session->set_flashdata('success_msg', 'Border foto berhasil diperbarui!');
        redirect('user_carousel');
    }

    /**
     * Hapus foto milik sendiri
     */
    public function delete()
    {
        $user_id = $this->session->userdata('user_id');
        $index   = (int) $this->input->post('index');
        $items   = $this->_get_user_items($user_id);

        if (!isset($items[$index])) {
            $this->session->set_flashdata('error_msg', 'Foto tidak ditemukan.');
            redirect('user_carousel');
            return;
        }

        // Hapus file fisik
        $file_path = FCPATH . 'assets/images/' . $items[$index]['file'];
        if (file_exists($file_path)) unlink($file_path);

        array_splice($items, $index, 1);
        $this->_save_user_items($user_id, $items);

        $this->session->set_flashdata('success_msg', 'Foto berhasil dihapus.');
        redirect('user_carousel');
    }
}
