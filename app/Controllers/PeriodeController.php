<?php

namespace App\Controllers;

use App\Models\PeriodeModel;

class PeriodeController extends BaseController
{
    protected $periodeModel;

    public function __construct()
    {
        $this->periodeModel = new PeriodeModel();
    }

    public function index()
    {
        $periodes = $this->periodeModel->orderBy('id', 'DESC')->findAll();

        $data = [
            'title'    => 'Kelola Periode / Tahun Pelajaran',
            'periodes' => $periodes,
        ];

        return view('periode/index', $data);
    }

    public function store()
    {
        $rules = [
            'tahun_pelajaran' => 'required',
            'semester'        => 'required',
            'tgl_mulai'       => 'required|valid_date',
            'tgl_selesai'     => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->periodeModel->insert([
            'tahun_pelajaran' => $this->request->getPost('tahun_pelajaran'),
            'semester'        => $this->request->getPost('semester'),
            'status'          => $this->request->getPost('status') ?? 'draft',
            'tgl_mulai'       => $this->request->getPost('tgl_mulai'),
            'tgl_selesai'     => $this->request->getPost('tgl_selesai'),
        ]);

        return redirect()->to('/periode')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['draft', 'open', 'review', 'closed'])) {
            return redirect()->to('/periode')->with('error', 'Status tidak valid.');
        }

        if ($status === 'open') {
            // Close or draft other open periodes
            $this->periodeModel->where('status', 'open')->set(['status' => 'closed'])->update();
        }

        $this->periodeModel->update($id, ['status' => $status]);

        return redirect()->to('/periode')->with('success', 'Status periode berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->periodeModel->delete($id);
        return redirect()->to('/periode')->with('success', 'Periode berhasil dihapus.');
    }
}
