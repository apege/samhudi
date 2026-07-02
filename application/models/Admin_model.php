<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Menghitung total user dengan role member
     */
    public function get_total_members()
    {
        return $this->db->where('role', 'member')->count_all_results('users');
    }

    /**
     * Menghitung total berita yang dipublish
     */
    public function get_total_news()
    {
        return $this->db->where('status', 'publish')->count_all_results('news');
    }

    /**
     * Menghitung total forum diskusi
     */
    public function get_total_forums()
    {
        return $this->db->count_all_results('forums');
    }

    /**
     * Menghitung total data wasiat
     */
    public function get_total_wills()
    {
        return $this->db->count_all_results('wills');
    }

    /**
     * Mendapatkan aktivitas terbaru dari database
     */
    public function get_recent_activities($limit = 5)
    {
        $sql = "
            (SELECT 
                'Membuat Postingan Forum' AS aktivitas, 
                u.full_name AS pengguna, 
                f.created_at AS waktu, 
                'Berhasil' AS status, 
                f.id AS reff_id, 
                'forum' AS tipe
            FROM forums f
            JOIN users u ON f.created_by = u.id)
            
            UNION ALL
            
            (SELECT 
                'Menambahkan Data Family' AS aktivitas, 
                u.full_name AS pengguna, 
                fm.created_at AS waktu, 
                'Berhasil' AS status, 
                fm.id AS reff_id, 
                'silsilah' AS tipe
            FROM family_members fm
            JOIN users u ON fm.user_id = u.id)
            
            UNION ALL
            
            (SELECT 
                'Membuat Berita' AS aktivitas, 
                u.full_name AS pengguna, 
                n.created_at AS waktu, 
                IF(n.status = 'publish', 'Publish', 'Draft') AS status, 
                n.id AS reff_id, 
                'berita' AS tipe
            FROM news n
            JOIN users u ON n.author_id = u.id)
            
            UNION ALL
            
            (SELECT 
                'Membuat Wasiat' AS aktivitas, 
                u.full_name AS pengguna, 
                w.created_at AS waktu, 
                IF(w.status = 'public', 'Public', 'Private') AS status, 
                w.id AS reff_id, 
                'wasiat' AS tipe
            FROM wills w
            JOIN users u ON w.created_by = u.id)
            
            ORDER BY waktu DESC 
            LIMIT ?
        ";

        return $this->db->query($sql, array((int)$limit))->result_array();
    }
}

