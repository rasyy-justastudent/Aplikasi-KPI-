<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\PeriodeModel;
use App\Models\KategoriKpiModel;
use App\Models\IndikatorKpiModel;
use App\Models\PenilaianKpiModel;
use App\Models\PenilaianDetailModel;
use App\Models\PresensiGuruModel;
use App\Services\KpiCalculatorService;

class PenilaianController extends BaseController
{
    protected $guruModel;
    protected $periodeModel;
    protected $kategoriModel;
    protected $indikatorModel;
    protected $penilaianModel;
    protected $detailModel;
    protected $presensiModel;
    protected $calculator;

    public function __construct()
    {
        $this->guruModel      = new GuruModel();
        $this->periodeModel   = new PeriodeModel();
        $this->kategoriModel  = new KategoriKpiModel();
        $this->indikatorModel = new IndikatorKpiModel();
        $this->penilaianModel = new PenilaianKpiModel();
        $this->detailModel    = new PenilaianDetailModel();
        $this->presensiModel  = new PresensiGuruModel();
        $this->calculator     = new KpiCalculatorService();
    }

    public function index()
    {
        $role = session()->get('role');
        $userId = session()->get('user_id');
        $guruId = session()->get('guru_id');

        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 0;

        $gurus = $this->guruModel->getGuruWithUser();

        // Fetch existing assessments for active period
        $penilaians = [];
        if ($periodeId > 0) {
            $builder = $this->penilaianModel->db->table('penilaian_kpis pk')
                ->select('pk.*, g.nama_guru, g.posisi, g.tingkatan_level as guru_level, u.nama_lengkap as penilai_nama')
                ->join('gurus g', 'g.id = pk.guru_id')
                ->join('users u', 'u.id = pk.penilai_id')
                ->where('pk.periode_id', $periodeId);

            if ($role === 'guru') {
                $builder->where('pk.guru_id', $guruId);
            }

            $penilaians = $builder->orderBy('pk.id', 'DESC')->get()->getResultArray();
        }

        $data = [
            'title'         => 'Evaluasi & Penilaian KPI Guru 360°',
            'activePeriode' => $activePeriode,
            'gurus'         => $gurus,
            'penilaians'    => $penilaians,
            'role'          => $role
        ];

        return view('penilaian/index', $data);
    }

    public function input($guruTargetId)
    {
        $guruTarget = $this->guruModel->getGuruWithUser($guruTargetId);
        if (!$guruTarget) {
            return redirect()->to('/penilaian')->with('error', 'Pendidik tidak ditemukan.');
        }

        // Prevent user from evaluating themselves
        if ($guruTarget['user_id'] == session()->get('user_id')) {
            return redirect()->to('/penilaian')->with('error', 'Anda tidak dapat menilai diri sendiri. Penilaian 360° hanya dilakukan untuk menilai rekan pendidik/guru lain.');
        }

        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        if (!$activePeriode) {
            return redirect()->to('/penilaian')->with('error', 'Tidak ada periode penilaian aktif.');
        }

        $role = session()->get('role');
        $jenisPenilaian = 'waka_kepsek';
        if ($role === 'guru') {
            $jenisPenilaian = ($guruTarget['id'] == session()->get('guru_id')) ? 'self' : 'peer';
        } elseif ($role === 'koordinator') {
            $jenisPenilaian = 'observer_kelas';
        }

        // Fetch existing assessment if any
        $existing = $this->penilaianModel->where('guru_id', $guruTargetId)
            ->where('periode_id', $activePeriode['id'])
            ->where('penilai_id', session()->get('user_id'))
            ->first();

        $existingDetails = [];
        if ($existing) {
            $details = $this->detailModel->where('penilaian_id', $existing['id'])->findAll();
            foreach ($details as $d) {
                $existingDetails[$d['indikator_id']] = $d;
            }
        }

        // Role Scoping for Evaluation Categories:
        // Guru and Admin: SOSIAL_360 (Rekan Sejawat 360°)
        // Kepsek & Leadership: PROFESIONAL, KEPRIBADIAN, and SOSIAL_360
        if (in_array($role, ['guru', 'admin', 'admin_tu'])) {
            $kategoris = $this->kategoriModel->where('kode_kategori', 'SOSIAL_360')->findAll();
        } else {
            $kategoris = $this->kategoriModel->whereIn('kode_kategori', ['PROFESIONAL', 'KEPRIBADIAN', 'SOSIAL_360'])->findAll();
        }

        $indikatorsPerKategori = [];
        foreach ($kategoris as $kat) {
            $indikatorsPerKategori[$kat['id']] = $this->indikatorModel->getByKategori($kat['id']);
        }

        // Calculate attendance score for Pilar 5
        $rekapPresensi = $this->presensiModel->getRekapPresensi($guruTargetId);
        $skorPresensi = $this->calculator->hitungSkorPresensi($rekapPresensi['total_hadir'], $rekapPresensi['total_hari']);

        $data = [
            'title'                 => 'Form Input Evaluasi KPI: ' . $guruTarget['nama_guru'],
            'guruTarget'            => $guruTarget,
            'activePeriode'         => $activePeriode,
            'jenisPenilaian'        => $jenisPenilaian,
            'kategoris'             => $kategoris,
            'indikatorsPerKategori' => $indikatorsPerKategori,
            'existing'              => $existing,
            'existingDetails'       => $existingDetails,
            'rekapPresensi'         => $rekapPresensi,
            'skorPresensi'          => $skorPresensi,
        ];

        return view('penilaian/form', $data);
    }

