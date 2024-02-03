<?php

namespace App\Http\Controllers\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Redirect, Response;
use DateTime;
use DB;

class RekapAbsensiController extends Controller
{
    public function index()
    {
        return view('attendance.rekap-absensi-office');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'nik',
            3 => 'created_at'
        );

        $devisi = $request->devisi;
        $tgl_dari = $request->tgl_dari;
        $tgl_sampai = $request->tgl_sampai;
        $totalData = DB::table('trx_absen_office')
                    ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                    ->join('qview_data_karyawan', 'trx_absen_office.nik', '=', 'qview_data_karyawan.nrp')
                    ->where('qview_data_karyawan.id_devisi',$devisi)
                    ->whereBetween('trx_absen_office.tanggal',[$tgl_dari,$tgl_sampai])
                    ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('trx_absen_office')
                ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                ->join('qview_data_karyawan', 'trx_absen_office.nik', '=', 'qview_data_karyawan.nrp')
                ->selectraw('trx_absen_office.*, trx_absen_office_hdr.file_name, qview_data_karyawan.nama_devisi')
                ->where('qview_data_karyawan.id_devisi',$devisi)
                ->whereBetween('trx_absen_office.tanggal',[$tgl_dari,$tgl_sampai])
                ->offset($start)
                ->limit($limit)
                ->orderBy('nik')
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('trx_absen_office')
                ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                ->join('qview_data_karyawan', 'trx_absen_office.nik', '=', 'qview_data_karyawan.nrp')
                ->selectraw('trx_absen_office.*, trx_absen_office_hdr.file_name, qview_data_karyawan.nama_devisi')
                ->offset($start)
                ->limit($limit)
                ->where(function($query) use ($devisi,$tgl_dari,$tgl_sampai,$search){
                    $query->whereBetween('trx_absen_office.tanggal',[$tgl_dari,$tgl_sampai])
                            ->Where('qview_data_karyawan.id_devisi',$devisi)
                            ->where('trx_absen_office.nik', 'LIKE', "%{$search}%");
                })
                ->orwhere(function($query) use ($devisi,$tgl_dari,$tgl_sampai,$search){
                    $query->whereBetween('trx_absen_office.tanggal',[$tgl_dari,$tgl_sampai])
                            ->Where('qview_data_karyawan.id_devisi',$devisi)
                            ->where('trx_absen_office.nama', 'LIKE', "%{$search}%");
                })
                // ->where([['trx_absen_office_hdr.id_devisi',$devisi],['trx_absen_office.nik', 'LIKE', "%{$search}%"]])
                // ->orWhere([['trx_absen_office_hdr.id_devisi',$devisi],['trx_absen_office.nama', 'LIKE', "%{$search}%"]])
                ->orderBy('nik')
                ->get();

            $totalFiltered = DB::table('trx_absen_office')
                ->join('trx_absen_office_hdr', 'trx_absen_office.id_hdr', '=', 'trx_absen_office_hdr.id')
                ->join('qview_data_karyawan', 'trx_absen_office.nik', '=', 'qview_data_karyawan.nrp')
                ->where(function($query) use ($devisi,$tgl_dari,$tgl_sampai,$search){
                    $query->whereBetween('trx_absen_office.tanggal',[$tgl_dari,$tgl_sampai])
                            ->Where('qview_data_karyawan.id_devisi',$devisi)
                            ->where('trx_absen_office.nik', 'LIKE', "%{$search}%");
                })
                ->orwhere(function($query) use ($devisi,$tgl_dari,$tgl_sampai,$search){
                    $query->whereBetween('trx_absen_office.tanggal',[$tgl_dari,$tgl_sampai])
                            ->Where('qview_data_karyawan.id_devisi',$devisi)
                            ->where('trx_absen_office.nama', 'LIKE', "%{$search}%");
                })
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
                $nestedData['devisi'] = $post->nama_devisi;

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
}
