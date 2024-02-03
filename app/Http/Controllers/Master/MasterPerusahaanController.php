<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Master\MasterPerusahaanModel;
use DateTime;
use DB;

class MasterPerusahaanController extends Controller
{
    public function index()
    {
        return view('master.master-perusahaan');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'DT_RowIndex',
            2 => 'nama',
            3 => 'kode',
            4 => 'alamat',
            5 => 'logo',
        );

        $totalData = DB::table('mst_perusahaan')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('mst_perusahaan')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('mst_perusahaan')
                ->offset($start)
                ->limit($limit)
                ->where('nama', 'LIKE', "%{$search}%")
                ->orWhere('kode', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('mst_perusahaan')
                ->where('nama', 'LIKE', "%{$search}%")
                ->orWhere('kode', 'LIKE', "%{$search}%")
                ->orWhere('alamat', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nama'] = $post->nama;
                $nestedData['kode'] = $post->kode;
                $nestedData['alamat'] = $post->alamat;
                $url = asset('/images/perusahaan/'.$post->logo);
                $nestedData['logo'] = '<img src='.$url.' border="0" height="100" width="100" class="img-rounded" align="center" />';
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
        $data =  new MasterPerusahaanModel();

        if($request->file('image')){
            $file_upload    = $request->file('image');
            $fileName       = 'P-' . uniqid() . '.' . $file_upload->getClientOriginalExtension();
            $file_upload->move(public_path('/images/perusahaan/'), $fileName);
        }else{
            $fileName = 'factory.png';
        }
        $data->nama = $request->nama_perusahaan;
        $data->kode = $request->kode_perusahaan;
        $data->alamat = $request->alamat;
        $data->logo = $fileName;
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
            'nama'  => $request->nama,
            'id_devisi'  => $request->devisi,
            'id_perusahaan'  => $request->perusahaan,
        ];

        MasterPerusahaanModel::where('id', $request->sysid)->update($data);
        $msg = 'Data berhasil di ubah';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function destroy($id)
    {
        MasterPerusahaanModel::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
