<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\PeriodeModel;
use App\Models\KategoriKpiModel;
use App\Models\IndikatorKpiModel;
use App\Models\PenilaianKpiModel;
use App\Models\PenilaianDetailModel;
use App\Models\ObserverAssignmentModel;
use App\Models\PresensiGuruModel;
use App\Services\KpiCalculatorService;

class ObservasiController extends BaseController
{
    protected $guruModel;
    protected $periodeModel;
    protected $kategoriModel;
    protected $indikatorModel;
    protected $penilaianModel;
    protected $detailModel;
    protected $assignmentModel;
    protected $presensiModel;
    protected $calculator;

    public function __construct()
    {
        $this->guruModel       = new GuruModel();
        $this->periodeModel    = new PeriodeModel();
        $this->kategoriModel   = new KategoriKpiModel();
        $this->indikatorModel  = new IndikatorKpiModel();
        $this->penilaianModel  = new PenilaianKpiModel();
        $this->detailModel     = new PenilaianDetailModel();
        $this->assignmentModel = new ObserverAssignmentModel();
        $this->presensiModel   = new PresensiGuruModel();
        $this->calculator      = new KpiCalculatorService();
    }

    public function index()
    {
        $role   = session()->get('role');
        $guruId = session()->get('guru_id');

        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId     = $activePeriode ? $activePeriode['id'] : 0;

        $gurus = $this->guruModel->getGuruWithUser();

        // Filter targetGurus: only teachers with role 'guru' or position != 'Admin TU'
        $targetGurus = array_values(array_filter($gurus, function ($g) {
            return ($g['role'] ?? '') === 'guru' || (!in_array($g['role'] ?? '', ['admin', 'admin_tu']) && ($g['posisi'] ?? '') !== 'Admin TU');
        }));

        $allAssignments  = [];
        $myObserverTasks = [];

        if ($periodeId > 0) {
            if (in_array($role, ['admin', 'admin_tu', 'kepsek', 'waka'])) {
                $allAssignments = $this->assignmentModel->getAllAssignmentsWithGuru($periodeId);
            }

            if ($guruId) {
                $myObserverTasks = $this->assignmentModel->getAssignmentsByObserver($guruId, $periodeId);
            }
        }

        $data = [
            'title'           => 'Observasi Kelas & Penugasan Observer',
            'activePeriode'   => $activePeriode,
            'gurus'           => $gurus,
            'targetGurus'     => $targetGurus,
            'allAssignments'  => $allAssignments,
            'myObserverTasks' => $myObserverTasks,
            'role'            => $role,
        ];

        return view('observasi/index', $data);
    }