    public function save()
    {
        $guruId = $this->request->getPost('guru_id');
        $periodeId = $this->request->getPost('periode_id');
        $jenisPenilaian = $this->request->getPost('jenis_penilaian');
        $penilaiId = session()->get('user_id');

        // Prevent user from evaluating themselves
        $guruTarget = $this->guruModel->find($guruId);
        if ($guruTarget && $guruTarget['user_id'] == $penilaiId) {
            return redirect()->to('/penilaian')->with('error', 'Anda tidak dapat menilai diri sendiri.');
        }

        $scores = $this->request->getPost('scores') ?? []; // array of indikator_id => 1-5 score

        // Fetch existing assessment for this target guru in active period as fallback score
        $existingHeader = $this->penilaianModel->where('guru_id', $guruId)
            ->where('periode_id', $periodeId)
            ->first();

        $fallbackP1 = $existingHeader ? ($existingHeader['skor_pilar_1'] / 100.0 * 5.0) : 4.0;
        $fallbackP2 = $existingHeader ? ($existingHeader['skor_pilar_2'] / 100.0 * 5.0) : 4.0;
        $fallbackP3 = $existingHeader ? ($existingHeader['skor_pilar_3'] / 100.0 * 5.0) : 4.0;
        $fallbackP4 = $existingHeader ? ($existingHeader['skor_pilar_4'] / 100.0 * 5.0) : 4.0;

        // Fetch all categories
        $kategoris = $this->kategoriModel->findAll();
        $katMap = [];
        foreach ($kategoris as $k) {
            $katMap[$k['kode_kategori']] = $k['id'];
        }

        // Helper to compute avg score for a category
        $calcKatAvg = function ($kodeKat, $fallbackVal = 4.0) use ($scores, $katMap) {
            $katId = $katMap[$kodeKat] ?? 0;
            if (!$katId) return $fallbackVal;

            $indikators = $this->indikatorModel->getByKategori($katId);
            $total = 0;
            $cnt = 0;
            foreach ($indikators as $ind) {
                if (isset($scores[$ind['id']])) {
                    $total += (float)$scores[$ind['id']];
                    $cnt++;
                }
            }

            return $cnt > 0 ? ($total / $cnt) : $fallbackVal;
        };

        $pedagogikKatId = $katMap['PEDAGOGIK'] ?? $katMap['OBS_KELAS'] ?? 0;
        $skorP1 = $calcKatAvg('PEDAGOGIK', $fallbackP1);
        if (!$skorP1 || $skorP1 == 4.0 && !isset($scores[$pedagogikKatId])) {
            $skorP1 = $calcKatAvg('OBS_KELAS', $fallbackP1);
        }

        $skorP2 = $calcKatAvg('PROFESIONAL', $fallbackP2);
        $skorP3 = $calcKatAvg('KEPRIBADIAN', $fallbackP3);
        $skorP4 = $calcKatAvg('SOSIAL_360', $fallbackP4);

        // Run full KPI calculation service (4 Pillars x 25%)
        $result = $this->calculator->hitungNilaiAkhir($skorP1, $skorP2, $skorP3, $skorP4);

        // Upsert Penilaian KPI Header
        $existing = $this->penilaianModel->where('guru_id', $guruId)
            ->where('periode_id', $periodeId)
            ->where('penilai_id', $penilaiId)
            ->first();

        // Handle PDF Rubrik upload if provided
        $filePdf = $this->request->getFile('metode_file_pdf');
        $pdfUrl = $existing['metode_file_pdf'] ?? null;

        if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
            $newName = $filePdf->getRandomName();
            $uploadDir = ROOTPATH . 'public/uploads/rubrik_metode';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filePdf->move($uploadDir, $newName);
            $pdfUrl = 'uploads/rubrik_metode/' . $newName;

            // Also register in portfolio table
            $portofolioModel = new \App\Models\BuktiPortofolioModel();
            $portofolioModel->insert([
                'guru_id'           => $guruId,
                'periode_id'        => $periodeId,
                'jenis_dokumen'     => 'pdf_rubrik_rpp_3metode',
                'judul_dokumen'     => 'PDF Rubrik RPP 3 Metode Penilaian',
                'jumlah_jam_jp'     => 0,
                'file_url'          => $pdfUrl,
                'status_validasi'   => 'pending',
                'catatan_validator' => 'Diunggah dari form evaluasi metode Pilar 5',
                'created_at'        => date('Y-m-d H:i:s'),
            ]);
        }

