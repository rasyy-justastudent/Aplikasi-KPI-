<?php

namespace App\Services;

class KpiCalculatorService
{
    /**
     * Menghitung Nilai Akhir KPI (4 Pilar x 25%) dan Mengklasifikasikan Tingkatan Level Guru
     * 
     * @param float $skorPedagogik  (Skala 1-5, Bobot 25%) - Observasi & Evaluasi Metode
     * @param float $skorProfesional (Skala 1-5, Bobot 25%)
     * @param float $skorKepribadian (Skala 1-5, Bobot 25%)
     * @param float $skorSosial360   (Skala 1-5, Bobot 25%)
     * @param float $skorEvalMetode  (Optional legacy parameter, defaults to $skorPedagogik)
     * @return array
     */
    public function hitungNilaiAkhir(
        float $skorPedagogik,
        float $skorProfesional,
        float $skorKepribadian,
        float $skorSosial360,
        float $skorEvalMetode = 0.0
    ): array {
        // Konversi skala 1-5 ke skala 0-100 (Nilai / 5 * 100)
        $p1 = ($skorPedagogik / 5.0) * 100.0;
        $p2 = ($skorProfesional / 5.0) * 100.0;
        $p3 = ($skorKepribadian / 5.0) * 100.0;
        $p4 = ($skorSosial360 / 5.0) * 100.0;

        // Hitung total terbobot (4 pilar x 25.00%)
        $totalSkor = ($p1 * 0.25) + ($p2 * 0.25) + ($p3 * 0.25) + ($p4 * 0.25);
        $totalSkor = round($totalSkor, 2);

        // Tentukan Tingkatan Level MI Al-Husna
        if ($totalSkor >= 90.0) {
            $levelCode = 'EXP';
            $levelName = 'Tingkat 4: Guru Ahli (Expert)';
            $rekomendasi = 'Diberikan mandat sebagai Mentor Pendidik, Koordinator Kurikulum, & Inovator Model Ajar.';
        } elseif ($totalSkor >= 80.0) {
            $levelCode = 'PROF';
            $levelName = 'Tingkat 3: Guru Mahir (Proficient)';
            $rekomendasi = 'Mandiri & efektif. Diikutsertakan dalam lokakarya riset pembelajaran tingkat lanjut.';
        } elseif ($totalSkor >= 70.0) {
            $levelCode = 'DEV';
            $levelName = 'Tingkat 2: Guru Berkembang (Developing)';
            $rekomendasi = 'Memerlukan pendampingan moderat pada perencanaan kurikulum dan manajemen KBM.';
        } else {
            $levelCode = 'ECT';
            $levelName = 'Tingkat 1: Guru Pemula (Early Career Teacher)';
            $rekomendasi = 'Wajib mengikuti program mentoring intensif & supervisi kelas berkala oleh Waka.';
        }

        return [
            'skor_pilar_1'     => round($p1, 2),
            'skor_pilar_2'     => round($p2, 2),
            'skor_pilar_3'     => round($p3, 2),
            'skor_pilar_4'     => round($p4, 2),
            'skor_pilar_5'     => round($p1, 2),
            'nilai_akhir'      => $totalSkor,
            'level_code'       => $levelCode,
            'level_name'       => $levelName,
            'rekomendasi'      => $rekomendasi
        ];
    }

    /**
     * Hitung Persentase & Konversi Skor Presensi
     *
     * @param int $totalHadir
     * @param int $totalHariEfektif
     * @return array
     */
    public function hitungSkorPresensi(int $totalHadir, int $totalHariEfektif): array
    {
        $eff = max(1, $totalHariEfektif);
        $persen = round(($totalHadir / $eff) * 100, 2);
        $skor15 = round($persen / 20, 2); // 100% -> 5.0

        return [
            'persentase_hadir' => $persen,
            'skor_skala_5'     => min(5.0, max(1.0, $skor15)),
        ];
    }
}
