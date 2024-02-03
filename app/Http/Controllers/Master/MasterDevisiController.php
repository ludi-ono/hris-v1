<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Master\MasterDevisiModel;
use DateTime;
use DB;

class MasterDevisiController extends Controller
{
    public function index()
    {
        return view('master.master-devisi');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'nama',
            3 => 'id_perusahaan',
            4 => 'nama_perusahaan',
            5 => 'action',
        );

        $totalData = DB::table('qview_devisi')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('qview_devisi')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('qview_devisi')
                ->offset($start)
                ->limit($limit)
                ->where('nama', 'LIKE', "%{$search}%")
                ->orWhere('nama_perusahaan', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('qview_devisi')
                ->where('nama', 'LIKE', "%{$search}%")
                ->orWhere('nama_perusahaan', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nama'] = $post->nama;
                $nestedData['id_perusahaan'] = $post->id_perusahaan;
                $nestedData['nama_perusahaan'] = $post->nama_perusahaan;
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a>
                                        <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
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
        $data =  new MasterDevisiModel();
        $data->nama = $request->nama;
        $data->id_perusahaan = $request->perusahaan;
        $data->user_at = 'Admin';
        $data->created_at = $date;
        $data->save();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function update(Request $request)
    {
        $date = new DateTime;
        $data = [
            'nama' => $request->nama,
            'id_perusahaan' => $request->perusahaan,
        ];

        MasterDevisiModel::where('id', $request->sysid)->update($data);
        $msg = 'Data berhasil di ubah';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function destroy($id)
    {
        MasterDevisiModel::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    function perusahaan()
    {
        $query = DB::table('mst_perusahaan')->get();
        return response()->json(['data' => $query]);
    }
}
