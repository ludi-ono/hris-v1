<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Payroll\PresensiModelHdr;
use App\Models\Payroll\PresensiModelDtl;
use App\Models\Payroll\PresensiModelTmp;

use DateTime;
use DB;

class PresensiController extends Controller
{
    public function index()
    {
        return view('payroll.generate-presensi');
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

        $totalData = DB::table('trx_presensi_hdr')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_presensi_hdr')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_presensi_hdr')
                ->offset($start)
                ->limit($limit)
                ->where('no_dokumen', 'LIKE', "%{$search}%")
                ->orWhere('tahun', 'LIKE', "%{$search}%")
                ->orWhere('bulan', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_presensi_hdr')
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
            0 => 'trx_presensi_dtl.id',
            1 => 'DT_RowIndex',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            4 => 'trx_presensi_dtl.presensi',
        );

        $id_hdr = $request->id_hdr;
        $totalData = DB::table('trx_presensi_dtl')
            ->join('mst_karyawan', 'trx_presensi_dtl.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->where('trx_presensi_dtl.id_hdr', $id_hdr)
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_presensi_dtl')
                ->join('mst_karyawan', 'trx_presensi_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('trx_presensi_dtl. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->offset($start)
                ->limit($limit)
                ->where('trx_presensi_dtl.id_hdr', $id_hdr)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_presensi_dtl')
                ->join('mst_karyawan', 'trx_presensi_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('trx_presensi_dtl. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->where('trx_presensi_dtl.id_hdr', $id_hdr)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_presensi_dtl.id_hdr', $id_hdr]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_presensi_dtl.id_hdr', $id_hdr]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_presensi_dtl')
                ->join('mst_karyawan', 'trx_presensi_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_presensi_dtl.id_hdr', $id_hdr]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_presensi_dtl.id_hdr', $id_hdr]])
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
                $nestedData['presensi'] = $post->presensi;
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
            0 => 'tmp_presensi.id',
            1 => 'DT_RowIndex',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            4 => 'tmp_presensi.presensi',
        );

        $user = session()->get('sess_username');
        $totalData = DB::table('tmp_presensi')
            ->join('mst_karyawan', 'tmp_presensi.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->where('tmp_presensi.user_at', $user)
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('tmp_presensi')
                ->join('mst_karyawan', 'tmp_presensi.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('tmp_presensi. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->offset($start)
                ->limit($limit)
                ->where('tmp_presensi.user_at', $user)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('tmp_presensi')
                ->join('mst_karyawan', 'tmp_presensi.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('tmp_presensi. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->where('tmp_presensi.user_at', $user)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['tmp_presensi.user_at', $user]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['tmp_presensi.user_at', $user]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('tmp_presensi')
                ->join('mst_karyawan', 'tmp_presensi.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['tmp_presensi.user_at', $user]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['tmp_presensi.user_at', $user]])
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
                $nestedData['presensi'] = $post->presensi;
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

    public function store(Request $request)
    {
        $date = new DateTime;
        $data =  new PresensiModelHdr();
        $user = session()->get('sess_username');

        // no dokumen reset per tahun
        $year = date('Y');
        $month = date('m');
        $no = PresensiModelHdr::where('no_dokumen', 'LIKE', 'PRS-'.substr($year,-2)."%")->count();
        $docno = 'PRS-'.substr($year,-2).$month.substr('0000'.$no+1,-4);

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
        $insert_dtl = DB::insert("INSERT INTO trx_presensi_dtl (id_hdr,id_karyawan,jml_masuk,jml_telat,jml_cuti,jml_izin,jml_sakit,presensi,status,user_at,created_at,updated_at) SELECT ".$id_hdr.",id_karyawan,jml_masuk,jml_telat,jml_cuti,jml_izin,jml_sakit,presensi,0,'".$user."','".date('Y-m-d H:m:s')."','".date('Y-m-d H:m:s')."' FROM tmp_presensi WHERE tmp_presensi.user_at='".$user."'");

        PresensiModelTmp::where('user_at', $user)->delete();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    // public function generate($id_hdr=0, $tahun=0, $bulan=0)
    public function generate(Request $request)
    {
        $date = new DateTime;

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
        // $dari = '2023-02-02 00:00:00';
        // $sampai = '2023-02-24 00:00:00';
        $data_kry = DB::table('mst_karyawan')
                        ->wherenull('tanggal_keluar')
                        ->orwhere('tanggal_keluar', '0000-00-00')
                        ->get();
        
        // dd($data_kry);
        foreach ($data_kry as $val) {
            $id_kry = $val->id;
            $nrp = $val->nrp;
            $presensi = $val->presensi;
            $presensi_perhari = floatval($presensi) / floatval($hari_kerja);
            $masuk = 0;
            $telat = 0;
            $cuti = 0;
            $izin = 0;
            $sakit = 0;

            $data = DB::table('trx_absen_office')
                    ->whereBetween('tanggal',[$dari,$sampai])
                    ->where('nik', $nrp)
                    ->get();
            foreach ($data as $value) {
                $status = $value->status;
                if($status == 'Hadir'){
                    $masuk = $masuk + 1;
                }else if($status == 'Telat'){
                    $telat = $telat + 1;
                }else if($status == 'Cuti'){
                    $cuti = $cuti + 1;
                }else if($status == 'Izin'){
                    $izin = $izin + 1;
                }else if($status == 'Sakit'){
                    $sakit = $sakit + 1;
                }
            }

            $data =  new PresensiModelTmp();
            $data->id_karyawan = $id_kry;
            $data->jml_masuk = $masuk;
            $data->jml_telat = $telat;
            $data->jml_cuti = $cuti;
            $data->jml_izin = $izin;
            $data->jml_sakit = $sakit;

            $presensi_masuk = round(floatval($presensi_perhari) * $masuk);
            $presensi_telat = round((floatval($presensi_perhari) * 0.5) * $telat);
// dd($presensi_masuk.' = '.$presensi_telat.'->'.$presensi_perhari.'->'.$presensi.'+'.$hari_kerja);
            $data->presensi = $presensi_masuk + $presensi_telat;
            $data->status = $request->status;
            $data->user_at = session()->get('sess_username');
            $data->created_at = $date;
            $data->save();
        }

        $msg = 'Data berhasil di generate';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    // public function update(Request $request)
    // {
    //     $date = new DateTime;
    //     $data = [
    //         'nama' => $request->nama,
    //         'id_perusahaan' => $request->perusahaan,
    //     ];

    //     MasterDevisiModel::where('id', $request->sysid)->update($data);
    //     $msg = 'Data berhasil di ubah';
    //     return response()->json(['success' => true, 'message' => $msg]);
    // }

    public function destroy($id)
    {
        PresensiModelHdr::where('id', $id)->delete();
        PresensiModelDtl::where('id_hdr', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function destroy_dtl($id)
    {
        PresensiModelDtl::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function destroy_tmp()
    {
        $user = session()->get('sess_username');
        PresensiModelTmp::where('user_at', $user)->delete();
        return response()->json(['success' => true]);
    }

    // function perusahaan()
    // {
    //     $query = DB::table('mst_perusahaan')->get();
    //     return response()->json(['data' => $query]);
    // }
}
