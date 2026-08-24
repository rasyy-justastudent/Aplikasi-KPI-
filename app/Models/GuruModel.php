<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table            = 'gurus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id',
        'nip_nik',
        'nama_guru',
        'posisi',
        'bidang_studi',
        'tingkatan_level',
        'target_ukg_persen',
        'target_jam_pelatihan',
        'target_english_persen',
        'target_digital_persen',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getGuruWithUser($id = null)
    {
        $builder = $this->db->table('gurus g')
            ->select('g.*, u.username, u.email, u.role, u.is_active')
            ->join('users u', 'u.id = g.user_id', 'left');

        if ($id !== null) {
            return $builder->where('g.id', $id)->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
}
