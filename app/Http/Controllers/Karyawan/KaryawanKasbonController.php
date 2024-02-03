<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan\TidakHadirModel;
use DateTime;
use DB;

class KaryawanKasbonController extends Controller
{
    public function index()
    {
        return view('karyawan.karyawan-kasbon');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'no_dokumen',
            2 => 'nilai',
            3 => 'keterangan',
            4 => 'status',
            5 => 'action'
        );

        $totalData = DB::table('mst_kasbon')
            ->where('id_karyawan', '=', session()->get('sess_id_karyawan'))
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('mst_kasbon')
                ->where('id_karyawan', '=', session()->get('sess_id_karyawan'))
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('mst_kasbon')
                ->offset($start)
                ->limit($limit)
                ->where([['no_dokumen', 'LIKE', "%{$search}%"], [id_karyawan]])
                ->orWhere('nilai', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('mst_kasbon')
                ->where('mulai', 'LIKE', "%{$search}%")
                ->orWhere('sampai', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%")
                ->orWhere('no_dokumen', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nrp'] = $post->nrp;
                $nestedData['nama_karyawan'] = $post->nama_karyawan;
                $nestedData['no_dokumen'] = $post->nama_karyawan;
                $nestedData['mulai'] = date('d-m-Y', strtotime($post->mulai));
                $nestedData['sampai'] = date('d-m-Y', strtotime($post->sampai));
                $nestedData['jumlah_hari'] = $post->jumlah_hari;
                $nestedData['status'] = $post->status;

                if ($post->status == 'Menunggu Persetujuan HRD') {
                    $nestedData['action'] = "<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a> <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
                } elseif ($post->status == 'Menunggu Persetujuan Atasan') {
                    $nestedData['action'] = "<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a>
                    <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
                } elseif ($post->status == 'Cuti Anda Di Setujui') {
                    $nestedData['action'] = "<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->id' data-original-title='' class='Detail btn btn-primary btn-sm'><i class='fa-regular fa-file-pdf'></i> &nbsp; Detail </a>";
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
        $data =  new TidakHadirModel();

        $data->nrp = $request->nrp;
        $data->nama_karyawan = $request->nama_karyawan;
        $data->no_dokumen = $request->no_dokumen;
        $data->jenis_ijin = 'Cuti';
        $data->mulai = $request->mulai;
        $data->sampai = $request->sampai;

        // Perhitungan Jumlah Hari //
        $tgl_mulai = $request->mulai;
        $tgl_sampai = $request->sampai;
        $datetime1 = new DateTime($tgl_mulai);
        $datetime2 = new DateTime($tgl_sampai);
        $interval = $datetime1->diff($datetime2);
        $jumlah_hari = $interval->format('%a'); //now do whatever you like with $days
        $data->jumlah_hari = $jumlah_hari;

        $data->filename = $request->file;
        $data->save();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function update(Request $request)
    {
        $date = new DateTime;
        $data = [
            'mulai'  => $request->mulai,
            'sampai'  => $request->sampai,
            'filename'  => $request->file,
            'keterangan'  => $request->keterangan,
        ];

        TidakHadirModel::where('id', $request->sysid)->update($data);

        $msg = 'Data berhasil di ubah';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function destroy($id)
    {
        TidakHadirModel::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function get_data(Request $request)
    {
        $query = DB::table('mst_kasbon')->where('id', $request->id)->first();
        return response()->json($query);
    }
}
