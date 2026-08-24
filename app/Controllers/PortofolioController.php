<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\PeriodeModel;
use App\Models\BuktiPortofolioModel;

class PortofolioController extends BaseController
{
    protected $guruModel;
    protected $periodeModel;
    protected $portofolioModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->periodeModel = new PeriodeModel();
        $this->portofolioModel = new BuktiPortofolioModel();
    }

    public function index()
    {
        $role = session()->get('role');
        $guruId = session()->get('guru_id');

        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 0;

        $builder = $this->portofolioModel->db->table('bukti_portofolios bp')
            ->select('bp.*, g.nama_guru, g.posisi, p.tahun_pelajaran, p.semester')
            ->join('gurus g', 'g.id = bp.guru_id')
            ->join('periodes p', 'p.id = bp.periode_id');

        $type = $this->request->getGet('type');
        if ($type) {
            $builder->where('bp.jenis_dokumen', $type);
        }

        $selectedGuruId = $this->request->getGet('guru_id');
        if ($selectedGuruId) {
            $builder->where('bp.guru_id', $selectedGuruId);
        }

        if ($role === 'guru') {
            $builder->where('bp.guru_id', $guruId);
        }

        $portofolios = $builder->orderBy('bp.id', 'DESC')->get()->getResultArray();

        // Calculate counts for folders
        $countBuilder = $this->portofolioModel->db->table('bukti_portofolios');
        if ($role === 'guru') {
            $countBuilder->where('guru_id', $guruId);
        } elseif ($selectedGuruId) {
            $countBuilder->where('guru_id', $selectedGuruId);
        }
        $allDocs = $countBuilder->get()->getResultArray();

        $countInggris = 0;
        $countPelatihan = 0;
        foreach ($allDocs as $doc) {
            if ($doc['jenis_dokumen'] === 'sertifikat_bahasa_inggris') {
                $countInggris++;
            } elseif ($doc['jenis_dokumen'] === 'sertifikat_pelatihan') {
                $countPelatihan++;
            }
        }

        // List status pengunggahan portofolio untuk Admin/Leadership
        $teacherStatusList = [];
        if ($role !== 'guru') {
            $allGurus = $this->guruModel->findAll();
            foreach ($allGurus as $g) {
                $gDocs = $this->portofolioModel->where('guru_id', $g['id'])->findAll();
                $ingDocs = array_filter($gDocs, fn($d) => $d['jenis_dokumen'] === 'sertifikat_bahasa_inggris');
                $pelDocs = array_filter($gDocs, fn($d) => $d['jenis_dokumen'] === 'sertifikat_pelatihan');
                $sumJp = array_sum(array_column($pelDocs, 'jumlah_jam_jp'));

                $teacherStatusList[] = [
                    'guru'            => $g,
                    'count_total'     => count($gDocs),
                    'count_inggris'   => count($ingDocs),
                    'count_pelatihan' => count($pelDocs),
                    'total_jp'        => $sumJp,
                    'has_uploaded'    => count($gDocs) > 0,
                ];
            }
        }

        $data = [
            'title'             => 'Portofolio Bukti Kinerja & Sertifikat Guru',
            'activePeriode'     => $activePeriode,
            'portofolios'       => $portofolios,
            'role'              => $role,
            'currentType'       => $type,
            'selectedGuruId'    => $selectedGuruId,
            'countInggris'      => $countInggris,
            'countPelatihan'    => $countPelatihan,
            'countTotal'        => count($allDocs),
            'teacherStatusList' => $teacherStatusList,
        ];

        return view('portofolio/index', $data);
    }

    public function store()
    {
        $guruId = session()->get('guru_id');
        if (!$guruId) {
            // Admin uploading on behalf of teacher
            $guruId = $this->request->getPost('guru_id');
        }

        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 1;

        $file = $this->request->getFile('file_dokumen');
        $fileUrl = '';
        $jenisDokumen = $this->request->getPost('jenis_dokumen');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            // Determine subdirectory based on document type
            $subDir = $jenisDokumen ?? 'others';
            $uploadDir = ROOTPATH . 'public/uploads/portofolio/' . $subDir;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $file->move($uploadDir, $newName);
            $fileUrl = 'uploads/portofolio/' . $subDir . '/' . $newName;
        } else {
            // Fallback link / external URL if given
            $fileUrl = $this->request->getPost('file_url') ?: 'uploads/portofolio/sample_certificate.pdf';
        }

        $this->portofolioModel->insert([
            'guru_id'           => $guruId,
            'periode_id'        => $periodeId,
            'jenis_dokumen'     => $this->request->getPost('jenis_dokumen'),
            'judul_dokumen'     => $this->request->getPost('judul_dokumen'),
            'jumlah_jam_jp'     => (int)$this->request->getPost('jumlah_jam_jp'),
            'file_url'          => $fileUrl,
            'status_validasi'   => 'pending',
            'catatan_validator' => null,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/portofolio')->with('success', 'Berkas portofolio berhasil diunggah dan menunggu verifikasi.');
    }

    public function validateDoc($id)
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'admin_tu', 'kepsek'])) {
            return redirect()->to('/portofolio')->with('error', 'Akses ditolak.');
        }

        $status = $this->request->getPost('status_validasi');
        $catatan = $this->request->getPost('catatan_validator');

        $this->portofolioModel->update($id, [
            'status_validasi'   => $status,
            'catatan_validator' => $catatan,
        ]);

        return redirect()->to('/portofolio')->with('success', 'Status validasi portofolio diperbarui.');
    }
}
