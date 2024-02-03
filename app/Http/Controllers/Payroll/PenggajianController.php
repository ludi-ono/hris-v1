<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll\GajiModelHdr;
use App\Models\Payroll\GajiModelDtl;
use App\Models\Payroll\GajiModelTmp;
use DateTime;
use DB;
use PDF;

class PenggajianController extends Controller
{
    public function index()
    {
        return view('payroll.penggajian');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'mst_karyawan.nrp',
            1 => 'DT_RowIndex',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            // 4 => 'trx_lembur_dtl.presensi',
        );

        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $totalData = DB::table('trx_gaji_dtl')
            ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
            ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
            ->where('trx_gaji_hdr.tahun', $tahun)
            ->where('trx_gaji_hdr.bulan', $bulan)
            // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_gaji_dtl')
                ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
                ->offset($start)
                ->limit($limit)
                ->where('trx_gaji_hdr.tahun', $tahun)
                ->where('trx_gaji_hdr.bulan', $bulan)
                // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_gaji_dtl')
                ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
                ->where('trx_gaji_dtl.id_hdr', $id_hdr)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_gaji_dtl')
                ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->nrp;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nrp'] = $post->nrp;
                $nestedData['nama'] = $post->nama;
                $nestedData['devisi'] = $post->devisi;
                $nestedData['nominal'] = $post->gaji;
                $nestedData['bulan'] = $post->bulan;
                $nestedData['tahun'] = $post->tahun;
                // $nestedData['status'] = $post->status;
                $nestedData['action'] = "<a href='javascript:void(0)' id='print' data-toggle='tooltip' title='Print' data-id='$post->nrp' data-original-title='' class='Delete btn btn-warning btn-sm'><i class='fas fa-print'></i> &nbsp; Print </a>";
                // $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->nrp' data-original-title='' class='Edit btn btn-info btn-sm'><i class='fas fa-info'></i> &nbsp; Detail </a>
                //                         <a href='javascript:void(0)' id='print' data-toggle='tooltip' title='Print' data-id='$post->nrp' data-original-title='' class='Delete btn btn-warning btn-sm'><i class='fas fa-print'></i> &nbsp; Print </a>";
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

        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $totalData = DB::table('trx_gaji_dtl')
            ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
            ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
            ->where('trx_gaji_hdr.tahun', $tahun)
            ->where('trx_gaji_hdr.bulan', $bulan)
            // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_gaji_dtl')
                ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
                ->offset($start)
                ->limit($limit)
                ->where('trx_gaji_hdr.tahun', $tahun)
                ->where('trx_gaji_hdr.bulan', $bulan)
                // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_gaji_dtl')
                ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
                ->where('trx_gaji_dtl.id_hdr', $id_hdr)
                ->offset($start)
                ->limit($limit)
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('trx_gaji_dtl')
                ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
                ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan')
                ->where([['mst_karyawan.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                ->orWhere([['mst_devisi.nama', 'LIKE', "%{$search}%"],['trx_gaji_hdr.tahun', $tahun],['trx_gaji_hdr.bulan', $bulan]])
                // ->groupBy(\DB::raw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama'))
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->nrp;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nrp'] = $post->nrp;
                $nestedData['nama'] = $post->nama;
                $nestedData['devisi'] = $post->devisi;
                $nestedData['nominal'] = $post->gaji;
                $nestedData['bulan'] = $post->bulan;
                $nestedData['tahun'] = $post->tahun;
                // $nestedData['status'] = $post->status;
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->nrp' data-original-title='' class='Edit btn btn-info btn-sm'><i class='fas fa-info'></i> &nbsp; Detail </a>
                                        <a href='javascript:void(0)' id='print' data-toggle='tooltip' title='Print' data-id='$post->nrp' data-original-title='' class='Delete btn btn-warning btn-sm'><i class='fas fa-print'></i> &nbsp; Print </a>";
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

    public function print_slip(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $nrp = $request->nrp;

        $data = DB::table('trx_gaji_dtl')
                    ->join('trx_gaji_hdr', 'trx_gaji_dtl.id_hdr', '=', 'trx_gaji_hdr.id')
                    ->join('mst_karyawan', 'trx_gaji_dtl.id_karyawan', '=', 'mst_karyawan.id')
                    ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                    ->join('mst_perusahaan', 'mst_karyawan.id_perusahaan', '=', 'mst_perusahaan.id')
                    ->leftjoin('mst_bpjs_kesehatan', 'mst_karyawan.id', '=', 'mst_bpjs_kesehatan.id_karyawan')
                    ->leftjoin('mst_bpjs_ketenagakerjaan', 'mst_karyawan.id', '=', 'mst_bpjs_ketenagakerjaan.id_karyawan')
                    ->selectraw('mst_karyawan.nrp, mst_karyawan.nik, mst_karyawan.nama, mst_devisi.nama as devisi, (trx_gaji_dtl.nilai_gaji) as gaji, trx_gaji_hdr.tahun, trx_gaji_hdr.bulan, mst_perusahaan.nama as nama_perusahaan, mst_perusahaan.alamat as alamat_perusahaan, mst_karyawan.gaji_pokok, mst_karyawan.tunjangan_jabatan, mst_karyawan.tunjangan_pulsa, mst_bpjs_kesehatan.nilai as bpjs_kesehatan, mst_bpjs_ketenagakerjaan.nilai as bpjs_ketenagakerjaan')
                    ->where('trx_gaji_hdr.tahun', $tahun)
                    ->where('trx_gaji_hdr.bulan', $bulan)
                    ->where('mst_karyawan.nrp', $nrp)
                    ->first();

        $data_presesnsi = DB::table('trx_presensi_hdr')
                            ->join('trx_presensi_dtl', 'trx_presensi_hdr.id', '=', 'trx_presensi_dtl.id_hdr')
                            ->join('mst_karyawan', 'trx_presensi_dtl.id_karyawan', '=', 'mst_karyawan.id')
                            ->selectraw('trx_presensi_dtl.presensi')
                            ->where('trx_presensi_hdr.tahun', $tahun)
                            ->where('trx_presensi_hdr.bulan', $bulan)
                            ->where('mst_karyawan.nrp', $nrp)
                            ->first()->presensi;

        $data_lembur = DB::table('trx_lembur_hdr')
                            ->join('trx_lembur_dtl', 'trx_lembur_hdr.id', '=', 'trx_lembur_dtl.id_hdr')
                            ->join('mst_karyawan', 'trx_lembur_dtl.id_karyawan', '=', 'mst_karyawan.id')
                            ->selectraw('trx_lembur_dtl.*')
                            ->where('trx_lembur_hdr.tahun', $tahun)
                            ->where('trx_lembur_hdr.bulan', $bulan)
                            ->where('mst_karyawan.nrp', $nrp)
                            ->first();
        if($data_lembur){
            $lembur_hk = $data_lembur->nominal_lembur_hk??0;
            $lembur_hl = $data_lembur->nominal_lembur_hl??0;
            $lembur = floatval($lembur_hk) + floatval($lembur_hl);

            $jam_lembur_hk = $data_lembur->jam_lembur_hk??0;
            $jam_lembur_hl = $data_lembur->jam_lembur_hl??0;

            $jam_hk = floor($jam_lembur_hk??0);
            $jam_hl = floor($jam_lembur_hl??0);
            $jam_lembur = $jam_hk + $jam_hl;

            $sisa_menit_hk = floor(($jam_lembur_hk - $jam_hk) * 60);
            $sisa_menit_hl = floor(($jam_lembur_hl - $jam_hl) * 60);
            $menit_lembur = $sisa_menit_hk + $sisa_menit_hl;
        }else{
            $lembur = 0;
            $jam_lembur = 0;
            $menit_lembur = 0;
        }

        $bulan_text = $this->bulan_to_text($bulan);

        $pdf = PDF::loadView('karyawan.file.file-penggajian', ['data' => $data, 'bulan' => $bulan_text, 'presesnsi' => $data_presesnsi, 'lembur' => $lembur, 'jam_lembur' => $jam_lembur, 'menit_lembur' => $menit_lembur]);
        return $pdf->stream();
    }

    function bulan_to_text($bulan)
    {
        switch ($bulan) {
            case 1:
                $bulan_text = 'Januari';
                break;
            case 2:
                $bulan_text = 'Februari';
                break;
            case 3:
                $bulan_text = 'Maret';
                break;
            case 4:
                $bulan_text = 'April';
                break;
            case 5:
                $bulan_text = 'Mei';
                break;
            case 6:
                $bulan_text = 'Juni';
                break;
            case 7:
                $bulan_text = 'Juli';
                break;
            case 8:
                $bulan_text = 'Agustus';
                break;
            case 9:
                $bulan_text = 'September';
                break;
            case 10:
                $bulan_text = 'Oktober';
                break;
            case 11:
                $bulan_text = 'November';
                break;
            case 12:
                $bulan_text = 'Desember';
                break;
            default:
                $bulan_text = '-';
                break;
        }
        return $bulan_text;
    }
}