        $headerData = [
            'periode_id'                => $periodeId,
            'guru_id'                   => $guruId,
            'penilai_id'                => $penilaiId,
            'jenis_penilaian'           => $jenisPenilaian,
            'skor_pilar_1'              => $result['skor_pilar_1'],
            'skor_pilar_2'              => $result['skor_pilar_2'],
            'skor_pilar_3'              => $result['skor_pilar_3'],
            'skor_pilar_4'              => $result['skor_pilar_4'],
            'skor_pilar_5'              => $result['skor_pilar_5'],
            'nilai_akhir_total'         => $result['nilai_akhir'],
            'predikat_level'            => $result['level_code'],
            'status'                    => 'submitted',
            'observer_comments'         => $this->request->getPost('observer_comments'),
            'teacher_reflection'        => $this->request->getPost('teacher_reflection'),
            'metode_jenis'              => json_encode($this->request->getPost('metode_jenis') ?? []),
            'metode_proporsi'           => $this->request->getPost('metode_proporsi'),
            'metode_contoh_penyesuaian' => $this->request->getPost('metode_contoh_penyesuaian'),
            'metode_rubrik_status'      => $this->request->getPost('metode_rubrik_status'),
            'metode_file_pdf'           => $pdfUrl,
            'updated_at'                => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $this->penilaianModel->update($existing['id'], $headerData);
            $penilaianId = $existing['id'];
            $this->detailModel->where('penilaian_id', $penilaianId)->delete();
        } else {
            $headerData['created_at'] = date('Y-m-d H:i:s');
            $penilaianId = $this->penilaianModel->insert($headerData);
        }

        // Insert Detail Scores
        if (!empty($scores)) {
            $details = [];
            foreach ($scores as $indId => $scoreVal) {
                $details[] = [
                    'penilaian_id' => $penilaianId,
                    'indikator_id' => $indId,
                    'skor_nilai'   => (int)$scoreVal,
                    'jawaban_text' => null,
                ];
            }
            if (count($details) > 0) {
                $this->detailModel->insertBatch($details);
            }
        }

