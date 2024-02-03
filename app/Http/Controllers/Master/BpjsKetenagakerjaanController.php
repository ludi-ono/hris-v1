<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\BpjsKetenagakerjaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Master\MasterKaryawanModel;
use DateTime;
use DB;

class BpjsKetenagakerjaanController extends Controller
{
    public function index()
    {
        return view('master.bpjs-ketenagakerjaan');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'mst_bpjs_ketenagakerjaan.id',
            1 => 'mst_bpjs_ketenagakerjaan.id',
            2 => 'mst_karyawan.nama',
            3 => 'mst_devisi.nama',
            4 => 'mst_bpjs_ketenagakerjaan.nilai',
            5 => 'mst_bpjs_ketenagakerjaan.id',
        );

        $totalData = DB::table('mst_bpjs_ketenagakerjaan')
            ->rightjoin('mst_karyawan', 'mst_bpjs_ketenagakerjaan.id_karyawan', '=', 'mst_karyawan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('mst_bpjs_ketenagakerjaan')
                ->rightjoin('mst_karyawan', 'mst_bpjs_ketenagakerjaan.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_bpjs_ketenagakerjaan.*, mst_karyawan.nama as nama_karyawan, mst_devisi.nama as nama_devisi, mst_karyawan.id as id_kry')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('mst_bpjs_ketenagakerjaan')
                ->rightjoin('mst_karyawan', 'mst_bpjs_ketenagakerjaan.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_bpjs_ketenagakerjaan.*, mst_karyawan.nama as nama_karyawan, mst_devisi.nama as nama_devisi, mst_karyawan.id as id_kry')
                ->offset($start)
                ->limit($limit)
                ->where('nama_karyawan', 'LIKE', "%{$search}%")
                ->orWhere('nama_devisi', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('mst_bpjs_ketenagakerjaan')
                ->rightjoin('mst_karyawan', 'mst_bpjs_ketenagakerjaan.id_karyawan', '=', 'mst_karyawan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->selectraw('mst_bpjs_ketenagakerjaan.*, mst_karyawan.nama as nama_karyawan, mst_devisi.nama as nama_devisi')
                ->where('nama_karyawan', 'LIKE', "%{$search}%")
                ->orWhere('nama_devisi', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nilai = $post->nilai ?? 0;
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nama_karyawan'] = $post->nama_karyawan;
                $nestedData['nama_devisi'] = $post->nama_devisi;
                $nestedData['nilai'] = $nilai;
                $nestedData['id_karyawan'] = $post->id_kry;

                if ($nilai == 0) {
                    $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='input' data-toggle='tooltip' title='Input' data-id='$post->id' data-original-title='' class='Input btn btn-info btn-sm'><i class='fa-solid fa-share'></i> &nbsp; Input Nilai BPJS </a>";
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
        $data =  new BpjsKetenagakerjaanModel();
        $data->nilai = $request->nilai;
        $data->id_karyawan = $request->id_karyawan;
        $data->user_at = session()->get('sess_username');
        $data->created_at = $date;
        $data->save();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function update(Request $request)
    {
        $date = new DateTime;
        $data = [
            'nilai' => $request->nilai,
        ];

        BpjsKetenagakerjaanModel::where('id', $request->sysid)->update($data);
        $msg = 'Data berhasil di ubah';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    // public function destroy($id)
    // {
    //     MasterDevisiModel::where('id', $id)->delete();
    //     return response()->json(['success' => true]);
    // }

    // function perusahaan()
    // {
    //     $query = DB::table('mst_perusahaan')->get();
    //     return response()->json(['data' => $query]);
    // }
}
