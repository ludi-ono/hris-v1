<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan\TidakHadirModel;
use App\Models\Absensi\KryOfficeDtlModel;
use App\Models\Master\MasterKaryawanModel;
use Illuminate\Support\Facades\Validator;

use DateTime;
use DB;

class KaryawanCutiController extends Controller
{
    public function index()
    {
        return view('karyawan.karyawan-cuti');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'mst_tidakhadir.id',
            1 => 'mst_tidakhadir.no_dokumen',
            2 => 'mst_tidakhadir.mulai',
            3 => 'mst_tidakhadir.sampai',
            4 => 'mst_tidakhadir.jumlah_hari',
            5 => 'mst_tidakhadir.status',
            6 => 'mst_tidakhadir.id'
        );

        $id_kry = session()->get('sess_id_karyawan');
        $totalData = DB::table('mst_tidakhadir')
            ->join('mst_karyawan', 'mst_tidakhadir.id_karyawan', '=', 'mst_karyawan.id')
            ->where('jenis_ijin', '=', 'Cuti')
            ->where('mst_tidakhadir.id_karyawan',$id_kry)
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('mst_tidakhadir')
                ->join('mst_karyawan', 'mst_tidakhadir.id_karyawan', '=', 'mst_karyawan.id')
                ->selectraw('mst_tidakhadir.* , mst_karyawan.nama, mst_karyawan.nrp')
                ->where('jenis_ijin', '=', 'Cuti')
                ->where('mst_tidakhadir.id_karyawan',$id_kry)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('mst_tidakhadir')
                ->join('mst_karyawan', 'mst_tidakhadir.id_karyawan', '=', 'mst_karyawan.id')
                ->selectraw('mst_tidakhadir.* , mst_karyawan.nama, mst_karyawan.nrp')
                ->offset($start)
                ->limit($limit)
                ->where([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['mulai', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['sampai', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['status', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['keterangan', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['no_dokumen', 'LIKE', "%{$search}%"]])
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('mst_tidakhadir')
                ->join('mst_karyawan', 'mst_tidakhadir.id_karyawan', '=', 'mst_karyawan.id')
                ->where([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['mulai', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['sampai', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['status', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['keterangan', 'LIKE', "%{$search}%"]])
                ->orWhere([['jenis_ijin', '=', 'Cuti'],['mst_tidakhadir.id_karyawan', '=', $id_kry],['no_dokumen', 'LIKE', "%{$search}%"]])
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nrp'] = $post->nrp;
                $nestedData['nama_karyawan'] = $post->nama;
                $nestedData['no_dokumen'] = $post->no_dokumen;
                $nestedData['mulai'] = date('d-m-Y', strtotime($post->mulai));
                $nestedData['sampai'] = date('d-m-Y', strtotime($post->sampai));
                $nestedData['jumlah_hari'] = $post->jumlah_hari;
                $nestedData['status'] = $post->status;
                $nestedData['filename'] = $post->filename;

                if ($post->status == 2) { //'Menunggu Persetujuan HRD'
                    $nestedData['action'] = "<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a> <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
                } elseif ($post->status == 1) { //'Menunggu Persetujuan Atasan'
                    $nestedData['action'] = "<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a>
                    <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
                } elseif ($post->status == 3) { //'Cuti Anda Di Setujui'
                    $nestedData['action'] = "<a href='javascript:void(0)' id='detail' data-toggle='tooltip' title='Detail' data-id='$post->id' data-original-title='' class='Detail btn btn-primary btn-sm'><i class='fa-regular fa-file-pdf'></i> &nbsp; Detail </a>";
                } else {
                    $nestedData['action'] = '-';
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
        $validator = Validator::make($request->all(), [
            'file'          => 'required|mimes:jpg,jpeg,bmp,png,pdf|max:1024'
        ],[
            'file.required' => 'File harus diisi',
            'file.mimes'    => 'Format file tidak sesuai',
            'file.max'      => 'File tidak bisa lebih besar dari 1MB',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['success' => false, 'message' => $error]);
        }
        $data =  new TidakHadirModel();

        // no dokumen reset per tahun
        $year = date('Y');
        $month = date('m');
        $no = TidakHadirModel::where('jenis_ijin','Cuti')->where('no_dokumen', 'LIKE', 'C-'.substr($year,-2)."%")->count();
        $docno = 'C-'.substr($year,-2).$month.substr('0000'.$no+1,-4);
        
        $data_kry = MasterKaryawanModel::where('id',$request->nrp)->first();

        $data->id_karyawan = $request->nrp;
        $data->id_devisi = $data_kry->id_devisi ?? 0;
        $data->no_dokumen = $docno;
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
        $data->jumlah_hari = floatval($jumlah_hari) + 1;

        $file_upload    = $request->file('file');
        $fileName       = 'C-' . uniqid() . '.' . $file_upload->getClientOriginalExtension();
        $file_upload->move(public_path('/file_upload/'), $fileName);
        $data->filename = $fileName;
        $data->keterangan = $request->keterangan;
        $data->status = 1;
        $data->status_approve = 0;
        $data->user_at = session()->get('sess_username');
        $data->save();



        // // Insert absensi
        // for ($i = 0; $i <= floatval($jumlah_hari); $i++) {
        //     $hari = ' -'.$i.' day';
        //     $tgl = date('Y-m-d h:i:s', strtotime($request->mulai.$hari));

        //     $id_hdr = KryOfficeDtlModel::where('tanggal',$tgl)->first()->id_hdr ?? 0;
        //     // $nrp = MasterKaryawanModel::where('id',$request->nrp)->first()->nrp ?? 0;

        //     $data_absen['id_hdr']     = $id_hdr;
        //     $data_absen['nik']   = $data_kry->nrp ?? 0;
        //     $data_absen['nama'] = $request->nama_karyawan;
        //     $data_absen['tanggal'] = $tgl;
        //     $data_absen['jam_masuk'] = null;
        //     $data_absen['jam_keluar'] = null;
        //     $data_absen['user_at']     = session()->get('sess_username');
        //     $data_absen['status'] = 'Cuti';
        //     $save_barang = KryOfficeDtlModel::create($data_absen);
        // }
        // dd('ss');

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function update(Request $request)
    {
        $date = new DateTime;
        $validator = Validator::make($request->all(), [
            'file'          => 'mimes:jpg,jpeg,bmp,png,pdf|max:1024'
        ],[
            'file.mimes'    => 'Format file tidak sesuai',
            'file.max'      => 'File tidak bisa lebih besar dari 1MB',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['success' => false, 'message' => $error]);
        }

        // Perhitungan Jumlah Hari //
        $tgl_mulai = $request->mulai;
        $tgl_sampai = $request->sampai;
        $datetime1 = new DateTime($tgl_mulai);
        $datetime2 = new DateTime($tgl_sampai);
        $interval = $datetime1->diff($datetime2);
        $jumlah_hari = $interval->format('%a'); //now do whatever you like with $days

        if($request->file('file')){
            $file_upload    = $request->file('file');
            $fileName       = 'C-' . uniqid() . '.' . $file_upload->getClientOriginalExtension();
            $file_upload->move(public_path('/file_upload/'), $fileName);
            $data = [
                'mulai'  => $request->mulai,
                'sampai'  => $request->sampai,
                'filename'  => $fileName,
                'keterangan'  => $request->keterangan,
                'jumlah_hari'  => floatval($jumlah_hari) + 1,
            ];
        }else{
            $data = [
                'mulai'  => $request->mulai,
                'sampai'  => $request->sampai,
                'keterangan'  => $request->keterangan,
                'jumlah_hari'  => floatval($jumlah_hari) + 1,
            ];
        }
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
        $query = DB::table('mst_tidakhadir')->where('id', $request->id)->first();
        return response()->json($query);
    }
}
