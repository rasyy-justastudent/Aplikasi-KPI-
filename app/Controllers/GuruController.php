<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\UserModel;

class GuruController extends BaseController
{
    protected $guruModel;
    protected $userModel;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('q');
        $levelFilter = $this->request->getGet('level');

        $builder = $this->guruModel->db->table('gurus g')
            ->select('g.*, u.username, u.email, u.role, u.is_active')
            ->join('users u', 'u.id = g.user_id', 'left');

        if ($keyword) {
            $builder->like('g.nama_guru', $keyword)
                    ->orLike('g.nip_nik', $keyword)
                    ->orLike('g.bidang_studi', $keyword);
        }

        if ($levelFilter) {
            $builder->where('g.tingkatan_level', $levelFilter);
        }

        $gurus = $builder->orderBy('g.id', 'ASC')->get()->getResultArray();

        $data = [
            'title'       => 'Data Master Pendidik MI Al-Husna',
            'gurus'       => $gurus,
            'keyword'     => $keyword,
            'levelFilter' => $levelFilter
        ];

        return view('guru/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Pendidik Baru',
            'posisiOptions' => ['Kepala Sekolah', 'Admin TU', 'Wakil Kepala Sekolah', 'Wali kelas', 'Koordinator Bidang', 'Guru Bidang Studi', "Guru Al-Qur'an"]
        ];

        return view('guru/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_guru'    => 'required|min_length[3]',
            'username'     => 'required|is_unique[users.username]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'posisi'       => 'required',
            'password'     => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = $this->request->getPost('nama_guru');
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $role = $this->request->getPost('role') ?? 'guru';
        $password = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);

        // Insert User
        $userId = $this->userModel->insert([
            'username'     => $username,
            'email'        => $email,
            'password_hash' => $password,
            'nama_lengkap' => $nama,
            'role'         => $role,
            'is_active'    => 1,
        ]);

        // Insert Guru
        $this->guruModel->insert([
            'user_id'              => $userId,
            'nip_nik'              => $this->request->getPost('nip_nik'),
            'nama_guru'            => $nama,
            'posisi'               => $this->request->getPost('posisi'),
            'bidang_studi'         => $this->request->getPost('bidang_studi'),
            'tingkatan_level'      => $this->request->getPost('tingkatan_level') ?? 'ECT',
            'target_ukg_persen'    => $this->request->getPost('target_ukg_persen') ?? 85.00,
            'target_jam_pelatihan' => $this->request->getPost('target_jam_pelatihan') ?? 25,
            'target_english_persen' => $this->request->getPost('target_english_persen') ?? 40.00,
            'target_digital_persen' => $this->request->getPost('target_digital_persen') ?? 75.00,
        ]);

        return redirect()->to('/guru')->with('success', 'Data pendidik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $guru = $this->guruModel->getGuruWithUser($id);
        if (!$guru) {
            return redirect()->to('/guru')->with('error', 'Data guru tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Data Pendidik',
            'guru'  => $guru,
            'posisiOptions' => ['Kepala Sekolah', 'Admin TU', 'Wakil Kepala Sekolah', 'Wali kelas', 'Koordinator Bidang', 'Guru Bidang Studi', "Guru Al-Qur'an"]
        ];

        return view('guru/edit', $data);
    }

    public function update($id)
    {
        $guru = $this->guruModel->find($id);
        if (!$guru) {
            return redirect()->to('/guru')->with('error', 'Data guru tidak ditemukan.');
        }

        $rules = [
            'nama_guru' => 'required|min_length[3]',
            'posisi'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = $this->request->getPost('nama_guru');

        // Update Guru
        $this->guruModel->update($id, [
            'nip_nik'              => $this->request->getPost('nip_nik'),
            'nama_guru'            => $nama,
            'posisi'               => $this->request->getPost('posisi'),
            'bidang_studi'         => $this->request->getPost('bidang_studi'),
            'tingkatan_level'      => $this->request->getPost('tingkatan_level'),
            'target_ukg_persen'    => $this->request->getPost('target_ukg_persen'),
            'target_jam_pelatihan' => $this->request->getPost('target_jam_pelatihan'),
            'target_english_persen' => $this->request->getPost('target_english_persen'),
            'target_digital_persen' => $this->request->getPost('target_digital_persen'),
        ]);

        // Update User name & role if user exists
        if ($guru['user_id']) {
            $userUpdate = ['nama_lengkap' => $nama];
            if ($this->request->getPost('role')) {
                $userUpdate['role'] = $this->request->getPost('role');
            }
            if ($this->request->getPost('password')) {
                $userUpdate['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
            }
            $this->userModel->update($guru['user_id'], $userUpdate);
        }

        return redirect()->to('/guru')->with('success', 'Data pendidik berhasil diperbarui.');
    }

    public function delete($id)
    {
        $guru = $this->guruModel->find($id);
        if ($guru) {
            if ($guru['user_id']) {
                $this->userModel->delete($guru['user_id']);
            }
            $this->guruModel->delete($id);
        }

        return redirect()->to('/guru')->with('success', 'Data pendidik berhasil dihapus.');
    }
}
