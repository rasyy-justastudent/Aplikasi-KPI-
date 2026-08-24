<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\PeriodeModel;
use App\Models\PenilaianKpiModel;
use App\Models\PresensiGuruModel;
use App\Models\BuktiPortofolioModel;
use App\Services\KpiCalculatorService;

class Dashboard extends BaseController
{
    public function index()
    {
        $guruModel = new GuruModel();
        $periodeModel = new PeriodeModel();
        $penilaianModel = new PenilaianKpiModel();
        $presensiModel = new PresensiGuruModel();
        $portofolioModel = new BuktiPortofolioModel();
        $calculator = new KpiCalculatorService();

        $activePeriode = $periodeModel->getActivePeriode() ?? $periodeModel->orderBy('id', 'DESC')->first();
        $periodeId = $activePeriode ? $activePeriode['id'] : 0;
        $role = session()->get('role');
        $guruId = session()->get('guru_id');

        $totalGuru = $guruModel->countAllResults();

        // Level distribution (for Admin / Leadership)
        $levelCounts = [
            'ECT'  => $guruModel->where('tingkatan_level', 'ECT')->countAllResults(),
            'DEV'  => $guruModel->where('tingkatan_level', 'DEV')->countAllResults(),
            'PROF' => $guruModel->where('tingkatan_level', 'PROF')->countAllResults(),
            'EXP'  => $guruModel->where('tingkatan_level', 'EXP')->countAllResults(),
        ];

        // School-wide average scores
        $avgScores = [
            'pilar_1' => 0,
            'pilar_2' => 0,
            'pilar_3' => 0,
            'pilar_4' => 0,
            'pilar_5' => 0,
            'total'   => 0
        ];

        if ($periodeId > 0) {
            $assessments = $penilaianModel->where('periode_id', $periodeId)->findAll();
            if (count($assessments) > 0) {
                $sumP1 = $sumP2 = $sumP3 = $sumP4 = $sumP5 = $sumTotal = 0;
                foreach ($assessments as $a) {
                    $sumP1 += $a['skor_pilar_1'];
                    $sumP2 += $a['skor_pilar_2'];
                    $sumP3 += $a['skor_pilar_3'];
                    $sumP4 += $a['skor_pilar_4'];
                    $sumP5 += $a['skor_pilar_5'];
                    $sumTotal += $a['nilai_akhir_total'];
                }
                $cnt = count($assessments);
                $avgScores = [
                    'pilar_1' => round($sumP1 / $cnt, 1),
                    'pilar_2' => round($sumP2 / $cnt, 1),
                    'pilar_3' => round($sumP3 / $cnt, 1),
                    'pilar_4' => round($sumP4 / $cnt, 1),
                    'pilar_5' => round($sumP5 / $cnt, 1),
                    'total'   => round($sumTotal / $cnt, 1)
                ];
            }
        }

        // Recent assessments
        $recentPenilaian = $penilaianModel->db->table('penilaian_kpis pk')
            ->select('pk.*, g.nama_guru, g.posisi, g.tingkatan_level')
            ->join('gurus g', 'g.id = pk.guru_id')
            ->orderBy('pk.updated_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // User specific data if teacher
        $myGuruData = null;
        $myPenilaian = null;
        $myPresensi = null;
        $myPortofolios = [];
        $myLevelInfo = null;

        if ($guruId) {
            $myGuruData = $guruModel->find($guruId);
            if ($periodeId > 0) {
                $myPenilaian = $penilaianModel->where('guru_id', $guruId)
                    ->where('periode_id', $periodeId)
                    ->orderBy('id', 'DESC')
                    ->first();
            }
            $myPresensi = $presensiModel->getRekapPresensi($guruId);
            $myPortofolios = $portofolioModel->where('guru_id', $guruId)->findAll();

            if ($myPenilaian) {
                $myLevelInfo = $calculator->hitungNilaiAkhir(
                    ($myPenilaian['skor_pilar_1'] / 100) * 5,
                    ($myPenilaian['skor_pilar_2'] / 100) * 5,
                    ($myPenilaian['skor_pilar_3'] / 100) * 5,
                    ($myPenilaian['skor_pilar_4'] / 100) * 5,
                    ($myPenilaian['skor_pilar_5'] / 100) * 5
                );
            }
        }

        $data = [
            'title'           => 'Dashboard Utama',
            'activePeriode'   => $activePeriode,
            'totalGuru'       => $totalGuru,
            'levelCounts'     => $levelCounts,
            'avgScores'       => $avgScores,
            'recentPenilaian' => $recentPenilaian,
            'role'            => $role,
            'myGuruData'      => $myGuruData,
            'myPenilaian'     => $myPenilaian,
            'myPresensi'      => $myPresensi,
            'myPortofolios'   => $myPortofolios,
            'myLevelInfo'     => $myLevelInfo,
        ];

        return view('dashboard/index', $data);
    }
}
