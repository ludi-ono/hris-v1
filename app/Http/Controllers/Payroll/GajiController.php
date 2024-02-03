<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Payroll\LemburModelHdr;
use App\Models\Payroll\PresensiModelHdr;
use App\Models\Payroll\GajiModelHdr;
use App\Models\Payroll\GajiModelDtl;
use App\Models\Payroll\GajiModelTmp;
use DateTime;
use DB;

class GajiController extends Controller
{
    public function index()
    {
        return view('payroll.generate-gaji');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'no_dokumen',
            3 => 'tgl_dokumen',
            4 => 'tahun',
            5 => 'bulan',
            6 => 'status',
        );

        $totalData = DB::table('trx_gaji_hdr')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_gaji_hdr')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_gaji_hdr')
                ->offset($start)
                ->limit($limit)
                ->where('no_dokumen', 'LIKE', "%{$search}%")
                ->orWhere('tahun', 'LIKE', "%{$search}%")
                ->orWhere('bulan', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_gaji_hdr')
                ->where('no_dokumen', 'LIKE', "%{$search}%")
                ->orWhere('tahun', 'LIKE', "%{$search}%")
                ->orWhere('bulan', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['no_dokumen'] = $post->no_dokumen;
                $nestedData['tgl_dokumen'] = $post->tgl_dokumen;
                $nestedData['tahun'] = $post->tahun;
                $nestedData['bulan'] = $post->bulan;
                $nestedData['status'] = $post->status;

                if($post->status == 0){
                    $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->id' data-original-title='' class='Edit btn btn-info btn-sm'><i class='fas fa-info'></i> &nbsp; Detail </a>
                                            <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
                }else{
                    $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->id' data-original-title='' class='Edit btn btn-info btn-sm'><i class='fas fa-info'></i> &nbsp; Detail </a>";
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
            0 => 'mst_karyawan.nrp',
            1 => 'DT_RowIndex',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            // 4 => 'trx_lembur_dtl.presensi',
        );

        $id_hdr = $request->id_hdr;
        $totalData = DB::table('trx_gaji_dtl')
            ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_gaji_dtl.nilai_gaji) as gaji')
            ->where('trx_gaji_dtl.id_hdr', $id_hdr)
            ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_gaji_dtl')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_gaji_dtl.nilai_gaji) as gaji')
                ->offset($start)
                ->limit($limit)
                ->where('trx_gaji_dtl.id_hdr', $id_hdr)
                ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_gaji_dtl')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_gaji_dtl.nilai_gaji) as gaji')
                ->where('trx_gaji_dtl.id_hdr', $id_hdr)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_gaji_dtl.id_hdr', $id_hdr]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_gaji_dtl.id_hdr', $id_hdr]])
                ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_gaji_dtl')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_gaji_dtl.nilai_gaji) as gaji')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_gaji_dtl.id_hdr', $id_hdr]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_gaji_dtl.id_hdr', $id_hdr]])
                ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->nrp;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nama'] = $post->nama;
                $nestedData['devisi'] = $post->devisi;
                $nestedData['nominal'] = $post->gaji;
                // $nestedData['bulan'] = $post->bulan;
                // $nestedData['status'] = $post->status;
                // $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-eye'></i> &nbsp; Detail </a>
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

    public function list_data_tmp(Request $request)
    {
        $columns = array(
            0 => 'tmp_gaji.id',
            1 => 'DT_RowIndex',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            4 => 'tmp_gaji.nominal_lembur_hk',
        );

        $user = session()->get('sess_username');
        $totalData = DB::table('tmp_gaji')
            ->join('mst_karyawan', 'tmp_gaji.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->where('tmp_gaji.user_at', $user)
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('tmp_gaji')
                ->join('mst_karyawan', 'tmp_gaji.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('tmp_gaji. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->offset($start)
                ->limit($limit)
                ->where('tmp_gaji.user_at', $user)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('tmp_gaji')
                ->join('mst_karyawan', 'tmp_gaji.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('tmp_gaji. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->where('tmp_gaji.user_at', $user)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['tmp_gaji.user_at', $user]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['tmp_gaji.user_at', $user]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('tmp_gaji')
                ->join('mst_karyawan', 'tmp_gaji.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['tmp_gaji.user_at', $user]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['tmp_gaji.user_at', $user]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nama'] = $post->nama;
                $nestedData['devisi'] = $post->devisi;
                $nestedData['nominal'] = $post->nilai_gaji;
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
        $data =  new GajiModelHdr();
        $user = session()->get('sess_username');
        $id_presensi = $request->presensi;
        $id_lembur = $request->lembur;

        // no dokumen reset per tahun
        $year = date('Y');
        $month = date('m');
        $no = GajiModelHdr::where('no_dokumen', 'LIKE', 'GJI-'.substr($year,-2)."%")->count();
        $docno = 'GJI-'.substr($year,-2).$month.substr('0000'.$no+1,-4);

        $data->id_gen_presensi = $id_presensi;
        $data->id_gen_lembur = $id_lembur;
        $data->no_dokumen = $docno;
        $data->tgl_dokumen = date('Y-m-d');
        $data->tahun = $request->tahun;
        $data->bulan = $request->bulan;
        $data->status = 0;
        $data->user_at = $user;
        $data->created_at = $date;
        $data->save();

        $id_hdr = $data->id;
        // $this->generate(1,2023,1);
        $insert_dtl = DB::insert("INSERT INTO trx_gaji_dtl (id_hdr,id_gen_presensi_dtl,id_gen_lembur_dtl,id_karyawan,total_hari_kerja,total_hari_masuk_kry,total_hari_cuti_kry,total_hari_sakit_kry,total_hari_izin_kry,total_hari_lembur_kry,total_jam_lembur_kry,nilai_gaji,user_at,created_at,updated_at) SELECT ".$id_hdr.",id_gen_presensi_dtl,id_gen_lembur_dtl,id_karyawan,total_hari_kerja,total_hari_masuk_kry,total_hari_cuti_kry,total_hari_sakit_kry,total_hari_izin_kry,total_hari_lembur_kry,total_jam_lembur_kry,nilai_gaji,'".$user."','".date('Y-m-d H:m:s')."','".date('Y-m-d H:m:s')."' FROM tmp_gaji WHERE tmp_gaji.user_at='".$user."'");

        GajiModelTmp::where('user_at', $user)->delete();
        PresensiModelHdr::where('id',$id_presensi)->update(['status' => 1]);
        LemburModelHdr::where('id',$id_lembur)->update(['status' => 1]);

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function generate(Request $request)
    {
        $date = new DateTime;
        $id_presensi = $request->id_presensi;
        $id_lembur = $request->id_lembur;

        if($request->bulan == 1){
            $dari = date('Y-m-d H:i:s', strtotime(floatval($request->tahun) - 1 . '-12-22'));
            $sampai = date('Y-m-d H:i:s', strtotime($request->tahun.'-'.substr('0'.$request->bulan,-2).'-21'));
        }else{
            $dari = date('Y-m-d H:i:s', strtotime($request->tahun .'-'. substr('0'.floatval($request->bulan)-1,-2) . '-22'));
            $sampai = date('Y-m-d H:i:s', strtotime($request->tahun.'-'.substr('0'.$request->bulan,-2).'-21'));
        }

        $earlier = new DateTime($dari);
        $later = new DateTime($sampai);

        $jumlah_hari = $later->diff($earlier)->format("%a"); //3
        $tanggal = $dari;
        $hari_libur = 0;
        for($i=$jumlah_hari;$i>=1;$i--)
        {
            $tanggal = date("Y-m-d",strtotime("+1 days",strtotime($tanggal)));
            $hari = date('l',strtotime($tanggal));
            if($hari =='Saturday' OR $hari =='Sunday')
            {
                // $i = $i + 1;
                $hari_libur = $hari_libur + 1;
                continue;
            }
        }
        $hari_kerja = $jumlah_hari - $hari_libur;

        $data_kry = DB::table('mst_karyawan')
                        ->wherenull('tanggal_keluar')
                        ->orwhere('tanggal_keluar', '0000-00-00')
                        ->get();
        foreach ($data_kry as $val) {
            $id_kry = $val->id;
            $nrp = $val->nrp;

            $d_presensi = DB::table('trx_presensi_hdr')
                            ->join('trx_presensi_dtl', 'trx_presensi_hdr.id', '=', 'trx_presensi_dtl.id_hdr')
                            ->where('trx_presensi_hdr.id',$id_presensi)
                            ->where('trx_presensi_dtl.id_karyawan',$id_kry)
                            ->first();
            $id_gen_presensi = $d_presensi->id ?? 0;
            $presensi = $d_presensi->presensi ?? 0;
            $d_lembur = DB::table('trx_lembur_hdr')
                            ->join('trx_lembur_dtl', 'trx_lembur_hdr.id', '=', 'trx_lembur_dtl.id_hdr')
                            ->where('trx_lembur_hdr.id',$id_lembur)
                            ->where('trx_lembur_dtl.id_karyawan',$id_kry)
                            ->first();
            $id_gen_lembur = $d_lembur->id ?? 0;
            $lembur_hk = $d_lembur->nominal_lembur_hk ?? 0;
            $lembur_hl = $d_lembur->nominal_lembur_hl ?? 0;
            $lembur = $lembur_hk + $lembur_hl;

            $gapok = floatval($val->gaji_pokok);
            $tunjangan = floatval($val->tunjangan_pulsa??0) + floatval($val->tunjangan_jabatan??0);

            $data_ins =  new GajiModelTmp();
            $data_ins->id_karyawan = $id_kry;
            $data_ins->id_gen_presensi_dtl = $id_gen_presensi;
            $data_ins->id_gen_lembur_dtl = $id_gen_lembur;
            $data_ins->total_hari_kerja = $hari_kerja;
            $data_ins->total_hari_masuk_kry = $this->get_hadir($id_kry, $dari, $sampai);
            // $data_ins->total_hari_cuti_kry = round($nominal_lembur,2);
            // $data_ins->total_hari_sakit_kry = $total_jam_lembur;
            // $data_ins->total_hari_izin_kry = $total_jam_lembur;
            // $data_ins->total_hari_lembur_kry = $total_jam_lembur;
            // $data_ins->total_jam_lembur_kry = $total_jam_lembur;
            $data_ins->nilai_gaji = round($gapok + $tunjangan + $presensi + $lembur,2);
            $data_ins->user_at = session()->get('sess_username');
            $data_ins->created_at = $date;
            $data_ins->save();
            // dd($this->)
        }
        $msg = 'Data berhasil di generate';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    function get_cuti($id_kry, $tgl_dari, $tgl_sampai)
    {
        $data = DB::table('mst_tidakhadir')
                    ->where('jenis_ijin','Cuti')
                    ->whereBetween('mulai',[$tgl_dari,$tgl_sampai])
                    ->get();
        return $data;
    }

    function get_sakit($id_kry, $tgl_dari, $tgl_sampai)
    {
        $data = DB::table('trx_absen_office')
                    ->where('status','Sakit')
                    ->whereBetween('tanggal',[$tgl_dari,$tgl_sampai])
                    ->sum('nik');
        return $data;
    }

    function get_izin($id_kry, $tgl_dari, $tgl_sampai)
    {
        $data = DB::table('trx_absen_office')
                    ->where('status','Izin')
                    ->whereBetween('tanggal',[$tgl_dari,$tgl_sampai])
                    ->sum('nik');
        return $data;
    }

    function get_hadir($id_kry, $tgl_dari, $tgl_sampai)
    {
        $data = DB::table('trx_absen_office')
                    ->where('status','Hadir')
                    ->whereBetween('tanggal',[$tgl_dari,$tgl_sampai])
                    ->sum('nik');
        return $data;
    }

    public function destroy($id)
    {
        $data = GajiModelHdr::where('id', $id)->first();
        PresensiModelHdr::where('id',$data->id_gen_presensi)->update(['status' => 0]);
        LemburModelHdr::where('id',$data->id_gen_lembur)->update(['status' => 0]);

        GajiModelHdr::where('id', $id)->delete();
        GajiModelDtl::where('id_hdr', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function destroy_dtl($id)
    {
        GajiModelDtl::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function destroy_tmp()
    {
        $user = session()->get('sess_username');
        GajiModelTmp::where('user_at', $user)->delete();
        return response()->json(['success' => true]);
    }

    public function get_data_presensi(Request $request)
    {
        $id = $request->id;
        $data = DB::table('trx_presensi_hdr')
                    ->where('status',0)
                    ->get();
        return response()->json($data);
    }

    public function get_data_lembur(Request $request)
    {
        $id = $request->id;
        $data = DB::table('trx_lembur_hdr')
                    ->where('status',0)
                    ->get();
        return response()->json($data);
    }
}
