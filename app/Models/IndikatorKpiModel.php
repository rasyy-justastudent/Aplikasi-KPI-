<?php

namespace App\Models;

use CodeIgniter\Model;

class IndikatorKpiModel extends Model
{
    protected $table            = 'indikator_kpis';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'kategori_id',
        'kode_indikator',
        'sub_aspek',
        'pertanyaan_indikator',
        'tipe_jawaban',
        'target_standar',
        'urutan'
    ];

    public function getByKategori($kategoriId)
    {
        return $this->where('kategori_id', $kategoriId)->orderBy('urutan', 'ASC')->findAll();
    }
}
