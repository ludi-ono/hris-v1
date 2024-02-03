<?php

namespace App\Http\Controllers\Approve;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Karyawan\SplModel;
use App\Models\Karyawan\TidakHadirModel;
use DateTime;
use DB;

class ApproveManagerController extends Controller
{
    public function spl_manager()
    {
        return view('approve.approve-spl-manager');
    }

    public function list_data_spl(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'nama_karyawan',
            3 => 'tgl_lembur',
            4 => 'keterangan',
            5 => 'action',
        );

        $totalData = DB::table('qview_spl')
            ->where([['status', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('qview_spl')
                ->where([['status', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('qview_spl')
                ->offset($start)
                ->limit($limit)
                ->where([['nama_karyawan', 'LIKE', "%{$search}%"], ['status', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('qview_spl')
                ->where([['nama_karyawan', 'LIKE', "%{$search}%"], ['status', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['id_karyawan'] = $post->id_karyawan;
                $nestedData['nama_karyawan'] = $post->nama_karyawan;
                $nestedData['tgl_lembur'] = date('d-m-Y', strtotime($post->tgl_lembur));
                $nestedData['keterangan'] = $post->keterangan;
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='approve' data-toggle='tooltip' title='Approve' data-id='$post->id' data-original-title='' class='Approve btn btn-success btn-sm'><i class='fas fa-check'></i> &nbsp; Apporve </a>
                                        <a href='javascript:void(0)' id='reject' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Reject btn btn-danger btn-sm'><i class='fa-solid fa-circle-xmark'></i> &nbsp; Reject </a>";
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

    public function approve_spl($id)
    {
        $data = [
            'status' => 1,
            'user_at' => session()->get('sess_nama'),
        ];

        SplModel::where('id', $id)->update($data);

        //dd($coba);

        return response()->json(['success' => true]);
    }

    public function reject_spl($id)
    {
        $data = [
            'status' => 4,
            'user_at' => session()->get('sess_nama'),
        ];

        SplModel::where('id', $id)->update($data);
        return response()->json(['success' => true]);
    }

    public function absen_manager()
    {
        return view('approve.approve-absen-manager');
    }

    public function list_data_absen(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'nama_karyawan',
            3 => 'mulai',
            4 => 'sampai',
            5 => 'jumlah_hari',
            6 => 'keterangan',
            7 => 'action',
        );

        $totalData = DB::table('qview_ketidakhadiran')
            ->where([['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('qview_ketidakhadiran')
                ->where([['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('qview_ketidakhadiran')
                ->offset($start)
                ->limit($limit)
                ->where([['nama_karyawan', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orwhere([['jenis_ijin', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orwhere([['mulai', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orwhere([['sampai', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('qview_ketidakhadiran')
                ->where([['nama_karyawan', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orwhere([['jenis_ijin', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orwhere([['mulai', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->orwhere([['sampai', 'LIKE', "%{$search}%"], ['status_approve', '=', 0], ['id_devisi', '=', session()->get('sess_id_devisi')]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['id_karyawan'] = $post->id_karyawan;
                $nestedData['nama_karyawan'] = $post->nama_karyawan;
                $nestedData['jenis_ijin'] = $post->jenis_ijin;
                $nestedData['mulai'] = date('d-m-Y', strtotime($post->mulai));
                $nestedData['sampai'] = date('d-m-Y', strtotime($post->sampai));
                $nestedData['jumlah_hari'] = $post->jumlah_hari;
                $nestedData['filename'] = $post->filename;
                $nestedData['keterangan'] = $post->keterangan;
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='approve' data-toggle='tooltip' title='Approve' data-id='$post->id' data-original-title='' class='Approve btn btn-success btn-sm'><i class='fas fa-check'></i> &nbsp; Apporve </a>
                                        <a href='javascript:void(0)' id='reject' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Reject btn btn-danger btn-sm'><i class='fa-solid fa-circle-xmark'></i> &nbsp; Reject </a>";
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

    public function approve_absen($id)
    {
        $data = [
            'status_approve' => 1,
            'user_at' => session()->get('sess_nama'),
        ];

        TidakHadirModel::where('id', $id)->update($data);
        return response()->json(['success' => true]);
    }

    public function reject_absen($id)
    {
        $data = [
            'status_approve' => 4,
            'user_at' => session()->get('sess_nama'),
        ];

        TidakHadirModel::where('id', $id)->update($data);
        return response()->json(['success' => true]);
    }
}
