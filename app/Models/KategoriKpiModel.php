<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriKpiModel extends Model
{
    protected $table            = 'kategori_kpis';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'kode_kategori',
        'nama_kategori',
        'bobot_persen',
        'deskripsi'
    ];
}
