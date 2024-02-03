<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan\SplModel;
use DateTime;
use DB;
use SplHeap;

class KaryawanSplController extends Controller
{
    public function index()
    {
        return view('karyawan.karyawan-spl');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'no_dokumen',
            2 => 'id_karyawan',
            3 => 'tgl_lembur',
            4 => 'keterangan',
            5 => 'status',
            6 => 'action'
        );

        $totalData = DB::table('mst_spl')
            ->where('id_karyawan', '=', session()->get('sess_id_karyawan'))
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('mst_spl')
                ->where('id_karyawan', '=', session()->get('sess_id_karyawan'))
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('mst_spl')
                ->offset($start)
                ->limit($limit)
                ->where([['no_dokumen', 'LIKE', "%{$search}%"], ['id_karyawan', '=', session()->get('sess_id_karyawan')]])
                ->orWhere([['tgl_lembur', 'LIKE', "%{$search}%"], ['id_karyawan', '=', session()->get('sess_id_karyawan')]])
                ->orWhere([['keterangan', 'LIKE', "%{$search}%"], ['id_karyawan', '=', session()->get('sess_id_karyawan')]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('mst_spl')
                ->where([['no_dokumen', 'LIKE', "%{$search}%"], ['id_karyawan', '=', session()->get('sess_id_karyawan')]])
                ->orWhere([['tgl_lembur', 'LIKE', "%{$search}%"], ['id_karyawan', '=', session()->get('sess_id_karyawan')]])
                ->orWhere([['keterangan', 'LIKE', "%{$search}%"], ['id_karyawan', '=', session()->get('sess_id_karyawan')]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['no_dokumen'] = $post->no_dokumen;
                $nestedData['tgl_lembur'] = date('d-m-Y', strtotime($post->tgl_lembur));
                $nestedData['keterangan'] = $post->keterangan;

                if ($post->status = 0) {
                    $nestedData['status'] = "Menunggu Persetujuan Manager";
                } else if ($post->status = 1) {
                    $nestedData['status'] = "Disetujui Manager, Menunggu Persetujuan HRD";
                } elseif ($post->status = 2) {
                    $nestedData['status'] = "SPL Anda Di Setuji";
                }

                if ($post->status = 1) {
                    $nestedData['action'] = "<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a>
                                            <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
                } else if ($post->status = 2) {
                    $nestedData['action'] = "<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->id' data-original-title='' class='Detail btn btn-primary btn-sm'><i class='fa-solid fa-circle-info'></i> &nbsp; Detail </a>";
                } elseif ($post->status = 3) {
                    $nestedData['action'] = "<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->id' data-original-title='' class='Detail btn btn-primary btn-sm'><i class='fa-solid fa-circle-info'></i> &nbsp; Detail </a>";
                }

                // $url = asset('/images/perusahaan/'.$post->logo);
                // $nestedData['logo'] = '<img src='.$url.' border="0" height="100" width="100" class="img-rounded" align="center" />';
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
        $created = new DateTime;
        $data =  new SplModel();

        // no dokumen reset per tahun
        $year = date('Y');
        $month = date('m');
        $no = SplModel::where('no_dokumen', 'LIKE', 'SPL-'.substr($year,-2)."%")->count();
        $docno = 'SPL-'.substr($year,-2).$month.substr('0000'.$no+1,-4);

        $data->no_dokumen = $docno;
        $data->id_karyawan = session()->get('sess_id_karyawan');
        $data->tgl_lembur = $request->tgl_lembur;
        $data->keterangan = $request->keterangan;
        $data->status = 0;
        $data->created_at = $created;
        $data->user_at = session()->get('sess_nama');

        $data->save();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function update(Request $request)
    {
        $date = new DateTime;
        $data = [
            'mulai'  => $request->mulai,
            'tgl_lembur'  => $request->tgl_lembur,
            'keterangan'  => $request->keterangan,
            'user_at'  => session()->get('sess_nama'),
        ];

        SplModel::where('id', $request->sysid)->update($data);

        $msg = 'Data berhasil di ubah';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function destroy($id)
    {
        SplModel::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function get_data(Request $request)
    {
        $query = DB::table('mst_spl')->where('id', $request->id)->first();
        return response()->json($query);
    }
}