    public function assign()
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'admin_tu', 'kepsek', 'waka'])) {
            return redirect()->to('/observasi')->with('error', 'Hanya Kepala Sekolah / Manajemen yang berhak menugaskan Observer.');
        }

        $observerGuruId = $this->request->getPost('observer_guru_id');
        $targetGuruId   = $this->request->getPost('target_guru_id');
        $periodeId      = $this->request->getPost('periode_id');
        $catatan        = $this->request->getPost('catatan_kepsek');

        if ($observerGuruId == $targetGuruId) {
            return redirect()->to('/observasi')->with('error', 'Guru Observer tidak boleh sama dengan Guru Target Observasi.');
        }

        // Validate that target guru is a pendidik (role === 'guru' or not Admin TU)
        $targetGuru = $this->guruModel->getGuruWithUser($targetGuruId);
        if (!$targetGuru || in_array($targetGuru['role'] ?? '', ['admin', 'admin_tu']) || ($targetGuru['posisi'] ?? '') === 'Admin TU') {
            return redirect()->to('/observasi')->with('error', 'Guru target yang di-observasi harus ber-role Pendidik / Guru (bukan Admin TU).');
        }

        $existing = $this->assignmentModel->where('periode_id', $periodeId)
            ->where('observer_guru_id', $observerGuruId)
            ->where('target_guru_id', $targetGuruId)
            ->first();

        if ($existing) {
            $this->assignmentModel->update($existing['id'], [
                'catatan_kepsek' => $catatan,
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
        } else {
            $this->assignmentModel->insert([
                'periode_id'       => $periodeId,
                'observer_guru_id' => $observerGuruId,
                'target_guru_id'   => $targetGuruId,
                'status'           => 'pending',
                'catatan_kepsek'   => $catatan,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('/observasi')->with('success', 'Penugasan Observer berhasil disimpan.');
    }

    public function deleteAssignment($id)
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'admin_tu', 'kepsek', 'waka'])) {
            return redirect()->to('/observasi')->with('error', 'Akses ditolak.');
        }

        $this->assignmentModel->delete($id);
        return redirect()->to('/observasi')->with('success', 'Penugasan Observer telah dihapus.');
    }

    public function input($assignmentId)
    {
        $assignment = $this->assignmentModel->getAssignmentDetail($assignmentId);
        if (!$assignment) {
            return redirect()->to('/observasi')->with('error', 'Data penugasan observasi tidak ditemukan.');
        }

        $userRole = session()->get('role');
        $userGuruId = session()->get('guru_id');

        if (!in_array($userRole, ['admin', 'admin_tu', 'kepsek']) && $userGuruId != $assignment['observer_guru_id']) {
            return redirect()->to('/observasi')->with('error', 'Anda tidak berhak mengisi instrumen observasi untuk penugasan ini.');
        }

        $activePeriode = $this->periodeModel->find($assignment['periode_id']);
        if (!$activePeriode) {
            return redirect()->to('/observasi')->with('error', 'Periode penilaian tidak ditemukan.');
        }

        // Fetch Pilar 1: PEDAGOGIK category & indicators
        $kategoriObs = $this->kategoriModel->where('kode_kategori', 'PEDAGOGIK')->first() ?? $this->kategoriModel->where('kode_kategori', 'OBS_KELAS')->first();
        if (!$kategoriObs) {
            return redirect()->to('/observasi')->with('error', 'Kategori Kompetensi Pedagogik tidak ditemukan.');
        }

        $indikators = $this->indikatorModel->getByKategori($kategoriObs['id']);

        // Group indicators by sub_aspek (A s.d. H)
        $groupedIndikators = [];
        foreach ($indikators as $ind) {
            $sub = $ind['sub_aspek'] ?: 'Umum';
            $groupedIndikators[$sub][] = $ind;
        }

        // Fetch existing assessment for target guru if any
        $existingHeader = $this->penilaianModel->where('guru_id', $assignment['target_guru_id'])
            ->where('periode_id', $assignment['periode_id'])
            ->first();

        $existingScores = [];
        if ($existingHeader) {
            $details = $this->detailModel->where('penilaian_id', $existingHeader['id'])->findAll();
            foreach ($details as $d) {
                $existingScores[$d['indikator_id']] = $d['skor_nilai'];
            }
        }

        $data = [
            'title'             => 'Instrumen Observasi Kelas & Supervisi KBM: ' . $assignment['target_nama'],
            'assignment'        => $assignment,
            'activePeriode'     => $activePeriode,
            'kategoriObs'       => $kategoriObs,
            'groupedIndikators' => $groupedIndikators,
            'existingHeader'    => $existingHeader,
            'existingScores'    => $existingScores,
        ];

        return view('observasi/form', $data);
    }

    public function save()
    {
        $assignmentId = $this->request->getPost('assignment_id');
        $targetGuruId = $this->request->getPost('target_guru_id');
        $periodeId    = $this->request->getPost('periode_id');
        $scores       = $this->request->getPost('scores'); // array of indikator_id => 1..5
        $comments     = $this->request->getPost('observer_comments');

        $assignment = $this->assignmentModel->find($assignmentId);
        if (!$assignment) {
            return redirect()->to('/observasi')->with('error', 'Penugasan tidak valid.');
        }

        $kategoriObs = $this->kategoriModel->where('kode_kategori', 'PEDAGOGIK')->first() ?? $this->kategoriModel->where('kode_kategori', 'OBS_KELAS')->first();
        $indikators  = $this->indikatorModel->getByKategori($kategoriObs['id']);

        // Calculate average score 1-5 for Pilar 1 (PEDAGOGIK)
        $sum = 0;
        $count = 0;
        foreach ($indikators as $ind) {
            if (isset($scores[$ind['id']])) {
                $sum += (float)$scores[$ind['id']];
                $count++;
            }
        }

        $skorObs15 = $count > 0 ? ($sum / $count) : 4.0;
        $skorPilar1Persen = round(($skorObs15 / 5.0) * 100.0, 2);

        // Fetch existing assessment header for target guru or create new
        $existingHeader = $this->penilaianModel->where('guru_id', $targetGuruId)
            ->where('periode_id', $periodeId)
            ->first();

        // Get default or current scores for other pillars if exists
        $skorP2_persen = $existingHeader ? $existingHeader['skor_pilar_2'] : 85.0;
        $skorP3_persen = $existingHeader ? $existingHeader['skor_pilar_3'] : 85.0;
        $skorP4_persen = $existingHeader ? $existingHeader['skor_pilar_4'] : 85.0;

        // Convert percentage scores back to scale 1-5 for calculator
        $p1_15 = $skorObs15;
        $p2_15 = ($skorP2_persen / 100.0) * 5.0;
        $p3_15 = ($skorP3_persen / 100.0) * 5.0;
        $p4_15 = ($skorP4_persen / 100.0) * 5.0;

        $calc = $this->calculator->hitungNilaiAkhir($p1_15, $p2_15, $p3_15, $p4_15);

        $penilaiUserId = session()->get('user_id');

        $headerData = [
            'periode_id'        => $periodeId,
            'guru_id'           => $targetGuruId,
            'penilai_id'        => $penilaiUserId,
            'jenis_penilaian'   => 'observer_kelas',
            'skor_pilar_1'      => $skorPilar1Persen,
            'skor_pilar_2'      => $skorP2_persen,
            'skor_pilar_3'      => $skorP3_persen,
            'skor_pilar_4'      => $skorP4_persen,
            'skor_pilar_5'      => $skorP5_persen,
            'nilai_akhir_total' => $calc['nilai_akhir'],
            'predikat_level'    => $calc['level_code'],
            'status'            => 'submitted',
            'observer_comments' => $comments,
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        if ($existingHeader) {
            $this->penilaianModel->update($existingHeader['id'], $headerData);
            $penilaianId = $existingHeader['id'];
        } else {
            $headerData['created_at'] = date('Y-m-d H:i:s');
            $penilaianId = $this->penilaianModel->insert($headerData);
        }

        // Save detail scores
        if (!empty($scores)) {
            foreach ($scores as $indId => $scVal) {
                $exDetail = $this->detailModel->where('penilaian_id', $penilaianId)
                    ->where('indikator_id', $indId)
                    ->first();

                if ($exDetail) {
                    $this->detailModel->update($exDetail['id'], [
                        'skor_nilai' => (int)$scVal
                    ]);
                } else {
                    $this->detailModel->insert([
                        'penilaian_id' => $penilaianId,
                        'indikator_id' => $indId,
                        'skor_nilai'   => (int)$scVal
                    ]);
                }
            }
        }

        // Mark assignment status as completed
        $this->assignmentModel->update($assignmentId, [
            'status'     => 'completed',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/observasi')->with('success', 'Penilaian Observasi Kelas KBM (20 Indikator) berhasil disimpan.');
    }
}
