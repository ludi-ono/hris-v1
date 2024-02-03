<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Master\MasterUserModel;
use App\Models\Auth\LoginModel;
use DateTime;
use DB;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $request->validate([
            'username'              => 'required',
            'password'              => 'required',
        ], [
            'username.required'     => 'Masukkan Username',
            'password.required'     => 'Masukkan Password',
        ]);

        $data = LoginModel::where('username', $request->username)->first();
        if ($data <> Null) {
            if (Hash::check($request->password, $data->password)) {
                if ($data->status_user == 0) {
                    return redirect('/')->with('alert-verifikasi', 'Anda Belum Melakukan Verifikasi Akun');
                } else {
                    session::put('sess_id_user', $data->id_users);
                    session::put('sess_id_perusahaan', $data->id_perusahaan);
                    session::put('sess_id_devisi', $data->id_devisi);
                    session::put('sess_id_karyawan', $data->id_karyawan);
                    session::put('sess_nama', $data->nama_karyawan);
                    session::put('sess_devisi', $data->nama_devisi);
                    session::put('sess_jabatan', $data->nama_jabatan);
                    session::put('sess_username', $data->username);

                    if ($data->status == 1) {
                        session::put('sess_status', 'Super Admin');
                    } else if ($data->status == 2) {
                        session::put('sess_status', 'Human Resources');
                    } else if ($data->status == 3) {
                        session::put('sess_status', 'Karyawan');
                    }

                    session::put('sess_approve', $data->status_approve);

                    session(['berhasil_login' => true]);
                    return redirect('/dashboard');
                }
            } else {
                return redirect('/')->with('alert-wrong', 'Password salah !');
            }
        } else {
            return redirect('/')->with('alert-noaccount', 'Username Tidak Terdaftar, Silahkan Registrasi');
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect('/')->with('alert-logout', 'Anda berhasil logout');
    }
}
