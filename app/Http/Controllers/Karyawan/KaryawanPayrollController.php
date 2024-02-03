<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan\TidakHadirModel;
use App\Models\Absensi\KryOfficeDtlModel;
use App\Models\Master\MasterKaryawanModel;
use Illuminate\Support\Facades\Validator;

use DateTime;
use DB;
use PDF;

class KaryawanPayrollController extends Controller
{
    public function index()
    {
        return view('karyawan.karyawan-cuti');
    }

    public function pdf_file(Request $request)
    {
        $tahun = ['tahun' => '2023'];
        $bulan = ['bulan' => 'Januari'];

        $perusahaan = ['perusahaan' => 'Global Inti Sejati'];
        $devisi = ['devisi' => 'Human Resource'];
        $nama = ['nama' => 'Nur Hudha Haksono'];

        $pdf = PDF::loadView('karyawan.file.file-penggajian', $tahun);
        return $pdf->stream();
    }
}
