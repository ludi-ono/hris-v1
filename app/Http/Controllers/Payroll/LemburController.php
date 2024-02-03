<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Payroll\LemburModelHdr;
use App\Models\Payroll\LemburModelDtl;
use App\Models\Payroll\LemburModelTmp;

use DateTime;
use DB;

class LemburController extends Controller
{
    public function index()
    {
        return view('payroll.generate-lembur');
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

        $totalData = DB::table('trx_lembur_hdr')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_lembur_hdr')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_lembur_hdr')
                ->offset($start)
                ->limit($limit)
                ->where('no_dokumen', 'LIKE', "%{$search}%")
                ->orWhere('tahun', 'LIKE', "%{$search}%")
                ->orWhere('bulan', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_lembur_hdr')
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
        $totalData = DB::table('trx_lembur_dtl')
            ->join('mst_karyawan', 'trx_lembur_dtl.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_lembur_dtl.nominal_lembur_hk) as lembur_hk, sum(trx_lembur_dtl.nominal_lembur_hl) as lembur_hl')
            ->where('trx_lembur_dtl.id_hdr', $id_hdr)
            ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_lembur_dtl')
                ->join('mst_karyawan', 'trx_lembur_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_lembur_dtl.nominal_lembur_hk) as lembur_hk, sum(trx_lembur_dtl.nominal_lembur_hl) as lembur_hl')
                ->offset($start)
                ->limit($limit)
                ->where('trx_lembur_dtl.id_hdr', $id_hdr)
                ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_lembur_dtl')
                ->join('mst_karyawan', 'trx_lembur_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_lembur_dtl.nominal_lembur_hk) as lembur_hk, sum(trx_lembur_dtl.nominal_lembur_hl) as lembur_hl')
                ->where('trx_lembur_dtl.id_hdr', $id_hdr)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_lembur_dtl.id_hdr', $id_hdr]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_lembur_dtl.id_hdr', $id_hdr]])
                ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_lembur_dtl')
                ->join('mst_karyawan', 'trx_lembur_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, sum(trx_lembur_dtl.nominal_lembur_hk) as lembur_hk, sum(trx_lembur_dtl.nominal_lembur_hl) as lembur_hl')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_lembur_dtl.id_hdr', $id_hdr]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_lembur_dtl.id_hdr', $id_hdr]])
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
                $nestedData['nominal'] = $post->lembur_hk + $post->lembur_hl;
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
            0 => 'tmp_lembur.id',
            1 => 'DT_RowIndex',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            4 => 'tmp_lembur.nominal_lembur_hk',
        );

        $user = session()->get('sess_username');
        $totalData = DB::table('tmp_lembur')
            ->join('mst_karyawan', 'tmp_lembur.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->where('tmp_lembur.user_at', $user)
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('tmp_lembur')
                ->join('mst_karyawan', 'tmp_lembur.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('tmp_lembur. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->offset($start)
                ->limit($limit)
                ->where('tmp_lembur.user_at', $user)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('tmp_lembur')
                ->join('mst_karyawan', 'tmp_lembur.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('tmp_lembur. *, mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi')
                ->where('tmp_lembur.user_at', $user)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['tmp_lembur.user_at', $user]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['tmp_lembur.user_at', $user]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('tmp_lembur')
                ->join('mst_karyawan', 'tmp_lembur.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['tmp_lembur.user_at', $user]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['tmp_lembur.user_at', $user]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $lembur_hk = $post->nominal_lembur_hk ?? 0;
                $lembur_hl = $post->nominal_lembur_hl ?? 0;
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nama'] = $post->nama;
                $nestedData['devisi'] = $post->devisi;
                $nestedData['tanggal'] = $post->tanggal;
                $nestedData['nominal'] = $lembur_hk + $lembur_hl;
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
        $data =  new LemburModelHdr();
        $user = session()->get('sess_username');

        // no dokumen reset per tahun
        $year = date('Y');
        $month = date('m');
        $no = LemburModelHdr::where('no_dokumen', 'LIKE', 'LBR-'.substr($year,-2)."%")->count();
        $docno = 'LBR-'.substr($year,-2).$month.substr('0000'.$no+1,-4);

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
        $insert_dtl = DB::insert("INSERT INTO trx_lembur_dtl (id_hdr,id_karyawan,tanggal,jam_lembur_hk,jam_lembur_hl,nominal_lembur_hk,nominal_lembur_hl,user_at,created_at,updated_at) SELECT ".$id_hdr.",id_karyawan,tanggal,jam_lembur_hk,jam_lembur_hl,nominal_lembur_hk,nominal_lembur_hl,'".$user."','".date('Y-m-d H:m:s')."','".date('Y-m-d H:m:s')."' FROM tmp_lembur WHERE tmp_lembur.user_at='".$user."'");

        LemburModelTmp::where('user_at', $user)->delete();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

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

        $data_kry = DB::table('mst_karyawan')
                        ->wherenull('tanggal_keluar')
                        ->orwhere('tanggal_keluar', '0000-00-00')
                        ->get();
        foreach ($data_kry as $val) {
            $id_kry = $val->id;
            $nrp = $val->nrp;
            $presensi = $val->presensi;
            $gapok = $val->gaji_pokok;
            $tunjangan = floatval($val->tunjangan_pulsa??0) + floatval($val->tunjangan_jabatan??0);
            $thp = floatval($gapok) + floatval($presensi) + floatval($tunjangan);
            $updah_perjam = round((75/100) * $thp * (1/173),2);
            // dd($updah_perjam);
            $presensi_perhari = floatval($presensi) / floatval($hari_kerja);
            $nominal = 0;
            $nominal_lembur = 0;
            $cuti = 0;
            $izin = 0;
            $sakit = 0;

            $data = DB::table('trx_absen_office')
                    ->whereBetween('tanggal',[$dari,$sampai])
                    ->where([['nik', $nrp],['status','Hadir']])
                    ->get();
            foreach ($data as $value) {
                $tgl_absen = $value->tanggal;
                $jam_masuk = date('Y-m-d H:i',strtotime($value->jam_masuk));
                $jam_keluar = date('Y-m-d H:i',strtotime($value->jam_keluar));

                $new_jam_masuk = new DateTime($jam_masuk);
                $new_jam_keluar = new DateTime($jam_keluar);
                
                

                $jamk = date('H',strtotime($jam_keluar));
                $menitk = date('i',strtotime($jam_keluar));
                // dd($jam.' - '.$menit.' - '.$tgl_absen.' - '.$id_kry);
                $data_spl = DB::table('mst_spl')
                                ->where([['id_karyawan',$id_kry],['tgl_lembur',$tgl_absen]])
                                ->first();
                if($data_spl){
                    $hari = date('l',strtotime($tgl_absen));

                    if($hari =='Saturday' OR $hari =='Sunday'){
                        $selisih = $new_jam_masuk->diff($new_jam_keluar);
                        $selisih_jam = floatval($selisih->format('%H'));
                        $selisih_menit = $selisih->format('%i');
                        
                        $jam_lembur = floatval($selisih_jam);
                        $menit_to_jam = floatval($selisih_menit) / 60;
                        $total_jam_lembur = round($jam_lembur,2) + round($menit_to_jam, 2);
                        // dd($tgl_absen.' - '.$total_jam_lembur);
                        if($total_jam_lembur <= 8){
                            $nominal = $total_jam_lembur * (2 * $updah_perjam);
                            $nominal_lembur = $nominal_lembur + $nominal;
                            $data =  new LemburModelTmp();
                            $data->id_karyawan = $id_kry;
                            $data->tanggal = $tgl_absen;
                            $data->jam_lembur_hl = $total_jam_lembur;
                            $data->nominal_lembur_hl = round($nominal_lembur,2);
                            $data->user_at = session()->get('sess_username');
                            $data->created_at = $date;
                            $data->save();
                        }elseif($total_jam_lembur > 8 && $jamk <= 19){
                            $total_jam_lembur = $total_jam_lembur - 1;
                            if($total_jam_lembur >= 9 && $total_jam_lembur <= 10){
                                for ($x = 1; $x <= $jam_lembur - 1; $x++) {
                                    if($x == 1){
                                        $nominal = 8 * (2 * $updah_perjam);
                                        $x = 8;
                                    }elseif($x == 9){
                                        $nominal = 1 * (3 * $updah_perjam);
                                    }else{
                                        $nominal = 1 * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                if($menit_to_jam > 0){
                                    if($total_jam_lembur <= 9){
                                        $nominal = $menit_to_jam * (3 * $updah_perjam);
                                    }elseif($total_jam_lembur <= 10){
                                        $nominal = $menit_to_jam * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                $data =  new LemburModelTmp();
                                $data->id_karyawan = $id_kry;
                                $data->tanggal = $tgl_absen;
                                $data->jam_lembur_hl = $total_jam_lembur;
                                $data->nominal_lembur_hl = round($nominal_lembur,2);
                                $data->user_at = session()->get('sess_username');
                                $data->created_at = $date;
                                $data->save();
                            }elseif($total_jam_lembur >= 10){
                                for ($x = 1; $x <= $total_jam_lembur; $x++) {
                                    if($x == 1){
                                        $nominal = 8 * (2 * $updah_perjam);
                                        $x = 8;
                                    }elseif($x == 9){
                                        $nominal = 1 * (3 * $updah_perjam);
                                    }else{
                                        $nominal = 1 * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                if($menit_to_jam > 0){
                                    if($total_jam_lembur <= 9){
                                        $nominal = $menit_to_jam * (3 * $updah_perjam);
                                    }elseif($total_jam_lembur <= 10){
                                        $nominal = $menit_to_jam * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                $data =  new LemburModelTmp();
                                $data->id_karyawan = $id_kry;
                                $data->tanggal = $tgl_absen;
                                $data->jam_lembur_hl = $total_jam_lembur;
                                $data->nominal_lembur_hl = round($nominal_lembur,2);
                                $data->user_at = session()->get('sess_username');
                                $data->created_at = $date;
                                $data->save();
                            }
                        }elseif($total_jam_lembur > 8 && $jamk > 19){
                            $total_jam_lembur = $total_jam_lembur - 2;
                            if($total_jam_lembur >= 9 && $total_jam_lembur <= 10){
                                for ($x = 1; $x <= $jam_lembur - 1; $x++) {
                                    if($x == 1){
                                        $nominal = 8 * (2 * $updah_perjam);
                                        $x = 8;
                                    }elseif($x == 9){
                                        $nominal = 1 * (3 * $updah_perjam);
                                    }else{
                                        $nominal = 1 * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                if($menit_to_jam > 0){
                                    if($total_jam_lembur <= 9){
                                        $nominal = $menit_to_jam * (3 * $updah_perjam);
                                    }elseif($total_jam_lembur <= 10){
                                        $nominal = $menit_to_jam * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                $data =  new LemburModelTmp();
                                $data->id_karyawan = $id_kry;
                                $data->tanggal = $tgl_absen;
                                $data->jam_lembur_hl = $total_jam_lembur;
                                $data->nominal_lembur_hl = round($nominal_lembur,2);
                                $data->user_at = session()->get('sess_username');
                                $data->created_at = $date;
                                $data->save();
                            }elseif($total_jam_lembur >= 10){
                                for ($x = 1; $x <= $total_jam_lembur; $x++) {
                                    if($x == 1){
                                        $nominal = 8 * (2 * $updah_perjam);
                                        $x = 8;
                                    }elseif($x == 9){
                                        $nominal = 1 * (3 * $updah_perjam);
                                    }else{
                                        $nominal = 1 * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                if($menit_to_jam > 0){
                                    if($total_jam_lembur <= 9){
                                        $nominal = $menit_to_jam * (3 * $updah_perjam);
                                    }elseif($total_jam_lembur <= 10){
                                        $nominal = $menit_to_jam * (4 * $updah_perjam);
                                    }
                                    $nominal_lembur = $nominal_lembur + $nominal;
                                }
                                $data =  new LemburModelTmp();
                                $data->id_karyawan = $id_kry;
                                $data->tanggal = $tgl_absen;
                                $data->jam_lembur_hl = $total_jam_lembur;
                                $data->nominal_lembur_hl = round($nominal_lembur,2);
                                $data->user_at = session()->get('sess_username');
                                $data->created_at = $date;
                                $data->save();
                            }
                        }
                        
                    }else{
                        // if($jamk > 17){
                            $selisih = $new_jam_masuk->diff($new_jam_keluar);
                            $selisih_jam = floatval($selisih->format('%H')) - 9;
                            $selisih_menit = $selisih->format('%i');

                            $jam_lembur = floatval($selisih_jam) - 1;
                            $menit_to_jam = floatval($selisih_menit) / 60;
                            $total_jam_lembur = round($jam_lembur + $menit_to_jam, 2);

                            for ($x = 1; $x <= $jam_lembur; $x++) {
                                if($x == 1){
                                    $nominal = 1 * (1.5 * $updah_perjam);
                                }else{
                                    $nominal = $x * (2 * $updah_perjam);
                                }
                                $nominal_lembur = $nominal_lembur + $nominal;
                            }

                            if($menit_to_jam > 0){
                                $nominal_lembur = $nominal_lembur + ($menit_to_jam * (2 * $updah_perjam));
                            }
                            // dd($nominal_lembur);

                            // dd(round($nominal_lembur,2).' - '.$jam_lembur.' - '.$menit_to_jam.' - '.$tgl_absen.' - '.$id_kry.' upah='.$updah_perjam);
                            $data =  new LemburModelTmp();
                            $data->id_karyawan = $id_kry;
                            $data->tanggal = $tgl_absen;
                            $data->jam_lembur_hk = $total_jam_lembur;
                            $data->nominal_lembur_hk = round($nominal_lembur,2);
                            $data->user_at = session()->get('sess_username');
                            $data->created_at = $date;
                            $data->save();
                        // }
                    }
                }
                
            }

        }
        $msg = 'Data berhasil di generate';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function destroy($id)
    {
        LemburModelHdr::where('id', $id)->delete();
        LemburModelDtl::where('id_hdr', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function destroy_dtl($id)
    {
        LemburModelDtl::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function destroy_tmp()
    {
        $user = session()->get('sess_username');
        LemburModelTmp::where('user_at', $user)->delete();
        return response()->json(['success' => true]);
    }
}
