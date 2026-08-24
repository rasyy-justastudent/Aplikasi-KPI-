<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiGuruModel extends Model
{
    protected $table            = 'presensi_gurus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'guru_id',
        'tanggal',
        'jenis_kegiatan',
        'status_kehadiran',
        'jam_masuk',
        'jam_pulang',
        'agenda_kegiatan',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = false;

    public function getRekapPresensi($guruId)
    {
        $builder = $this->db->table($this->table)->where('guru_id', $guruId);
        $totalDays = $builder->countAllResults(false);
        $hadirCount = $builder->where('status_kehadiran', 'Hadir')->countAllResults();

        return [
            'total_hari' => max(1, $totalDays),
            'total_hadir' => $hadirCount,
            'persentase' => round(($hadirCount / max(1, $totalDays)) * 100, 2)
        ];
    }
}
