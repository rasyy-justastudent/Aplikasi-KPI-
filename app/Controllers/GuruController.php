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
        $nipNik = trim($this->request->getPost('nip_nik') ?? '');
        if ($nipNik === '') {
            $nipNik = null;
        }

        $rules = [
            'nama_guru' => [
                'rules'  => 'required|min_length[3]',
                'errors' => [
                    'required'   => 'Nama lengkap guru wajib diisi.',
                    'min_length' => 'Nama lengkap minimal 3 karakter.'
                ]
            ],
            'username' => [
                'rules'  => 'required|is_unique[users.username]',
                'errors' => [
                    'required'  => 'Username login wajib diisi.',
                    'is_unique' => 'Username tersebut SUDAH DIGUNAKAN oleh akun pendidik/user lain. Silakan buat username yang unik (misal: nama_pendidik).'
                ]
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format penulisan email tidak valid.',
                    'is_unique'   => 'Alamat email tersebut SUDAH TERDAFTAR untuk akun pendidik lain.'
                ]
            ],
            'posisi' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Posisi / Jabatan wajib dipilih.']
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password login wajib diisi.',
                    'min_length' => 'Password minimal harus 6 karakter.'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            $errMsgs = implode('<br>', $this->validator->getErrors());
            return redirect()->back()->withInput()->with('error', $errMsgs);
        }

        if ($nipNik !== null) {
            $checkNip = $this->guruModel->where('nip_nik', $nipNik)->first();
            if ($checkNip) {
                return redirect()->back()->withInput()->with('error', 'NIP / NIK "' . $nipNik . '" sudah terdaftar untuk pendidik lain (' . $checkNip['nama_guru'] . '). Silakan gunakan NIP lain.');
            }
        }

        $nama     = $this->request->getPost('nama_guru');
        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $role     = $this->request->getPost('role') ?? 'guru';
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

        if (!$userId) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan akun user baru.');
        }

        // Insert Guru
        $inserted = $this->guruModel->insert([
            'user_id'              => $userId,
            'nip_nik'              => $nipNik,
            'nama_guru'            => $nama,
            'posisi'               => $this->request->getPost('posisi'),
            'bidang_studi'         => $this->request->getPost('bidang_studi'),
            'tingkatan_level'      => $this->request->getPost('tingkatan_level') ?: 'ECT',
            'target_ukg_persen'    => $this->request->getPost('target_ukg_persen') ?: 85.00,
            'target_jam_pelatihan' => $this->request->getPost('target_jam_pelatihan') ?: 25,
            'target_english_persen' => $this->request->getPost('target_english_persen') ?: 40.00,
            'target_digital_persen' => $this->request->getPost('target_digital_persen') ?: 75.00,
        ]);

        if (!$inserted) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan profil data pendidik.');
        }

        return redirect()->to('/guru')->with('success', 'Data pendidik baru (' . $nama . ') berhasil ditambahkan ke database.');
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
        $guru = $this->guruModel->getGuruWithUser($id);
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

        $nipNik = trim($this->request->getPost('nip_nik') ?? '');
        if ($nipNik === '') {
            $nipNik = null;
        }

        if ($nipNik !== null) {
            $checkNip = $this->guruModel->where('nip_nik', $nipNik)->where('id !=', $id)->first();
            if ($checkNip) {
                return redirect()->back()->withInput()->with('error', 'NIP / NIK "' . $nipNik . '" sudah terdaftar untuk pendidik lain (' . $checkNip['nama_guru'] . ').');
            }
        }

        $nama     = $this->request->getPost('nama_guru');
        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $role     = $this->request->getPost('role');
        $password = $this->request->getPost('password');

        if ($guru['user_id']) {
            if ($username && $username !== $guru['username']) {
                $checkUn = $this->userModel->where('username', $username)->where('id !=', $guru['user_id'])->first();
                if ($checkUn) {
                    return redirect()->back()->withInput()->with('error', 'Username sudah digunakan oleh akun lain.');
                }
            }

            if ($email && $email !== $guru['email']) {
                $checkEm = $this->userModel->where('email', $email)->where('id !=', $guru['user_id'])->first();
                if ($checkEm) {
                    return redirect()->back()->withInput()->with('error', 'Email sudah digunakan oleh akun lain.');
                }
            }

            $userUpdate = ['nama_lengkap' => $nama];
            if ($username) $userUpdate['username'] = $username;
            if ($email) $userUpdate['email'] = $email;
            if ($role) $userUpdate['role'] = $role;
            if ($password) $userUpdate['password_hash'] = password_hash($password, PASSWORD_BCRYPT);

            $this->userModel->update($guru['user_id'], $userUpdate);
        }

        // Update Guru
        $this->guruModel->update($id, [
            'nip_nik'              => $nipNik,
            'nama_guru'            => $nama,
            'posisi'               => $this->request->getPost('posisi'),
            'bidang_studi'         => $this->request->getPost('bidang_studi'),
            'tingkatan_level'      => $this->request->getPost('tingkatan_level'),
            'target_ukg_persen'    => $this->request->getPost('target_ukg_persen'),
            'target_jam_pelatihan' => $this->request->getPost('target_jam_pelatihan'),
            'target_english_persen' => $this->request->getPost('target_english_persen'),
            'target_digital_persen' => $this->request->getPost('target_digital_persen'),
        ]);

        return redirect()->to('/guru')->with('success', 'Data pendidik dan akun user berhasil diperbarui.');
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
