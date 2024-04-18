<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class Auth extends BaseController
{

    public function showRegistrationForm(){
        return view ('auth/register');
    }



    public function register()
    {
        $validation = \Config\Services::validation();

        // Validasi input
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'profile_picture' => 'uploaded[profile_picture]|max_size[profile_picture,1024]|is_image[profile_picture]'
        ];

        // Pesan kesalahan validasi
        $errors = [
            'email' => [
                'is_unique' => 'Email sudah terdaftar. Silakan gunakan email lain.'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('validation', $validation->getErrors());
        }

        // Ambil data dari form registrasi
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $profilePicture = $this->request->getFile('profile_picture');

        // Lakukan validasi data

        // Mulai transaksi database
        $db = db_connect();

        try {
            // Mulai transaksi
            $db->transBegin();

            // Simpan data pengguna ke dalam database
            $userModel = new UserModel();

            // Pemeriksaan apakah file telah diunggah dengan benar
            if ($profilePicture->isValid() && !$profilePicture->hasMoved()) {
                // Buat nama acak untuk file gambar
                $nameProfilePicture = $profilePicture->getRandomName();
                // Pindahkan file gambar ke folder yang ditentukan
                $profilePicture->move(WRITEPATH . 'uploads/profilepicture', $nameProfilePicture);
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'profile_picture' => $nameProfilePicture ?? ''
            ];

            $userModel->insert($userData);

            // Lakukan commit jika berhasil
            $db->transCommit();

            // Redirect dengan pesan sukses
            return redirect()->to('/')->with('message', 'Registrasi berhasil!');
        } catch (\Exception $e) {
            $db->transRollback();

            // Tambahkan notifikasi gagal
            session()->setFlashdata('error', 'Registrasi gagal: ' . $e->getMessage());

            // Redirect kembali ke halaman registrasi dengan input sebelumnya
            return redirect()->back()->withInput();
        }
    }

    public function showLoginForm()
    {
        return view('auth/login');
    }

    public function postLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Autentikasi berhasil, simpan data pengguna ke sesi
            $session = session();
            $session->set('user_id', $user['id']);
            $session->set('user_email', $user['email']);
            $session->set('user_name', $user['name']);

            return redirect()->to('/');
        } else {
            // Autentikasi gagal, tampilkan pesan error
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }
    }

    public function logout()
    {
        // Hapus sesi dan cookie 'Remember Me'
        $session = session();
        $session->destroy();

        return redirect()->to('/');
    }

    public function showForgotPassword(){
        return view('auth/forgot_password');
    }

    public function showResetPassword()
    {
        return view('auth/reset_password');
    }

    public function checkEmail()
    {
        $email = $this->request->getPost('email');

        // Validasi email
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar.');
        }

        // Jika email tersedia, lanjutkan ke halaman reset password
        return redirect()->to('reset_password/'.$user['id']);
    }

    public function resetPassword($userId)
{
    $userModel = new UserModel();
    $user = $userModel->find($userId);

    if (!$user) {
        return redirect()->to('forget_password')->with('error', 'User tidak ditemukan.');
    }

    return view('auth/reset_password', ['userId' => $userId]);
}

public function updatePassword($userId)
{
    $password = $this->request->getPost('password');
    $confirmPassword = $this->request->getPost('confirm_password');

    if ($password !== $confirmPassword) {
        return redirect()->back()->withInput()->with('error', 'Password dan konfirmasi password tidak cocok.');
    }

    $userModel = new UserModel();
    $userModel->update($userId, ['password' => password_hash($password, PASSWORD_DEFAULT)]);

    return redirect()->to('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
}

}
