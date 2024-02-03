<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\MasterKaryawanModel;
use App\Models\Master\BpjsKesehatanModel;
use App\Models\Master\BpjsKetenagakerjaanModel;
use DateTime;
use DB;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('master.master-karyawan');
    }

    public function list_data(Request $request)
    {
        $columns = array(
            0 => 'mst_karyawan.id',
            1 => 'mst_karyawan.id',
            2 => 'nama',
            3 => 'kode',
            4 => 'alamat',
            5 => 'logo',
        );

        $totalData = DB::table('mst_karyawan')
            ->join('mst_perusahaan', 'mst_karyawan.id_perusahaan', '=', 'mst_perusahaan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->join('mst_jabatan', 'mst_karyawan.id_jabatan', '=', 'mst_jabatan.id')
            ->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $posts = DB::table('mst_karyawan')
                ->join('mst_perusahaan', 'mst_karyawan.id_perusahaan', '=', 'mst_perusahaan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->join('mst_jabatan', 'mst_karyawan.id_jabatan', '=', 'mst_jabatan.id')
                ->selectraw('mst_karyawan.*, mst_perusahaan.nama as nama_perusahaan, mst_devisi.nama as nama_devisi, mst_jabatan.nama as nama_jabatan')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $posts =  DB::table('mst_karyawan')
                ->join('mst_perusahaan', 'mst_karyawan.id_perusahaan', '=', 'mst_perusahaan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->join('mst_jabatan', 'mst_karyawan.id_jabatan', '=', 'mst_jabatan.id')
                ->selectraw('mst_karyawan.*, mst_perusahaan.nama as nama_perusahaan, mst_devisi.nama as nama_devisi, mst_jabatan.nama as nama_jabatan')
                ->offset($start)
                ->limit($limit)
                ->where('mst_karyawan.nrp', 'LIKE', "%{$search}%")
                ->orWhere('mst_karyawan.nik', 'LIKE', "%{$search}%")
                ->orWhere('mst_karyawan.nama', 'LIKE', "%{$search}%")
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = DB::table('mst_karyawan')
                ->join('mst_perusahaan', 'mst_karyawan.id_perusahaan', '=', 'mst_perusahaan.id')
                ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
                ->join('mst_jabatan', 'mst_karyawan.id_jabatan', '=', 'mst_jabatan.id')
                ->selectraw('mst_karyawan.*, mst_perusahaan.nama as nama_perusahaan, mst_devisi.nama as nama_devisi, mst_jabatan.nama as nama_jabatan')
                ->offset($start)
                ->limit($limit)
                ->where('mst_karyawan.nrp', 'LIKE', "%{$search}%")
                ->orWhere('mst_karyawan.nik', 'LIKE', "%{$search}%")
                ->orWhere('mst_karyawan.nama', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = array();
        $i = $start + 1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['DT_RowIndex'] = $i;
                $nestedData['nrp'] = $post->nrp;
                $nestedData['nik'] = $post->nik;
                $nestedData['nama'] = $post->nama;
                $nestedData['alamat'] = $post->alamat;
                $nestedData['action'] = "&emsp;<a href='javascript:void(0)' id='edit' data-toggle='tooltip' title='Edit' data-id='$post->id' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-pencil-alt'></i> &nbsp; Edit </a>
                                        <a href='javascript:void(0)' id='delete' data-toggle='tooltip' title='Delete' data-id='$post->id' data-original-title='' class='Delete btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i> &nbsp; Hapus </a>";
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
        $date = new DateTime;
        $data =  new MasterKaryawanModel();

        if ($request->file('file_ktp')) {
            $file_ktp    = $request->file('file_ktp');
            $fileKtp       = 'K-' . uniqid() . '.' . $file_ktp->getClientOriginalExtension();
            $file_ktp->move(public_path('/images/ktp/'), $fileKtp);
        } else {
            $fileKtp = 'factory.png';
        }
        if ($request->file('file_npwp')) {
            $file_npwp    = $request->file('file_npwp');
            $fileNpwp       = 'N-' . uniqid() . '.' . $file_npwp->getClientOriginalExtension();
            $file_npwp->move(public_path('/images/npwp/'), $fileNpwp);
        } else {
            $fileNpwp = 'factory.png';
        }
        if ($request->file('file_ijazah')) {
            $file_ijazah    = $request->file('file_ijazah');
            $fileIjazah       = 'I-' . uniqid() . '.' . $file_ijazah->getClientOriginalExtension();
            $file_ijazah->move(public_path('/images/ijazah/'), $fileIjazah);
        } else {
            $fileIjazah = NULL;
        }
        if ($request->file('file_sertifikat')) {
            $file_sertifikat    = $request->file('file_sertifikat');
            $fileSertifikat       = 'S-' . uniqid() . '.' . $file_sertifikat->getClientOriginalExtension();
            $file_sertifikat->move(public_path('/images/sertifikat/'), $fileSertifikat);
        } else {
            $fileSertifikat = NULL;
        }
        $data->id_perusahaan = $request->perusahaan;
        $data->id_devisi = $request->devisi;
        $data->id_jabatan = $request->jabatan;
        $data->nrp = $request->nrp;
        $data->nik = $request->nik;
        $data->nama = $request->nama;
        $data->alamat = $request->alamat;
        $data->alamat_domisili = $request->alamat_domisili;
        $data->ktp = $request->ktp;
        $data->npwp = $request->npwp;
        $data->file_ktp = $fileKtp;
        $data->file_npwp = $fileNpwp;
        $data->file_ijazah = $fileIjazah;
        $data->file_sertifikat = $fileSertifikat;
        $data->jenis_kelamin = $request->jenis_kelamin;
        $data->tempat_lahir = $request->tempat_lahir;
        $data->tanggal_lahir = $request->tgl_lahir;
        $data->tanggal_masuk = $request->tgl_masuk;
        $data->tanggal_keluar = $request->tgl_keluar;
        $data->status_perkawinan = $request->status;
        $data->status_karyawan = $request->status_karyawan;
        $data->status_user = 0;
        $data->user_at = 'Admin';
        $data->created_at = $date;
        $data->save();

        // Insert BPSJ
        $data_umr = DB::table('mst_setting_umr')->where('status',1)->first();
        if($data_umr){
            $umr_bks = $data_umr->umr_bekasi;
            $umr_jkt = $data_umr->umr_jakarta;
        }else{
            $umr_bks = 0;
            $umr_jkt = 0;
        }

        $thp = floatval($data->gaji_pokok) + floatval($data->presensi) + floatval($data->tunjangan_pulsa) + floatval($data->tunjangan_jabatan);

        // Insert BPSJ kesehatan
        if($thp < $umr_bks){
            $bpjs_kesehatan = (5/100) * $umr_bks;
        }else{
            $bpjs_kesehatan = (5/100) * $thp;
        }
        $data_kesehatan =  new BpjsKesehatanModel();
        $data_kesehatan->nilai = $bpjs_kesehatan;
        $data_kesehatan->id_karyawan = $data->id;
        $data_kesehatan->user_at = session()->get('sess_username');
        $data_kesehatan->created_at = $date;
        $data_kesehatan->save();

        // Insert BPSJ ketenagakerjaan
        $range = floatval($data->gaji_pokok) + floatval($data->tunjangan_pulsa) + floatval($data->tunjangan_jabatan);
        if($range >= 0 && $range <= 2499999){
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }elseif($range >= 2500000 && $range <= $umr_jkt){
            $bpjs_ketenagakerjaan = (3/100) * $umr_jkt;
        }else{
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }
        $data_ketenagakerjaan =  new BpjsKetenagakerjaanModel();
        $data_ketenagakerjaan->nilai = $bpjs_ketenagakerjaan;
        $data_ketenagakerjaan->id_karyawan = $data->id;
        $data_ketenagakerjaan->user_at = session()->get('sess_username');
        $data_ketenagakerjaan->created_at = $date;
        $data_ketenagakerjaan->save();

        $msg = 'Data berhasil di simpan';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function update(Request $request)
    {
        $date = new DateTime;
        $data = [
            'id_perusahaan'  => $request->perusahaan,
            'id_devisi'  => $request->devisi,
            'id_jabatan'  => $request->jabatan,
            'nrp'  => $request->nrp,
            'nik'  => $request->nik,
            'nama'  => $request->nama,
            'alamat'  => $request->alamat,
            'alamat_domisili'  => $request->alamat_domisili,
            'ktp'  => $request->ktp,
            'npwp'  => $request->npwp,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tgl_lahir,
            'tanggal_masuk'  => $request->tgl_masuk,
            'tanggal_keluar'  => $request->tgl_keluar,
            'status_perkawinan'  => $request->status,
            'status_karyawan'  => $request->status_karyawan,
        ];

        MasterKaryawanModel::where('id', $request->sysid)->update($data);


        if ($request->file('file_ktp')) {
            $file_ktp    = $request->file('file_ktp');
            $fileKtp       = 'K-' . uniqid() . '.' . $file_ktp->getClientOriginalExtension();
            $file_ktp->move(public_path('/images/ktp/'), $fileKtp);
            MasterKaryawanModel::where('id', $request->sysid)->update(['file_ktp' => $fileKtp]);
        }
        if ($request->file('file_npwp')) {
            $file_npwp    = $request->file('file_npwp');
            $fileNpwp       = 'N-' . uniqid() . '.' . $file_npwp->getClientOriginalExtension();
            $file_npwp->move(public_path('/images/npwp/'), $fileNpwp);
            MasterKaryawanModel::where('id', $request->sysid)->update(['file_npwp' => $fileNpwp]);
        }
        if ($request->file('file_ijazah')) {
            $file_ijazah    = $request->file('file_ijazah');
            $fileIjazah       = 'I-' . uniqid() . '.' . $file_ijazah->getClientOriginalExtension();
            $file_ijazah->move(public_path('/images/ijazah/'), $fileIjazah);
            MasterKaryawanModel::where('id', $request->sysid)->update(['file_ijazah' => $fileIjazah]);
        }
        if ($request->file('file_sertifikat')) {
            $file_sertifikat    = $request->file('file_sertifikat');
            $fileSertifikat       = 'S-' . uniqid() . '.' . $file_sertifikat->getClientOriginalExtension();
            $file_sertifikat->move(public_path('/images/sertifikat/'), $fileSertifikat);
            MasterKaryawanModel::where('id', $request->sysid)->update(['file_sertifikat' => $fileSertifikat]);
        }

        // Insert BPSJ
        $data_umr = DB::table('mst_setting_umr')->where('status',1)->first();
        if($data_umr){
            $umr_bks = $data_umr->umr_bekasi;
            $umr_jkt = $data_umr->umr_jakarta;
        }else{
            $umr_bks = 0;
            $umr_jkt = 0;
        }

        $data_kry = MasterKaryawanModel::where('id', $request->sysid)->get();
        $thp = floatval($data_kry->gaji_pokok) + floatval($data_kry->presensi) + floatval($data_kry->tunjangan_pulsa) + floatval($data_kry->tunjangan_jabatan);

        // Insert BPSJ kesehatan
        if($thp < $umr_bks){
            $bpjs_kesehatan = (5/100) * $umr_bks;
        }else{
            $bpjs_kesehatan = (5/100) * $thp;
        }
        $data_update = [
            'nilai' => $bpjs_kesehatan,
        ];
        BpjsKesehatanModel::where('id_karyawan', $request->sysid)->update($data_update);

        // Insert BPSJ ketenagakerjaan
        $range = floatval($data_kry->gaji_pokok) + floatval($data_kry->tunjangan_pulsa) + floatval($data_kry->tunjangan_jabatan);
        if($range >= 0 && $range <= 2499999){
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }elseif($range >= 2500000 && $range <= $umr_jkt){
            $bpjs_ketenagakerjaan = (3/100) * $umr_jkt;
        }else{
            $bpjs_ketenagakerjaan = (3/100) * $range;
        }
        $data_update = [
            'nilai' => $bpjs_ketenagakerjaan,
        ];

        BpjsKetenagakerjaanModel::where('id_karyawan', $request->sysid)->update($data_update);

        $msg = 'Data berhasil di ubah';
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function destroy($id)
    {
        MasterKaryawanModel::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function get_data(Request $request)
    {
        $query = DB::table('mst_karyawan')
            ->join('mst_perusahaan', 'mst_karyawan.id_perusahaan', '=', 'mst_perusahaan.id')
            ->join('mst_devisi', 'mst_karyawan.id_devisi', '=', 'mst_devisi.id')
            ->join('mst_jabatan', 'mst_karyawan.id_jabatan', '=', 'mst_jabatan.id')
            ->selectraw('mst_karyawan.*, mst_perusahaan.nama as nama_perusahaan, mst_devisi.nama as nama_devisi, mst_jabatan.nama as nama_jabatan')
            ->where('mst_karyawan.id', $request->id)
            ->first();
        return response()->json($query);
    }
}
