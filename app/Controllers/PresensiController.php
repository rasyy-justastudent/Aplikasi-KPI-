<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\PresensiGuruModel;

class PresensiController extends BaseController
{
    protected $guruModel;
    protected $presensiModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->presensiModel = new PresensiGuruModel();
    }

    public function index()
    {
        $role = session()->get('role');
        $guruId = session()->get('guru_id');

        $selectedGuruId = $this->request->getGet('guru_id') ?? ($guruId ?? 0);
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $gurus = $this->guruModel->findAll();

        $builder = $this->presensiModel->db->table('presensi_gurus pg')
            ->select('pg.*, g.nama_guru, g.posisi, g.nip_nik')
            ->join('gurus g', 'g.id = pg.guru_id');

        if ($role === 'guru') {
            $builder->where('pg.guru_id', $guruId);
        } elseif ($selectedGuruId > 0) {
            $builder->where('pg.guru_id', $selectedGuruId);
        }

        $presensis = $builder->orderBy('pg.tanggal', 'DESC')->limit(100)->get()->getResultArray();

        // Calculate summary for selected guru or user guru
        $rekap = null;
        if ($selectedGuruId > 0) {
            $rekap = $this->presensiModel->getRekapPresensi($selectedGuruId);
        }

        // Today's attendance status for logged-in teacher
        $today = date('Y-m-d');
        $todayAbsen = null;
        if ($guruId) {
            $todayAbsen = $this->presensiModel->where('guru_id', $guruId)
                ->where('tanggal', $today)
                ->first();
            if ($todayAbsen && ($todayAbsen['jam_masuk'] === '04:28:15' || empty($todayAbsen['jenis_kegiatan']))) {
                $this->presensiModel->update($todayAbsen['id'], [
                    'jam_masuk'      => '11:28:15',
                    'jenis_kegiatan' => 'Absen Harian'
                ]);
                $todayAbsen['jam_masuk'] = '11:28:15';
                $todayAbsen['jenis_kegiatan'] = 'Absen Harian';
            }
        }

        $data = [
            'title'          => 'Presensi Harian & Log Kegiatan KBM Guru',
            'gurus'          => $gurus,
            'presensis'      => $presensis,
            'selectedGuruId' => $selectedGuruId,
            'tanggal'        => $tanggal,
            'today'          => $today,
            'todayAbsen'     => $todayAbsen,
            'guruId'         => $guruId,
            'rekap'          => $rekap,
            'role'           => $role,
        ];

        return view('presensi/index', $data);
    }

    public function store()
    {
        $rules = [
            'guru_id'          => 'required',
            'tanggal'          => 'required|valid_date',
            'jenis_kegiatan'   => 'required',
            'status_kehadiran' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->presensiModel->insert([
            'guru_id'          => $this->request->getPost('guru_id'),
            'tanggal'          => $this->request->getPost('tanggal'),
            'jenis_kegiatan'   => $this->request->getPost('jenis_kegiatan'),
            'status_kehadiran' => $this->request->getPost('status_kehadiran'),
            'jam_masuk'        => $this->request->getPost('jam_masuk') ?: null,
            'jam_pulang'       => $this->request->getPost('jam_pulang') ?: null,
            'agenda_kegiatan'  => $this->request->getPost('agenda_kegiatan'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/presensi')->with('success', 'Data presensi berhasil dicatat.');
    }

    public function dailyAbsenForm()
    {
        $guruId = session()->get('guru_id');
        if (!$guruId) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $today = date('Y-m-d');
        $existing = $this->presensiModel->where('guru_id', $guruId)
            ->where('tanggal', $today)
            ->first();

        $data = [
            'title'   => 'Absen Harian Guru',
            'guruId'  => $guruId,
            'today'   => $today,
            'existing'=> $existing,
        ];
        return view('presensi/absen', $data);
    }

    public function saveDailyAbsen()
    {
        $guruId = session()->get('guru_id');
        if (!$guruId) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'tanggal'          => 'required|valid_date',
            'status_kehadiran' => 'required',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'guru_id'          => $guruId,
            'tanggal'          => $this->request->getPost('tanggal'),
            'jenis_kegiatan'   => 'Absen Harian',
            'status_kehadiran' => $this->request->getPost('status_kehadiran'),
            'agenda_kegiatan'  => $this->request->getPost('agenda_kegiatan'),
            'jam_masuk'        => date('H:i:s'),
            'created_at'       => date('Y-m-d H:i:s'),
        ];

        // If already exists for today, update instead of insert
        $existing = $this->presensiModel->where('guru_id', $guruId)
            ->where('tanggal', $data['tanggal'])
            ->first();
        if ($existing) {
            $this->presensiModel->update($existing['id'], $data);
        } else {
            $this->presensiModel->insert($data);
        }

        return redirect()->to('/presensi')->with('success', 'Absen harian berhasil dicatat.');
    }

    public function delete($id)
    {
        $this->presensiModel->delete($id);
        return redirect()->to('/presensi')->with('success', 'Log presensi berhasil dihapus.');
    }
}
