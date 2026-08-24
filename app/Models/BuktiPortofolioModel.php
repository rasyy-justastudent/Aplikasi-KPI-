<?php

namespace App\Models;

use CodeIgniter\Model;

class BuktiPortofolioModel extends Model
{
    protected $table            = 'bukti_portofolios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'guru_id',
        'periode_id',
        'jenis_dokumen',
        'judul_dokumen',
        'jumlah_jam_jp',
        'file_url',
        'status_validasi',
        'catatan_validator',
        'created_at'
    ];

    protected $useTimestamps = false;
}
