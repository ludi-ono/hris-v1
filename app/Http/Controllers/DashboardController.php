<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use DateTime;

class DashboardController extends Controller
{
    public static function month_to_text_3($bulan)
    {
        if($bulan==1){
            $text = 'Jan';
        }elseif($bulan==2){
            $text = 'Feb';
        }elseif($bulan==3){
            $text = 'Mar';
        }elseif($bulan==4){
            $text = 'Apr';
        }elseif($bulan==5){
            $text = 'Mei';
        }elseif($bulan==6){
            $text = 'Jun';
        }elseif($bulan==7){
            $text = 'Jul';
        }elseif($bulan==8){
            $text = 'Ags';
        }elseif($bulan==9){
            $text = 'Sep';
        }elseif($bulan==10){
            $text = 'Okt';
        }elseif($bulan==11){
            $text = 'Nov';
        }elseif($bulan==12){
            $text = 'Des';
        }
        return $text;
    }

    public static function right($text, $length)
    {
        return substr($text, -$length);
    }

    public function list_chart_absensi(Request $request)
    {
        $bln = 12;
        $tahun = date('Y') - 1;
        $tahun_now = date('Y');
        $bulan_now = date('m');
        $dataC = array();
        $dataHadir = array();
        $dataTelat = array();
        $dataCuti = array();
        $dataTHadir = array();
        $blnL = array();

        for ($i=1; $i <= $bln; $i++) {
            $tgl_01 = date('Y-m-t', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $bln_filter = date("n", strtotime(date('Y-m-01')." +".intval($i)." month"));
            $tgl_awal = date('Y-m-01 00:00:00', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $tgl_akhir = date('Y-m-t 00:00:00', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
// dd($tgl_akhir);
            $tahun = date('Y', strtotime("+".intval($i)." month -1 year"));
            // if(intval($bln_filter) == 1){
            //     $tahun = intval($tahun)+1;
            // }
            $blnL[] = $this->month_to_text_3($bln_filter) . ' ' . $this->right($tahun, 2);
            $sumHadir = DB::table('trx_absen_office')
                        ->join('mst_karyawan', 'trx_absen_office.nik', '=', 'mst_karyawan.nrp')
                        ->whereBetween('trx_absen_office.tanggal', [$tgl_awal, $tgl_akhir])
                        ->where('trx_absen_office.status', 'Hadir')
                        ->where('mst_karyawan.id_perusahaan', session()->get('sess_id_perusahaan'))
                        ->count('trx_absen_office.nik');
// dd($dataSum->toSql(), $dataSum->getBindings());
            if($sumHadir){
                $dataHadir[] = $sumHadir;
            }else{
                $dataHadir[] = 0;
            }

            $sumTelat = DB::table('trx_absen_office')
                        ->join('mst_karyawan', 'trx_absen_office.nik', '=', 'mst_karyawan.nrp')
                        ->where('mst_karyawan.id_perusahaan', session()->get('sess_id_perusahaan'))
                        ->whereBetween('trx_absen_office.tanggal', [$tgl_awal, $tgl_akhir])
                        ->where('trx_absen_office.status', 'Telat')
                        ->count('trx_absen_office.nik');

            if($sumTelat){
                $dataTelat[] = $sumTelat;
            }else{
                $dataTelat[] = 0;
            }

            $sumCuti = DB::table('trx_absen_office')
                        ->join('mst_karyawan', 'trx_absen_office.nik', '=', 'mst_karyawan.nrp')
                        ->where('mst_karyawan.id_perusahaan', session()->get('sess_id_perusahaan'))
                        ->whereBetween('trx_absen_office.tanggal', [$tgl_awal, $tgl_akhir])
                        ->where('trx_absen_office.status', 'Cuti')
                        ->count('trx_absen_office.nik');

            if($sumCuti){
                $dataCuti[] = $sumCuti;
            }else{
                $dataCuti[] = 0;
            }

            $sumTHadir = DB::table('trx_absen_office')
                        ->join('mst_karyawan', 'trx_absen_office.nik', '=', 'mst_karyawan.nrp')
                        ->where('mst_karyawan.id_perusahaan', session()->get('sess_id_perusahaan'))
                        ->whereBetween('trx_absen_office.tanggal', [$tgl_awal, $tgl_akhir])
                        ->where('trx_absen_office.status', 'Tidak Hadir')
                        ->count('trx_absen_office.nik');

            if($sumTHadir){
                $dataTHadir[] = $sumTHadir;
            }else{
                $dataTHadir[] = 0;
            }

            $data_kry = DB::table('mst_karyawan')
                    ->whereNull('tanggal_keluar')
                    ->where('mst_karyawan.id_perusahaan', session()->get('sess_id_perusahaan'))
                    ->count('nik');
            if($data_kry){
                $dataC[] = $data_kry;
            }else{
                $dataC[] = 0;
            }
            
        }

        
        $data_[] = $dataHadir;
        $data_[] = $dataTelat;
        $data_[] = $dataCuti;
        $data_[] = $dataTHadir;
        $data_[] = $dataC;
        $data_[] = $blnL;
        echo json_encode($data_);
    }

    public function list_chart_absensi_telat(Request $request)
    {
        $bln = 12;
        $tahun = date('Y') - 1;
        $tahun_now = date('Y');
        $bulan_now = date('m');
        $dataC = array();
        $dataTelat = array();
        $blnL = array();

        for ($i=1; $i <= $bln; $i++) {
            $tgl_01 = date('Y-m-t', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $bln_filter = date("n", strtotime(date('Y-m-01')." +".intval($i)." month"));
            $tgl_awal = date('Y-m-01 00:00:00', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $tgl_akhir = date('Y-m-t 00:00:00', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $tahun = date('Y', strtotime("+".intval($i)." month -1 year"));

            $sumTelat = DB::table('trx_absen_office')
                        ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir])
                        ->where('status', 'Telat')
                        ->count('nik');

            if($sumTelat){
                $dataTelat[] = $sumTelat;
            }else{
                $dataTelat[] = 0;
            }

            $dataC[] = 0;
        }

        $data_[] = $dataC;
        $data_[] = $dataTelat;
        $data_[] = $blnL;
        echo json_encode($data_);
    }

    public function list_chart_absensi_cuti(Request $request)
    {
        $bln = 12;
        $tahun = date('Y') - 1;
        $tahun_now = date('Y');
        $bulan_now = date('m');
        $dataC = array();
        $dataCuti = array();
        $blnL = array();

        for ($i=1; $i <= $bln; $i++) {
            $tgl_01 = date('Y-m-t', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $bln_filter = date("n", strtotime(date('Y-m-01')." +".intval($i)." month"));
            $tgl_awal = date('Y-m-01 00:00:00', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $tgl_akhir = date('Y-m-t 00:00:00', strtotime(date('Y-m-01')." +".intval($i)." month -1 year"));
            $tahun = date('Y', strtotime("+".intval($i)." month -1 year"));

            $blnL[] = $this->month_to_text_3($bln_filter) . ' ' . $this->right($tahun, 2);

            $sumCuti = DB::table('trx_absen_office')
                        ->whereBetween('tanggal', [$tgl_awal, $tgl_akhir])
                        ->where('status', 'Cuti')
                        ->count('nik');

            if($sumCuti){
                $dataCuti[] = $sumCuti;
            }else{
                $dataCuti[] = 0;
            }

            $dataC[] = 0;
        }

        $data_[] = $dataC;
        $data_[] = $dataCuti;
        $data_[] = $blnL;
        echo json_encode($data_);
    }

    public function notifikasi(Request $request)
    {
        $data = DB::table('mst_karyawan')
                ->where('status_karyawan', 'Kontrak')
                ->wherenull('tanggal_keluar')
                ->get();
        $dataArr = array();
        $nrpArr = array();
        $nikArr = array();
        $namaArr = array();
        $tgl_kontrakArr = array();
        $tgl_kontrak7 = date('Y-m-d', strtotime(' - 7 days'));
        $selisihArr = array();
        $i = 0;
        foreach ($data as $key => $value) {
            $nrp = $value->nrp;
            $nik = $value->nik;
            $nama = $value->nama;
            $tanggal_masuk = $value->tanggal_masuk;
            $tanggal_keluar = $value->tanggal_keluar;
            $kontak_bulan = $value->kontak_bulan;
            $tgl_habis_kontrak = date('Y-m-d', strtotime($tanggal_masuk.' + '.$kontak_bulan.' months'));
            $selisih = date_diff(new DateTime($tgl_habis_kontrak), new DateTime(date('Y-m-d')))->format('%R%a');
            if(floatval($selisih) >= -7){
                if(floatval($selisih) <= 7){
                    $nrpArr['nrp'] = $nrp;
                    $nrpArr['nik'] = $nik;
                    $nrpArr['nama'] = $nama;
                    $nrpArr['tgl_kontrak'] = $tgl_habis_kontrak;
                    $nrpArr['selisih'] = $selisih;

                    $dataArr[] = $nrpArr;
                    $i++;
                }
                
                // $nikArr['nik'] = $nik;
                // $namaArr['nama'] = $nama;
                // $tgl_kontrakArr['tgl_kontrak'] = $tgl_habis_kontrak;
                // $selisihArr['selisih'] = $selisih;
                
            }
            
        }
        // $dataArr[] = $nrpArr;
        // $dataArr[] = $nikArr;
        // $dataArr[] = $namaArr;
        // $dataArr[] = $tgl_kontrakArr;
        // $dataArr[] = $selisihArr;
        return response()->json(['success' => true,'data' => $dataArr, 'jml_notif'=>$i]);
    }
}
