<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi\KryOfficeHdrModel;
use App\Models\Absensi\KryOfficeDtlModel;
use App\Models\Absensi\Tmp\KryOfficeTmpModel;
use App\Imports\AbsensiKryOffice;
use App\Models\Karyawan\TidakHadirModel;
use App\Models\Master\MasterKaryawanModel;
use Illuminate\Support\Facades\Validator;

use Redirect, Response;
use DateTime;
use DB;
use Excel;

class KryOfficeController extends Controller
{
    public function index()
    {
        // dd(session()->get('sess_username'));
        return view('attendance.absensi-office');
    }

    public function store(Request $request)
    {
        $data_hdr['file_name']      = $request->filename;
        $data_hdr['user_at']        = 'System';

        $data_save_hdr = KryOfficeHdrModel::create($data_hdr);

        // $data_update = [
        //     'id_hdr' => $data_save_hdr->id,
        //     'user_at' => $data_save_hdr->user_at
        // ];
        // KryOfficeDtlModel::where('id_hdr', 0)->update($data_update);

        $nik = $request->nik;
        $nama = $request->nama;
        $tanggal = $request->tanggal;
        $jam_masuk = $request->jam_masuk;
        $jam_keluar = $request->jam_keluar;

        $arrnik = [];
        foreach (array_count_values($nik) as $key => $value) { 
            array_push($arrnik, $key);
        } 
        
        

        $data_kry = DB::table('mst_karyawan')
                    ->whereNull('tanggal_keluar')
                    // ->where('id',1)
                    ->get();
        foreach ($data_kry as $value) {
            $id_kry = $value->id;
            $nrp = $value->nrp;

            // $nik = $request->nik;
            // $nama = $request->nama;
            // $tanggal = $request->tanggal;
            // $jam_masuk = $request->jam_masuk;
            // $jam_keluar = $request->jam_keluar;

            $key = array_search($nrp, $nik);
            $duplicate = array_unique(array_diff_assoc($nik, array_unique($nik)));
            $duplicate_keys = array_keys(array_intersect($nik, $duplicate));


            // $data=array();
            // if(array_key_exists($key,$duplicate_keys) == true){
            //     // for ($i = 0; $i < $duplicate_keys; $i++) {
            //     //         $data_absen['id_hdr']     = $data_save_hdr->id;
            //     //         $data_absen['nik']   = $nik[$i];
            //     //     }
            //     foreach ($duplicate_keys as $value) {
            //         $data_absen['nik']   = $nik[$value];
            //         $data[] = $data_absen;
            //         $status = $this->cek_ketidakhadiran($tanggal[$value], $id_kry);
            //     }

            //         dd($duplicate_keys, $status);
            // }else{
            //     dd('-');
            // }
            
            // dd(array_filter($arrnik, $nik));
            
            if(in_array($nrp, $arrnik)){
                // if(array_key_exists($key,$duplicate_keys) == true){
                //     foreach ($duplicate_keys as $value) {
                //         $data_absen['id_hdr']     = $data_save_hdr->id;
                //         $data_absen['nik']   = $nik[$value];
                //         $data_absen['nama'] = $nama[$value];
                //         $data_absen['tanggal'] = $tanggal[$value];
                //         $data_absen['jam_masuk'] = $jam_masuk[$value];
                //         $data_absen['jam_keluar'] = $jam_keluar[$value];
                //         $data_absen['user_at']     = session()->get('sess_username');

                //         $status = $this->cek_ketidakhadiran($tanggal[$value], $id_kry);
                //         if($status == '-'){
                //             if($jam_masuk[$value] != null || $jam_masuk[$value] !='' ){
                //                 if($jam_keluar[$value] != null || $jam_keluar[$value] != '' ){
                //                     if($jam_masuk[$value] > '08.15'){
                //                         $status = 'Telat';
                //                     }else if($jam_keluar[$value] < '17.00'){
                //                         $status = 'Absen Pulang Dulu';
                //                     }else{
                //                         $status = 'Hadir';
                //                     }
                //                 }else{
                //                     $status = 'Tidak Absen Pulang';
                //                 }
                //             }else if($jam_masuk[$value] == null || $jam_masuk[$value] =='' ){
                //                 if($jam_keluar[$value] != null || $jam_keluar[$value] != '' ){
                //                     $status = 'Tidak Absen Masuk';
                //                 }else{
                //                     $status = 'Tidak Hadir';
                //                 }
                                
                //             }else{
                //                 $status = 'Hadir';
                //             }
                //         }
                        
                //         $data_absen['status'] = $status;
                //         $save_barang = KryOfficeDtlModel::create($data_absen);
                //     }
                // }else{
                //     $data_absen['id_hdr']     = $data_save_hdr->id;
                //     $data_absen['nik']   = $nik[$key];
                //     $data_absen['nama'] = $nama[$key];
                //     $data_absen['tanggal'] = $tanggal[$key];
                //     $data_absen['jam_masuk'] = $jam_masuk[$key];
                //     $data_absen['jam_keluar'] = $jam_keluar[$key];
                //     $data_absen['user_at']     = session()->get('sess_username');

                //     $status = $this->cek_ketidakhadiran($tanggal[$key], $id_kry);
                //     if($status == '-'){
                //         if($jam_masuk[$key] != null || $jam_masuk[$key] !='' ){
                //             if($jam_keluar[$key] != null || $jam_keluar[$key] != '' ){
                //                 if($jam_masuk[$key] > '08.15'){
                //                     $status = 'Telat';
                //                 }else if($jam_keluar[$key] < '17.00'){
                //                     $status = 'Absen Pulang Dulu';
                //                 }else{
                //                     $status = 'Hadir';
                //                 }
                //             }else{
                //                 $status = 'Tidak Absen Pulang';
                //             }
                //         }else if($jam_masuk[$key] == null || $jam_masuk[$key] =='' ){
                //             if($jam_keluar[$key] != null || $jam_keluar[$key] != '' ){
                //                 $status = 'Tidak Absen Masuk';
                //             }else{
                //                 $status = 'Tidak Hadir';
                //             }
                            
                //         }else{
                //             $status = 'Hadir';
                //         }
                //     }
                    
                //     $data_absen['status'] = $status;
                //     $save_barang = KryOfficeDtlModel::create($data_absen);
                // }

                foreach ($duplicate_keys as $value) {
                    if($nik[$value] == $nrp){
                        $data_absen['id_hdr']     = $data_save_hdr->id;
                        $data_absen['nik']   = $nik[$value];
                        $data_absen['nama'] = $nama[$value];
                        $data_absen['tanggal'] = $tanggal[$value];
                        $data_absen['jam_masuk'] = $jam_masuk[$value];
                        $data_absen['jam_keluar'] = $jam_keluar[$value];
                        $data_absen['user_at']     = session()->get('sess_username');

                        $status = $this->cek_ketidakhadiran($tanggal[$value], $id_kry);
                        if($status == '-'){
                            if($jam_masuk[$value] != null || $jam_masuk[$value] !='' ){
                                if($jam_keluar[$value] != null || $jam_keluar[$value] != '' ){
                                    if($jam_masuk[$value] > '08.15'){
                                        $status = 'Telat';
                                    }else if($jam_keluar[$value] < '17.00'){
                                        $status = 'Absen Pulang Dulu';
                                    }else{
                                        $status = 'Hadir';
                                    }
                                }else{
                                    $status = 'Tidak Absen Pulang';
                                }
                            }else if($jam_masuk[$value] == null || $jam_masuk[$value] =='' ){
                                if($jam_keluar[$value] != null || $jam_keluar[$value] != '' ){
                                    $status = 'Tidak Absen Masuk';
                                }else{
                                    $status = 'Tidak Hadir';
                                }
                                
                            }else{
                                $status = 'Hadir';
                            }
                        }
                        
                        $data_absen['status'] = $status;
                        $save_barang = KryOfficeDtlModel::create($data_absen);
                    }
                }
            }else{
                $hari_ini = date("Y-m-d");
                $data_absen['id_hdr']     = $data_save_hdr->id;
                $data_absen['nik'] = $value->nrp;
                $data_absen['nama'] = $value->nama;
                $data_absen['tanggal'] = date('Y-m-d', strtotime($hari_ini.' -1 day'));
                $data_absen['jam_masuk'] = null;
                $data_absen['jam_keluar'] = null;
                $status = $this->cek_ketidakhadiran(date('Y-m-d', strtotime($hari_ini.' -1 day')), $id_kry);

                if($status == '-'){
                    $data_absen['status'] = 'Tidak Hadir';
                }else{
                    $data_absen['status'] = $status;
                }
                // $data_absen['status'] = 'Tidak Hadir';
                $data_absen['user_at']     = session()->get('sess_username');
                $save_barang = KryOfficeDtlModel::create($data_absen);
            }
        }

        // $nik = $request->nik;
        // $nama = $request->nama;
        // $tanggal = $request->tanggal;
        // $jam_masuk = $request->jam_masuk;
        // $jam_keluar = $request->jam_keluar;
        // for ($i = 0; $i < count($nik); $i++) {
        //     $data_absen['id_hdr']     = $data_save_hdr->id;
        //     $data_absen['nik']   = $nik[$i];
        //     $id_kry = $this->cek_karyawan($nik[$i]);
        //     if($id_kry != 0){
        //         $data_absen['nama'] = $nama[$i];
        //         $data_absen['tanggal'] = $tanggal[$i];
        //         $data_absen['jam_masuk'] = $jam_masuk[$i];
        //         $data_absen['jam_keluar'] = $jam_keluar[$i];
        //         $data_absen['user_at']     = session()->get('sess_username');

        //         $status = $this->cek_ketidakhadiran($tanggal[$i], $id_kry);
        //         if($status == '-'){
        //             if($jam_masuk[$i] != null || $jam_masuk[$i] !='' ){
        //                 if($jam_keluar[$i] != null || $jam_keluar[$i] != '' ){
        //                     if($jam_masuk[$i] > '08.15'){
        //                         $status = 'Telat';
        //                     }else if($jam_keluar[$i] < '17.00'){
        //                         $status = 'Absen Pulang Dulu';
        //                     }else{
        //                         $status = 'Hadir';
        //                     }
        //                 }else{
        //                     $status = 'Tidak Absen Pulang';
        //                 }
        //             }else if($jam_masuk[$i] == null || $jam_masuk[$i] =='' ){
        //                 if($jam_keluar[$i] != null || $jam_keluar[$i] != '' ){
        //                     $status = 'Tidak Absen Masuk';
        //                 }else{
        //                     $status = 'Tidak Hadir';
        //                 }
                        
        //             }else{
        //                 $status = 'Hadir';
        //             }
        //         }
                
        //         $data_absen['status'] = $status;
        //     }else{
        //         // Jika karyawan tidak ada di data excel
        //         $data_absen['nama'] = $nama[$i];
        //         $data_absen['tanggal'] = $tanggal[$i];
        //         $data_absen['status'] = 'Tidak Hadir';
        //         $data_absen['user_at']     = session()->get('sess_username');
        //     }
            
            // $save_barang = KryOfficeDtlModel::create($data_absen);
        // }

        KryOfficeTmpModel::where('user_at', session()->get('sess_username'))->delete();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg, 'id_hdr' => $data_save_hdr->id]);
    }

    function cek_karyawan($nrp){
        $data_kry = DB::table('mst_karyawan')
                    ->where('nrp',$nrp)
                    ->first();
        if($data_kry){
            return $data_kry->id;
        }else{
            return 0;
        }
    }

    function cek_ketidakhadiran($tgl, $id_kry)
    {
        $data = DB::table('mst_tidakhadir')
                    ->where('status_approve',3)
                    ->where('id_karyawan',$id_kry)
                    ->where([['mulai','>=',$tgl],['sampai','<=',$tgl]])
                    ->first();
        if($data){
            return $data->jenis_ijin;
        }else{
            return '-';
        } 
        
    }

    public function importFile(Request $request)
    {
        DB::beginTransaction();
        try {
            $path = $request->file('file')->getRealPath();
            $data = Excel::import(new AbsensiKryOffice, $path);
            // $data = Excel::toArray(new AbsensiKryOffice,$path);
            // dd($data);
            // die();

            // // menangkap file excel
            // $file = $request->file('exampleInputFile');

            // // membuat nama file unik
            // $nama_file = rand().$file->getClientOriginalName();

            // // upload ke folder file_siswa di dalam folder public
            // $file->move('file_absensi',$nama_file);

            // // import data
            // Excel::import(new AbsensiImport, public_path('/file_absensi/'.$nama_file));

            $data_tmp = DB::table('qtmp_absensi_office')->where('user_at', session()->get('sess_username'))->get();

            DB::commit();
            $msg = 'Data berhasil di simpan';
            return response()->json(['success' => true, 'message' => $msg, 'data' => $data_tmp]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e]);
        }
    }

    public function update(Request $request)
    {
        $sysid = $request->sysid;
        $jam_masuk = $request->jam_masuk;
        $jam_keluar = $request->jam_keluar;

        // if($jam_masuk > '08.15'){
        //     if($jam_keluar == null || $jam_keluar == '' ){
        //         if($jam_masuk[$i] == null || $jam_masuk[$i] == '' ){
        //             // Cek ke tabel tidak hadir
        //             $status = 'Tidak Hadir';
        //         }else{
        //             $status = 'Tidak Absen Pulang';
        //         }
                
        //     }else{
        //         $status = 'Telat';
        //     }
        // }else if($jam_keluar < '17.00'){
        //     if($jam_masuk == null || $jam_masuk =='' ){
        //         if($jam_keluar[$i] == null || $jam_keluar[$i] == '' ){
        //             // Cek ke tabel tidak hadir
        //             $status = 'Tidak Hadir';
        //         }else{
        //             $status = 'Tidak Absen Masuk';
        //         }
        //     }else{
        //         $status = 'Absen Pulang Dulu';
        //     }
        // }else{
        //     $status = 'Hadir';
        // }

        if($jam_masuk != null || $jam_masuk !='' ){
            if($jam_keluar != null || $jam_keluar != '' ){
                if($jam_masuk > '08.15'){
                    $status = 'Telat';
                }else if($jam_keluar < '17.00'){
                    $status = 'Absen Pulang Dulu';
                }else{
                    $status = 'Hadir';
                }
            }else{
                $status = 'Tidak Absen Pulang';
            }
        }else if($jam_masuk == null || $jam_masuk =='' ){
            if($jam_keluar != null || $jam_keluar != '' ){
                $status = 'Tidak Absen Masuk';
            }else{
                // Cek ke tabel tidak hadir
                $status = 'Tidak Hadir';
            }
            
        }else{
            $status = 'Hadir';
        }

        $data = [
            'jam_masuk' => $jam_masuk,
            'jam_keluar' => $jam_keluar,
            'status' => $status,
            'user_at' => session()->get('sess_username'),
        ];

        DB::beginTransaction();
        try {
            KryOfficeDtlModel::where('id', $sysid)->update($data);

            DB::commit();
            $msg = 'Data berhasil di ubah';
            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e]);
        }
    }

    public function delete_tmp(Request $request)
    {
        KryOfficeTmpModel::where('user_at', session()->get('sess_username'))->delete();
        return response()->json(['success' => true]);
    }

    public function list_data(Request $request)
    {
        $dataDB = DB::table('trx_absen_office_hdr')
                ->get();
        $data = array();
        foreach ($dataDB as $value) {
            $nestedData['id'] = $value->id;
            $nestedData['file_name'] = $value->file_name;
            $nestedData['user_at'] = $value->user_at;
            $nestedData['created_at'] = $value->created_at;
            $data[] = $nestedData;
        }
        return $data;
    }

    public function list_data_hdr(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'nik',
            3 => 'created_at'
        );

        $id_hdr = $request->id_hdr;
        $totalData = DB::table('trx_absen_office')
                    ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                    ->where('trx_absen_office_hdr.id',$id_hdr)
                    ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_absen_office')
                ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                ->selectraw('trx_absen_office.*, trx_absen_office_hdr.file_name')
                ->where('trx_absen_office_hdr.id',$id_hdr)
                ->offset($start)
                ->limit($limit)
                ->orderBy('nik')
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_absen_office')
                ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                ->selectraw('trx_absen_office.*, trx_absen_office_hdr.file_name')
                ->offset($start)
                ->limit($limit)
                ->where([['trx_absen_office_hdr.id',$id_hdr],['nik', 'LIKE', "%{$search}%"]])
                ->orWhere([['trx_absen_office_hdr.id',$id_hdr],['nama', 'LIKE', "%{$search}%"]])
                ->orderBy('nik')
                ->get();

            $totalFiltered = DB::table('trx_absen_office')
                ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                ->where([['trx_absen_office_hdr.id',$id_hdr],['nik', 'LIKE', "%{$search}%"]])
                ->orWhere([['trx_absen_office_hdr.id',$id_hdr],['nama', 'LIKE', "%{$search}%"]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nik'] = $post->nik;
                $nestedData['nama'] = $post->nama;
                $nestedData['tanggal'] = $post->tanggal;
                $nestedData['jam_masuk'] = $post->jam_masuk;
                $nestedData['jam_keluar'] = $post->jam_keluar;
                $nestedData['status'] = $post->status;
                $nestedData['file_name'] = $post->file_name;
                $nestedData['statusabsensi'] = $post->status;

                if(!$post->jam_masuk && !$post->jam_keluar){
                    if($post->status == 'Tidak Hadir'){
                        $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='i_ketidakhadiran' data-toggle='tooltip' title='Input Ketidakhadiran' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp;Input Ketidakhadiran </a>
                    <a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Input Absensi' data-id='$post->id' data-original-title='' class='Edit btn btn-success btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp;Input Absensi </a>";
                    }else{
                        $nestedData['action'] = "-";
                    }
                    
                }else if(!$post->jam_masuk){
                    $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Input Absensi' data-id='$post->id' data-original-title='' class='Edit btn btn-success btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp;Input Absensi </a>";
                }else if(!$post->jam_keluar){
                    $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Input Absensi' data-id='$post->id' data-original-title='' class='Edit btn btn-success btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp;Input Absensi </a>";
                }else{
                    $nestedData['action'] = "-";
                }

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

    public function list_data_dtl(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'nik',
            3 => 'nama',
            4 => 'tanggal',
            5 => 'status'
        );

        $id_hdr = $request->id_hdr;
        $totalData = DB::table('trx_absen')
            ->where([['id_hdr', '=', $id_hdr]])
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_absen')
                ->offset($start)
                ->limit($limit)
                ->where([['id_hdr', '=', $id_hdr]])
                ->orderBy('nik')
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_absen')
                ->offset($start)
                ->limit($limit)
                ->where([['id_hdr', '=', $id_hdr], ['nik', 'LIKE', "%{$search}%"]])
                ->orWhere([['id_hdr', '=', $id_hdr], ['nama', 'LIKE', "%{$search}%"]])
                ->orderBy('nik')
                ->get();

            $totalFiltered = DB::table('trx_absen')
                ->where([['id_hdr', '=', $id_hdr], ['nik', 'LIKE', "%{$search}%"]])
                ->orWhere([['id_hdr', '=', $id_hdr], ['nama', 'LIKE', "%{$search}%"]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nik'] = $post->nik;
                $nestedData['nama'] = $post->nama;
                $nestedData['tanggal'] = $post->tanggal;
                $nestedData['status'] = $post->status;
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

    public function get_data_tmp(Request $request)
    {
        $data = DB::table('qtmp_absensi_office')
            ->where('user_at', session()->get('sess_username'))
            // ->orderBy('selisih_jam')
            // ->orderBy('nik')
            ->orderBy(DB::raw('ISNULL(jam_masuk), tgl_masuk'), 'ASC')
            ->orderBy(DB::raw('ISNULL(jam_keluar), tgl_masuk'), 'ASC')
            ->get();
        return response()->json($data);
    }

    public function store_tidakhadir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file'          => 'required|mimes:jpg,jpeg,bmp,png,pdf|max:1024'
        ],[
            'file.required' => 'File harus diisi',
            'file.mimes'    => 'Format file tidak sesuai',
            'file.max'      => 'File tidak bisa lebih besar dari 1MB',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['success' => false, 'message' => $error]);
        }
        $data =  new TidakHadirModel();

        // no dokumen reset per tahun
        $year = date('Y');
        $month = date('m');
        if($request->kategori == 'Cuti'){
            $text = 'C-';
        }else if($request->kategori == 'Izin'){
            $text = 'I-';
        }else if($request->kategori == 'Sakit'){
            $text = 'S-';
        }else{
            $text = 'N-';
        }
        $no = TidakHadirModel::where('jenis_ijin',$request->kategori)->where('no_dokumen', 'LIKE', $text.substr($year,-2)."%")->count();
        $docno = $text.substr($year,-2).$month.substr('0000'.$no+1,-4);
        // dd($docno);

        $data_kry = MasterKaryawanModel::where('nrp',$request->nrp)->first();
        $data->id_karyawan = $data_kry->id ?? 0;
        $data->id_devisi = $data_kry->id_devisi ?? 0;
        $data->no_dokumen = $docno;
        $data->jenis_ijin = $request->kategori;
        $data->mulai = $request->mulai;
        $data->sampai = $request->sampai;

        // Perhitungan Jumlah Hari //
        $tgl_mulai = $request->mulai;
        $tgl_sampai = $request->sampai;
        $datetime1 = new DateTime($tgl_mulai);
        $datetime2 = new DateTime($tgl_sampai);
        $interval = $datetime1->diff($datetime2);
        $jumlah_hari = $interval->format('%a'); //now do whatever you like with $days
        $data->jumlah_hari = floatval($jumlah_hari) + 1;

        $file_upload    = $request->file('file');
        $fileName       = $text . uniqid() . '.' . $file_upload->getClientOriginalExtension();
        $file_upload->move(public_path('/file_upload/'), $fileName);
        $data->filename = $fileName;
        $data->keterangan = $request->keterangan;
        $data->status = 1;
        $data->status_approve = 3;
        $data->user_at = session()->get('sess_username');
        $data->save();

        $data = [
            'status' => $request->kategori,
            'user_at' => session()->get('sess_username'),
        ];
        $sysid = $request->sysid;
        KryOfficeDtlModel::where('id', $sysid)->update($data);

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }
}
