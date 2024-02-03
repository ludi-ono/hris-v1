<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Auth\UserModels;
use App\Models\Master\MasterUserModel;
use App\Models\Master\MasterKaryawanModel;
use DB;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function postregister(Request $request)
    {
        if ($request->karyawan == "") {
            return redirect('/register')->with('alert-nrp', 'NRP Tidak Tersedia');
        } elseif ($request->username == "") {
            return redirect('/register')->with('alert-username', 'Username Tidak Boleh Kosong');
        } elseif ($request->password == "") {
            return redirect('/register')->with('alert-password', 'Password Tidak Boleh Kosong');
        } elseif ($request->repassword == "") {
            return redirect('/register')->with('alert-repassword', 'Ulangi Password Tidak Boleh Kosong');
        } elseif ($request->password <> $request->repassword) {
            return redirect('/register')->with('alert-salah', 'Password Tidak Sama Dengan Ulangi Password');
        } else {
            $data = new MasterUserModel();
            $data->username = $request->username;
            $data->password = bcrypt($request->password);
            $data->id_karyawan = $request->karyawan;
            $data->save();

            // Update Status
            $data_karyawan = [
                'status_user' => 1
            ];

            MasterKaryawanModel::where('id', $request->karyawan)->update($data_karyawan);
            return redirect('/')->with('alert-berhasil', 'Registrasi Berhasil, Silahkan Login');
        }
    }

    function karyawan()
    {
        $query = DB::table('mst_karyawan')->where('status_user', '=', 0)->get();
        return response()->json(['data' => $query]);
    }
}
