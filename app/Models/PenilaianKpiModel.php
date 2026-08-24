<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianKpiModel extends Model
{
    protected $table            = 'penilaian_kpis';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'periode_id',
        'guru_id',
        'penilai_id',
        'jenis_penilaian',
        'skor_pilar_1',
        'skor_pilar_2',
        'skor_pilar_3',
        'skor_pilar_4',
        'skor_pilar_5',
        'nilai_akhir_total',
        'predikat_level',
        'status',
        'observer_comments',
        'teacher_reflection',
        'metode_jenis',
        'metode_proporsi',
        'metode_contoh_penyesuaian',
        'metode_rubrik_status',
        'metode_file_pdf',
        'approved_by',
        'approved_at',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getPenilaianComplete($guruId, $periodeId)
    {
        return $this->db->table('penilaian_kpis pk')
            ->select('pk.*, g.nama_guru, g.posisi, g.bidang_studi, g.nip_nik, u.nama_lengkap as penilai_nama')
            ->join('gurus g', 'g.id = pk.guru_id')
            ->join('users u', 'u.id = pk.penilai_id')
            ->where('pk.guru_id', $guruId)
            ->where('pk.periode_id', $periodeId)
            ->get()->getResultArray();
    }
}
