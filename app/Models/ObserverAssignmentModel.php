<?php

namespace App\Models;

use CodeIgniter\Model;

class ObserverAssignmentModel extends Model
{
    protected $table            = 'penugasan_observers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'periode_id',
        'observer_guru_id',
        'target_guru_id',
        'status',
        'catatan_kepsek',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllAssignmentsWithGuru($periodeId)
    {
        return $this->db->table('penugasan_observers po')
            ->select('po.*, 
                     obs.nama_guru as observer_nama, obs.nip_nik as observer_nip, obs.posisi as observer_posisi,
                     tgt.nama_guru as target_nama, tgt.nip_nik as target_nip, tgt.posisi as target_posisi')
            ->join('gurus obs', 'obs.id = po.observer_guru_id')
            ->join('gurus tgt', 'tgt.id = po.target_guru_id')
            ->where('po.periode_id', $periodeId)
            ->orderBy('po.id', 'DESC')
            ->get()->getResultArray();
    }

    public function getAssignmentsByObserver($observerGuruId, $periodeId)
    {
        return $this->db->table('penugasan_observers po')
            ->select('po.*, 
                     tgt.nama_guru as target_nama, tgt.nip_nik as target_nip, tgt.posisi as target_posisi, tgt.bidang_studi as target_bidang')
            ->join('gurus tgt', 'tgt.id = po.target_guru_id')
            ->where('po.observer_guru_id', $observerGuruId)
            ->where('po.periode_id', $periodeId)
            ->orderBy('po.id', 'DESC')
            ->get()->getResultArray();
    }

    public function getAssignmentDetail($id)
    {
        return $this->db->table('penugasan_observers po')
            ->select('po.*, 
                     obs.nama_guru as observer_nama, obs.nip_nik as observer_nip, obs.user_id as observer_user_id,
                     tgt.nama_guru as target_nama, tgt.nip_nik as target_nip, tgt.posisi as target_posisi, tgt.user_id as target_user_id')
            ->join('gurus obs', 'obs.id = po.observer_guru_id')
            ->join('gurus tgt', 'tgt.id = po.target_guru_id')
            ->where('po.id', $id)
            ->get()->getRowArray();
    }
}