        return redirect()->to('/penilaian')->with('success', 'Evaluasi KPI berhasil disimpan dan dikalkulasi.');
    }

    public function approve($penilaianId)
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'admin_tu', 'kepsek'])) {
            return redirect()->to('/penilaian')->with('error', 'Hanya Kepala Sekolah / Waka / Admin yang berhak menyetujui.');
        }

        $penilaian = $this->penilaianModel->find($penilaianId);
        if (!$penilaian) {
            return redirect()->to('/penilaian')->with('error', 'Data penilaian tidak ditemukan.');
        }

        // Update Penilaian status
        $this->penilaianModel->update($penilaianId, [
            'status'      => 'approved',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
        ]);

        // Update Guru's official career level
        $this->guruModel->update($penilaian['guru_id'], [
            'tingkatan_level' => $penilaian['predikat_level']
        ]);

        return redirect()->to('/penilaian')->with('success', 'Penilaian KPI dan Tingkatan Level Guru telah disahkan.');
    }

    public function evaluasiMetode()
    {
        $guruId = session()->get('guru_id');
        if (!$guruId) {
            return redirect()->to('/dashboard')->with('error', 'Halaman ini khusus untuk akun Pendidik / Guru.');
        }

        $guruTarget = $this->guruModel->getGuruWithUser($guruId);
        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        if (!$activePeriode) {
            return redirect()->to('/dashboard')->with('error', 'Tidak ada periode penilaian aktif.');
        }

        $existing = $this->penilaianModel->where('guru_id', $guruId)
            ->where('periode_id', $activePeriode['id'])
            ->first();

        $data = [
            'title'         => 'Form Evaluasi Variasi Metode Penilaian Pembelajaran',
            'guruTarget'    => $guruTarget,
            'activePeriode' => $activePeriode,
            'existing'      => $existing,
        ];

        return view('penilaian/evaluasi_metode', $data);
    }

    public function saveEvaluasiMetode()
    {
        $guruId = session()->get('guru_id');
        if (!$guruId) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 0;
        $penilaiId = session()->get('user_id');

        $existing = $this->penilaianModel->where('guru_id', $guruId)
            ->where('periode_id', $periodeId)
            ->first();

        // Handle PDF Rubrik upload if provided
        $filePdf = $this->request->getFile('metode_file_pdf');
        $pdfUrl = $existing['metode_file_pdf'] ?? null;

        if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
            $newName = $filePdf->getRandomName();
            $uploadDir = ROOTPATH . 'public/uploads/rubrik_metode';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filePdf->move($uploadDir, $newName);
            $pdfUrl = 'uploads/rubrik_metode/' . $newName;

            // Also register in portfolio table
            $portofolioModel = new \App\Models\BuktiPortofolioModel();
            $portofolioModel->insert([
                'guru_id'           => $guruId,
                'periode_id'        => $periodeId,
                'jenis_dokumen'     => 'pdf_rubrik_rpp_3metode',
                'judul_dokumen'     => 'PDF Rubrik RPP 3 Metode Penilaian',
                'jumlah_jam_jp'     => 0,
                'file_url'          => $pdfUrl,
                'status_validasi'   => 'pending',
                'catatan_validator' => 'Diunggah dari Sidebar Evaluasi Metode Penilaian',
                'created_at'        => date('Y-m-d H:i:s'),
            ]);
        }

        $updateData = [
            'metode_jenis'              => json_encode($this->request->getPost('metode_jenis') ?? []),
            'metode_proporsi'           => $this->request->getPost('metode_proporsi'),
            'metode_contoh_penyesuaian' => $this->request->getPost('metode_contoh_penyesuaian'),
            'metode_rubrik_status'      => $this->request->getPost('metode_rubrik_status'),
            'metode_file_pdf'           => $pdfUrl,
            'updated_at'                => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $this->penilaianModel->update($existing['id'], $updateData);
        } else {
            $updateData['periode_id']      = $periodeId;
            $updateData['guru_id']         = $guruId;
            $updateData['penilai_id']      = $penilaiId;
            $updateData['jenis_penilaian'] = 'self';
            $updateData['created_at']      = date('Y-m-d H:i:s');
            $this->penilaianModel->insert($updateData);
        }

        return redirect()->to('/evaluasi-metode')->with('success', 'Form Evaluasi Variasi Metode Penilaian Pembelajaran berhasil disimpan.');
    }
}
