<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Master\MasterKaryawanModel;
use App\Models\Master\BpjsKesehatanModel;
use App\Models\Master\BpjsKetenagakerjaanModel;
use DateTime;
use DB;

class MasterThpController extends Controller
{
    public function index()
    {
        return view('master.master-thp-karyawan');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'mst_karyawan.id',
            1 => 'mst_karyawan.id',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            4 => 'mst_karyawan.gaji_pokok',
            5 => 'mst_karyawan.presensi',
            6 => 'mst_karyawan.tunjangan_jabatan',
            7 => 'mst_karyawan.tunjangan_pulsa',
        );

        $totalData = DB::table('mst_karyawan')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('mst_karyawan')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.*, mst_devisi.nama as nama_devisi')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('mst_karyawan')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.*, mst_devisi.nama as nama_devisi')
                ->offset($start)
                ->limit($limit)
                ->where('mst_karyawan.nama', 'LIKE', "%{$search}%")
                ->orWhere('mst_devisi.nama', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('mst_karyawan')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.*, mst_devisi.nama as nama_devisi')
                ->where('mst_karyawan.nama', 'LIKE', "%{$search}%")
                ->orWhere('mst_devisi.nama', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $gapok = $post->gaji_pokok ?? 0;
                $presensi = $post->presensi ?? 0;
                $tunj_pulsa = $post->tunjangan_pulsa ?? 0;
                $tunj_jabatan = $post->tunjangan_jabatan ?? 0;

                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nama_karyawan'] = $post->nama;
                $nestedData['nama_devisi'] = $post->nama_devisi;
                $nestedData['gapok'] = $gapok;
                $nestedData['presensi'] = $presensi;
                $nestedData['tunj_pulsa'] = $tunj_pulsa;
                $nestedData['tunj_jabatan'] = $tunj_jabatan;

                if ($gapok == 0 || $presensi == 0) {
                    $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='input' data-toggle='tooltip' title='Input' data-id='$post->id' data-original-title='' class='Input btn btn-info btn-sm'><i class='fa-solid fa-share'></i> &nbsp; Input THP </a>";
                } else {
                    $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a>";
                }

                // $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a>
                //                         <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function store(Request $request)
    {
        $date = new DateTime;
        
        $data = [
            'gaji_pokok' => $request->gapok,
            'presensi' => $request->presensi,
            'tunjangan_jabatan' => $request->tunj_jabatan,
            'tunjangan_pulsa' => $request->tunj_pulsa,
        ];

        MasterKaryawanModel::where('id', $request->id_karyawan)->update($data);

        $id_kry = $request->id_karyawan;
        $gapok = $request->gapok;
        $presensi = $request->presensi;
        $tunj_jabatan = $request->tunj_jabatan;
        $tunj_pulsa = $request->tunj_pulsa;

        $thp = floatval($gapok) + floatval($presensi) + floatval($tunj_jabatan) + floatval($tunj_pulsa);
        $range = floatval($gapok) + floatval($tunj_jabatan) + floatval($tunj_pulsa);

        // Insert BPSJ
        $data_umr = DB::table('mst_setting_umr')->where('status',1)->first();
        if($data_umr){
            $umr_bks = $data_umr->umr_bekasi;
            $umr_jkt = $data_umr->umr_jakarta;
        }else{
            $umr_bks = 0;
            $umr_jkt = 0;
        }

        // Insert BPSJ kesehatan
        if($thp < $umr_bks){
            $bpjs_kesehatan = (5/100) * $umr_bks;
        }else{
            $bpjs_kesehatan = (5/100) * $thp;
        }
        $data_kesehatan =  new BpjsKesehatanModel();
        $data_kesehatan->nilai = $bpjs_kesehatan;
        $data_kesehatan->id_karyawan = $id_kry;
        $data_kesehatan->user_at = session()->get('sess_username');
        $data_kesehatan->created_at = $date;
        $data_kesehatan->save();

        // Insert BPSJ ketenagakerjaan
        if($range >= 0 && $range <= 2499999){
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }elseif($range >= 2500000 && $range <= $umr_jkt){
            $bpjs_ketenagakerjaan = (3/100) * $umr_jkt;
        }else{
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }
        $data_ketenagakerjaan =  new BpjsKetenagakerjaanModel();
        $data_ketenagakerjaan->nilai = $bpjs_ketenagakerjaan;
        $data_ketenagakerjaan->id_karyawan = $id_kry;
        $data_ketenagakerjaan->user_at = session()->get('sess_username');
        $data_ketenagakerjaan->created_at = $date;
        $data_ketenagakerjaan->save();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function update(Request $request)
    {
        $date = new DateTime;
        $data = [
            'gaji_pokok' => $request->gapok,
            'presensi' => $request->presensi,
            'tunjangan_jabatan' => $request->tunj_jabatan,
            'tunjangan_pulsa' => $request->tunj_pulsa,
        ];

        MasterKaryawanModel::where('id', $request->sysid)->update($data);

        $id_kry = $request->id_karyawan;
        $gapok = $request->gapok;
        $presensi = $request->presensi;
        $tunj_jabatan = $request->tunj_jabatan;
        $tunj_pulsa = $request->tunj_pulsa;

        // Insert BPSJ
        $data_umr = DB::table('mst_setting_umr')->where('status',1)->first();
        if($data_umr){
            $umr_bks = $data_umr->umr_bekasi;
            $umr_jkt = $data_umr->umr_jakarta;
        }else{
            $umr_bks = 0;
            $umr_jkt = 0;
        }

        $thp = floatval($gapok) + floatval($presensi) + floatval($tunj_jabatan) + floatval($tunj_pulsa);
        $range = floatval($gapok) + floatval($tunj_jabatan) + floatval($tunj_pulsa);

        // Insert BPSJ kesehatan
        if($thp < $umr_bks){
            $bpjs_kesehatan = (5/100) * $umr_bks;
        }else{
            $bpjs_kesehatan = (5/100) * $thp;
        }
        $data_update = [
            'nilai' => $bpjs_kesehatan,
        ];
        BpjsKesehatanModel::where('id_karyawan', $request->sysid)->update($data_update);

        // Insert BPSJ ketenagakerjaan
        if($range >= 0 && $range <= 2499999){
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }elseif($range >= 2500000 && $range <= $umr_jkt){
            $bpjs_ketenagakerjaan = (3/100) * $umr_jkt;
        }else{
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }
        $data_update = [
            'nilai' => $bpjs_ketenagakerjaan,
        ];

        BpjsKetenagakerjaanModel::where('id_karyawan', $request->sysid)->update($data_update);

        $msg = 'Data berhasil di ubah';
        return response()->json(['success' => true, 'message' => $msg]);
    }
}
