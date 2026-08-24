<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\PeriodeModel;
use App\Models\PenilaianKpiModel;
use App\Models\PresensiGuruModel;
use App\Models\BuktiPortofolioModel;
use App\Services\KpiCalculatorService;

class LaporanController extends BaseController
{
    protected $guruModel;
    protected $periodeModel;
    protected $penilaianModel;
    protected $presensiModel;
    protected $portofolioModel;
    protected $calculator;

    public function __construct()
    {
        $this->guruModel       = new GuruModel();
        $this->periodeModel    = new PeriodeModel();
        $this->penilaianModel  = new PenilaianKpiModel();
        $this->presensiModel   = new PresensiGuruModel();
        $this->portofolioModel = new BuktiPortofolioModel();
        $this->calculator      = new KpiCalculatorService();
    }

    public function rekapSekolah()
    {
        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 0;

        $gurus = $this->guruModel->getGuruWithUser();
        $rekapData = [];

        foreach ($gurus as $g) {
            $penilaian = $this->penilaianModel->where('guru_id', $g['id'])
                ->where('periode_id', $periodeId)
                ->orderBy('id', 'DESC')
                ->first();

            $rekapPresensi = $this->presensiModel->getRekapPresensi($g['id']);

            $rekapData[] = [
                'guru'          => $g,
                'penilaian'     => $penilaian,
                'rekapPresensi' => $rekapPresensi,
            ];
        }

        $data = [
            'title'         => 'Laporan Rekapitulasi KPI Sekolah (48 Guru)',
            'activePeriode' => $activePeriode,
            'rekapData'     => $rekapData,
        ];

        return view('laporan/rekap_sekolah', $data);
    }

    public function raporGuru($guruId)
    {
        $guru = $this->guruModel->getGuruWithUser($guruId);
        if (!$guru) {
            return redirect()->to('/laporan/rekap-sekolah')->with('error', 'Guru tidak ditemukan.');
        }

        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 0;

        $penilaian = $this->penilaianModel->where('guru_id', $guruId)
            ->where('periode_id', $periodeId)
            ->orderBy('id', 'DESC')
            ->first();

        // Fallback default calculation if not yet assessed
        if (!$penilaian) {
            $calc = $this->calculator->hitungNilaiAkhir(3.5, 3.8, 4.0, 3.7, 3.6);
            $penilaian = [
                'skor_pilar_1'       => $calc['skor_pilar_1'],
                'skor_pilar_2'       => $calc['skor_pilar_2'],
                'skor_pilar_3'       => $calc['skor_pilar_3'],
                'skor_pilar_4'       => $calc['skor_pilar_4'],
                'skor_pilar_5'       => $calc['skor_pilar_5'],
                'nilai_akhir_total'  => $calc['nilai_akhir'],
                'predikat_level'     => $calc['level_code'],
                'status'             => 'draft',
                'approved_at'        => null,
                'observer_comments'  => 'Perlu mempertahankan keaktifan dan meningkatkan integrasi media digital.',
                'teacher_reflection' => 'Berkomitmen untuk meningkatkan jam pelatihan Bahasa Inggris dan AI.',
            ];
        }

        $levelInfo = $this->calculator->hitungNilaiAkhir(
            ($penilaian['skor_pilar_1'] / 100) * 5,
            ($penilaian['skor_pilar_2'] / 100) * 5,
            ($penilaian['skor_pilar_3'] / 100) * 5,
            ($penilaian['skor_pilar_4'] / 100) * 5,
            ($penilaian['skor_pilar_5'] / 100) * 5
        );

        $rekapPresensi = $this->presensiModel->getRekapPresensi($guruId);
        $portofolios = $this->portofolioModel->where('guru_id', $guruId)->where('periode_id', $periodeId)->findAll();

        $data = [
            'title'         => 'Rapor KPI Individual: ' . $guru['nama_guru'],
            'guru'          => $guru,
            'activePeriode' => $activePeriode,
            'penilaian'     => $penilaian,
            'levelInfo'     => $levelInfo,
            'rekapPresensi' => $rekapPresensi,
            'portofolios'   => $portofolios,
        ];

        return view('laporan/rapor_pdf', $data);
    }

    public function exportCsv()
    {
        $activePeriode = $this->periodeModel->getActivePeriode() ?? $this->periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 0;

        $gurus = $this->guruModel->getGuruWithUser();

        $filename = 'Rekapitulasi_KPI_Guru_MI_Al_Husna_' . date('Ymd') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'NIP/NIK', 'Nama Guru', 'Posisi', 'Bidang Studi', 'Pilar 1 (25%)', 'Pilar 2 (25%)', 'Pilar 3 (20%)', 'Pilar 4 (15%)', 'Pilar 5 (15%)', 'Nilai Total', 'Level']);

        $no = 1;
        foreach ($gurus as $g) {
            $p = $this->penilaianModel->where('guru_id', $g['id'])->where('periode_id', $periodeId)->first();
            fputcsv($output, [
                $no++,
                $g['nip_nik'],
                $g['nama_guru'],
                $g['posisi'],
                $g['bidang_studi'],
                $p['skor_pilar_1'] ?? 0,
                $p['skor_pilar_2'] ?? 0,
                $p['skor_pilar_3'] ?? 0,
                $p['skor_pilar_4'] ?? 0,
                $p['skor_pilar_5'] ?? 0,
                $p['nilai_akhir_total'] ?? 0,
                $p['predikat_level'] ?? $g['tingkatan_level'],
            ]);
        }
        fclose($output);
        exit;
    }
}
