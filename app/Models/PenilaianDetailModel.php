<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianDetailModel extends Model
{
    protected $table            = 'penilaian_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'penilaian_id',
        'indikator_id',
        'skor_nilai',
        'jawaban_text',
        'file_path'
    ];
}
