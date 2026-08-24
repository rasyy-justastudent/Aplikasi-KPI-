<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GuruModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $guruModel = new GuruModel();

        $user = $userModel->where('username', $username)
                          ->orWhere('email', $username)
                          ->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Username atau Email tidak ditemukan.');
        }

        if (!$user['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda telah dinonaktifkan. Hubungi Admin TU.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
        }

        $guru = $guruModel->where('user_id', $user['id'])->first();

        $sessionData = [
            'user_id'      => $user['id'],
            'username'     => $user['username'],
            'email'        => $user['email'],
            'nama_lengkap' => $user['nama_lengkap'],
            'role'         => $user['role'],
            'guru_id'      => $guru ? $guru['id'] : null,
            'posisi'       => $guru ? $guru['posisi'] : null,
            'logged_in'    => true,
        ];

        session()->set($sessionData);

        return redirect()->to('/dashboard')->with('success', 'Selamat datang kembali, ' . $user['nama_lengkap']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
