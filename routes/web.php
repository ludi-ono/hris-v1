<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/face-absensi', function () {
    return view('attendance.absensi-face');
});

Route::get('/file-penggajian', 'App\Http\Controllers\Karyawan\KaryawanPayrollController@pdf_file');

// Register //
Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@index')->name('register');
Route::post('/postregister', 'App\Http\Controllers\Auth\RegisterController@postregister')->name('postregister');
Route::get('/register/karyawan', 'App\Http\Controllers\Auth\RegisterController@karyawan')->name('register-karyawan');

// Login //
Route::get('/', 'App\Http\Controllers\Auth\LoginController@index')->name('login');
Route::post('/postlogin', 'App\Http\Controllers\Auth\LoginController@postlogin')->name('postlogin');
Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});
Route::get('/dashboard/list-absensi', 'App\Http\Controllers\DashboardController@list_chart_absensi');

// Route::get('/absen-karyawan', function () {
//     return view('absensigis.absensi-office');
// });
Route::get('/absen-karyawan', 'App\Http\Controllers\Absensi\KryOfficeController@index')->name('absensi-office');
Route::post('/absen-karyawan/import-file', 'App\Http\Controllers\Absensi\KryOfficeController@importFile')->name('absensi-office.import');
Route::get('/absen-karyawan/get-tmp', 'App\Http\Controllers\Absensi\KryOfficeController@get_data_tmp')->name('absensi-office.data.tmp');
Route::post('/absen-karyawan/store', 'App\Http\Controllers\Absensi\KryOfficeController@store')->name('absensi-office.store');
Route::post('/absen-karyawan/update', 'App\Http\Controllers\Absensi\KryOfficeController@update')->name('absensi-office.update');
Route::post('/absen-karyawan/delete-tmp', 'App\Http\Controllers\Absensi\KryOfficeController@delete_tmp')->name('absensi-office.deletetmp');
Route::get('/absen-karyawan/list-data-hdr', 'App\Http\Controllers\Absensi\KryOfficeController@list_data')->name('absensi-office.list.hdr');
Route::get('/absen-karyawan/list-data-dtl', 'App\Http\Controllers\Absensi\KryOfficeController@list_data_hdr')->name('absensi-office.list.dtl');
Route::post('/absen-karyawan/store-surat', 'App\Http\Controllers\Absensi\KryOfficeController@store_tidakhadir')->name('absensi-office.store.surat');

// Approve SPL Manager //
Route::get('/approve-spl-manager', 'App\Http\Controllers\Approve\ApproveManagerController@spl_manager')->name('approve-spl-manager');
Route::get('/approve-spl-manager/list-data-spl', 'App\Http\Controllers\Approve\ApproveManagerController@list_data_spl')->name('approve-spl-manager.list-data-spl');
Route::post('/approve-spl-manager/approve-spl/{id}', 'App\Http\Controllers\Approve\ApproveManagerController@approve_spl')->name('approve-spl-manager.approve-spl');
Route::post('/approve-spl-manager/reject-spl/{id}', 'App\Http\Controllers\Approve\ApproveManagerController@reject_spl')->name('approve-spl-manager.reject-spl');

// Approve SPL HRD //
Route::get('/approve-spl-hrd', 'App\Http\Controllers\Approve\ApproveHrdController@spl_hrd')->name('approve-spl-hrd');
Route::get('/approve-spl-hrd/list-data-spl', 'App\Http\Controllers\Approve\ApproveHrdController@list_data_spl')->name('approve-spl-hrd.list-data-spl');
Route::post('/approve-spl-hrd/approve-spl/{id}', 'App\Http\Controllers\Approve\ApproveHrdController@approve_spl')->name('approve-spl-hrd.approve-spl');
Route::post('/approve-spl-hrd/reject-spl/{id}', 'App\Http\Controllers\Approve\ApproveHrdController@reject_spl')->name('approve-spl-hrd.reject-spl');

// Approve Ketidakhadiran Manager //
Route::get('/approve-absen-manager', 'App\Http\Controllers\Approve\ApproveManagerController@absen_manager')->name('approve-absen-manager');
Route::get('/approve-absen-manager/list-data-absen', 'App\Http\Controllers\Approve\ApproveManagerController@list_data_absen')->name('approve-absen-manager.list-data-absen');
Route::post('/approve-absen-manager/approve-absen/{id}', 'App\Http\Controllers\Approve\ApproveManagerController@approve_absen')->name('approve-absen-manager.approve-absen');
Route::post('/approve-absen-manager/reject-absen/{id}', 'App\Http\Controllers\Approve\ApproveManagerController@reject_absen')->name('approve-absen-manager.reject-absen');

// Approve Ketidakhadiran HRD //
Route::get('/approve-absen-hrd', 'App\Http\Controllers\Approve\ApproveHrdController@absen_hrd')->name('approve-absen-hrd');
Route::get('/approve-absen-hrd/list-data-absen', 'App\Http\Controllers\Approve\ApproveHrdController@list_data_absen')->name('approve-absen-hrd.list-data-absen');
Route::post('/approve-absen-hrd/approve-absen/{id}', 'App\Http\Controllers\Approve\ApproveHrdController@approve_absen')->name('approve-absen-hrd.approve-absen');
Route::post('/approve-absen-hrd/reject-absen/{id}', 'App\Http\Controllers\Approve\ApproveHrdController@reject_absen')->name('approve-absen-hrd.reject-absen');

// Route::get('/master-user', function () {
//     return view('master.master-user');
// });

// Route::get('/master-perusahaan', 'App\Http\Controllers\Master\MasterJabatanController@index')->name('master-perusahaan');

// Master Perusahaan //
Route::get('/master-perusahaan', 'App\Http\Controllers\Master\MasterPerusahaanController@index')->name('master-perusahaan');
Route::get('/master-perusahaan/list-data', 'App\Http\Controllers\Master\MasterPerusahaanController@list_data')->name('master-perusahaan.list-data');
Route::post('/master-perusahaan/store', 'App\Http\Controllers\Master\MasterPerusahaanController@store')->name('master-perusahaan.store');
Route::post('/master-perusahaan/update', 'App\Http\Controllers\Master\MasterPerusahaanController@update')->name('master-perusahaan.update');

// Master Devisi //
Route::get('/master-devisi', 'App\Http\Controllers\Master\MasterDevisiController@index')->name('master-devisi');
Route::get('/master-devisi/list-data', 'App\Http\Controllers\Master\MasterDevisiController@list_data')->name('master-devisi.list-data');
Route::post('/master-devisi/store', 'App\Http\Controllers\Master\MasterDevisiController@store')->name('master-devisi.store');
Route::post('/master-devisi/update', 'App\Http\Controllers\Master\MasterDevisiController@update')->name('master-devisi.update');
Route::post('/master-devisi/destroy/{id}', 'App\Http\Controllers\Master\MasterDevisiController@destroy')->name('master-devisi.destroy');
Route::get('/master-devisi/perusahaan', 'App\Http\Controllers\Master\MasterDevisiController@perusahaan')->name('master-devisi.perusahaan');

// Master Jabatan //
Route::get('/master-jabatan', 'App\Http\Controllers\Master\MasterJabatanController@index')->name('master-jabatan');
Route::get('/master-jabatan/list-data', 'App\Http\Controllers\Master\MasterJabatanController@list_data')->name('master-jabatan.list-data');
Route::post('/master-jabatan/store', 'App\Http\Controllers\Master\MasterJabatanController@store')->name('master-jabatan.store');
Route::post('/master-jabatan/update', 'App\Http\Controllers\Master\MasterJabatanController@update')->name('master-jabatan.update');
Route::post('/master-jabatan/destroy/{id}', 'App\Http\Controllers\Master\MasterJabatanController@destroy')->name('master-jabatan.destroy');
Route::get('/master-jabatan/perusahaan', 'App\Http\Controllers\Master\MasterJabatanController@perusahaan')->name('master-jabatan.perusahaan');
Route::get('/master-jabatan/devisi', 'App\Http\Controllers\Master\MasterJabatanController@devisi')->name('master-jabatan.devisi');
Route::get('/master-jabatan/jabatan', 'App\Http\Controllers\Master\MasterJabatanController@jabatan')->name('master-jabatan.jabatan');

// Master Karyawan //
Route::get('/master-karyawan', 'App\Http\Controllers\Master\MasterKaryawanController@index')->name('master-karyawan');
Route::get('/master-karyawan/list-data', 'App\Http\Controllers\Master\MasterKaryawanController@list_data')->name('master-karyawan.list-data');
Route::post('/master-karyawan/store', 'App\Http\Controllers\Master\MasterKaryawanController@store')->name('master-karyawan.store');
Route::post('/master-karyawan/update', 'App\Http\Controllers\Master\MasterKaryawanController@update')->name('master-karyawan.update');
Route::post('/master-karyawan/destroy/{id}', 'App\Http\Controllers\Master\MasterKaryawanController@destroy')->name('master-karyawan.destroy');
Route::get('/master-karyawan/get-data', 'App\Http\Controllers\Master\MasterKaryawanController@get_data')->name('master-karyawan.get-data');

// THP Karyawan //
Route::get('/thp-karyawan', 'App\Http\Controllers\Master\MasterThpController@index')->name('thp-karyawan');
Route::get('/thp-karyawan/list-data', 'App\Http\Controllers\Master\MasterThpController@list_data')->name('thp-karyawan.list-data');
Route::post('/thp-karyawan/store', 'App\Http\Controllers\Master\MasterThpController@store')->name('thp-karyawan.store');
Route::post('/thp-karyawan/update', 'App\Http\Controllers\Master\MasterThpController@update')->name('thp-karyawan.update');

// BPJS Kesehatan //
Route::get('/bpjs-kesehatan', 'App\Http\Controllers\Master\BpjsKesehatanController@index')->name('bpjs-kesehatan');
Route::get('/bpjs-kesehatan/list-data', 'App\Http\Controllers\Master\BpjsKesehatanController@list_data')->name('bpjs-kesehatan.list-data');
Route::post('/bpjs-kesehatan/store', 'App\Http\Controllers\Master\BpjsKesehatanController@store')->name('bpjs-kesehatan.store');
Route::post('/bpjs-kesehatan/update', 'App\Http\Controllers\Master\BpjsKesehatanController@update')->name('bpjs-kesehatan.update');
Route::post('/bpjs-kesehatan/destroy/{id}', 'App\Http\Controllers\Master\BpjsKesehatanController@destroy')->name('bpjs-kesehatan.destroy');

// BPJS Ketenagakerjaan //
Route::get('/bpjs-ketenagakerjaan', 'App\Http\Controllers\Master\BpjsKetenagakerjaanController@index')->name('bpjs-ketenagakerjaan');
Route::get('/bpjs-ketenagakerjaan/list-data', 'App\Http\Controllers\Master\BpjsKetenagakerjaanController@list_data')->name('bpjs-ketenagakerjaan.list-data');
Route::post('/bpjs-ketenagakerjaan/store', 'App\Http\Controllers\Master\BpjsKetenagakerjaanController@store')->name('bpjs-ketenagakerjaan.store');
Route::post('/bpjs-ketenagakerjaan/update', 'App\Http\Controllers\Master\BpjsKetenagakerjaanController@update')->name('bpjs-ketenagakerjaan.update');
Route::post('/bpjs-ketenagakerjaan/destroy/{id}', 'App\Http\Controllers\Master\BpjsKetenagakerjaanController@destroy')->name('bpjs-ketenagakerjaan.destroy');

// SPL //
Route::get('/karyawan-spl', 'App\Http\Controllers\Karyawan\KaryawanSplController@index')->name('karyawan-spl');
Route::get('/karyawan-spl/list-data', 'App\Http\Controllers\Karyawan\KaryawanSplController@list_data')->name('karyawan-spl.list-data');
Route::post('/karyawan-spl/store', 'App\Http\Controllers\Karyawan\KaryawanSplController@store')->name('karyawan-spl.store');
Route::post('/karyawan-spl/update', 'App\Http\Controllers\Karyawan\KaryawanSplController@update')->name('karyawan-spl.update');
Route::post('/karyawan-spl/destroy/{id}', 'App\Http\Controllers\Karyawan\KaryawanSplController@destroy')->name('karyawan-spl.destroy');
Route::get('/karyawan-spl/get-data', 'App\Http\Controllers\Karyawan\KaryawanSplController@get_data')->name('karyawan-spl.get-data');


// Cuti //
Route::get('/karyawan-cuti', 'App\Http\Controllers\Karyawan\KaryawanCutiController@index')->name('karyawan-cuti');
Route::get('/karyawan-cuti/list-data', 'App\Http\Controllers\Karyawan\KaryawanCutiController@list_data')->name('karyawan-cuti.list-data');
Route::post('/karyawan-cuti/store', 'App\Http\Controllers\Karyawan\KaryawanCutiController@store')->name('karyawan-cuti.store');
Route::post('/karyawan-cuti/update', 'App\Http\Controllers\Karyawan\KaryawanCutiController@update')->name('karyawan-cuti.update');
Route::post('/karyawan-cuti/destroy/{id}', 'App\Http\Controllers\Karyawan\KaryawanCutiController@destroy')->name('karyawan-cuti.destroy');
Route::get('/karyawan-cuti/get-data', 'App\Http\Controllers\Karyawan\KaryawanCutiController@get_data')->name('karyawan-cuti.get-data');

// Izin //
Route::get('/karyawan-izin', 'App\Http\Controllers\Karyawan\KaryawanIzinController@index')->name('karyawan-izin');
Route::get('/karyawan-izin/list-data', 'App\Http\Controllers\Karyawan\KaryawanIzinController@list_data')->name('karyawan-izin.list-data');
Route::post('/karyawan-izin/store', 'App\Http\Controllers\Karyawan\KaryawanIzinController@store')->name('karyawan-izin.store');
Route::post('/karyawan-izin/update', 'App\Http\Controllers\Karyawan\KaryawanIzinController@update')->name('karyawan-izin.update');
Route::post('/karyawan-izin/destroy/{id}', 'App\Http\Controllers\Karyawan\KaryawanIzinController@destroy')->name('karyawan-izin.destroy');
Route::get('/karyawan-izin/get-data', 'App\Http\Controllers\Karyawan\KaryawanIzinController@get_data')->name('karyawan-izin.get-data');

// Sakit //
Route::get('/karyawan-sakit', 'App\Http\Controllers\Karyawan\KaryawanSakitController@index')->name('karyawan-sakit');
Route::get('/karyawan-sakit/list-data', 'App\Http\Controllers\Karyawan\KaryawanSakitController@list_data')->name('karyawan-sakit.list-data');
Route::post('/karyawan-sakit/store', 'App\Http\Controllers\Karyawan\KaryawanSakitController@store')->name('karyawan-sakit.store');
Route::post('/karyawan-sakit/update', 'App\Http\Controllers\Karyawan\KaryawanSakitController@update')->name('karyawan-sakit.update');
Route::post('/karyawan-sakit/destroy/{id}', 'App\Http\Controllers\Karyawan\KaryawanSakitController@destroy')->name('karyawan-sakit.destroy');
Route::get('/karyawan-sakit/get-data', 'App\Http\Controllers\Karyawan\KaryawanSakitController@get_data')->name('karyawan-sakit.get-data');

// Kasbon //
Route::get('/karyawan-kasbon', 'App\Http\Controllers\Karyawan\KaryawanKasbonController@index')->name('karyawan-kasbon');
Route::get('/karyawan-kasbon/list-data', 'App\Http\Controllers\Karyawan\KaryawanKasbonController@list_data')->name('karyawan-kasbon.list-data');
Route::post('/karyawan-kasbon/store', 'App\Http\Controllers\Karyawan\KaryawanKasbonController@store')->name('karyawan-kasbon.store');
Route::post('/karyawan-kasbon/update', 'App\Http\Controllers\Karyawan\KaryawanKasbonController@update')->name('karyawan-kasbon.update');
Route::post('/karyawan-kasbon/destroy/{id}', 'App\Http\Controllers\Karyawan\KaryawanKasbonController@destroy')->name('karyawan-kasbon.destroy');
Route::get('/karyawan-kasbon/get-data', 'App\Http\Controllers\Karyawan\KaryawanKasbonController@get_data')->name('karyawan-kasbon.get-data');

// Presensi //
Route::get('/generate-presensi', 'App\Http\Controllers\Payroll\PresensiController@index')->name('generate-presensi');
Route::get('/generate-presensi/list-data', 'App\Http\Controllers\Payroll\PresensiController@list_data')->name('generate-presensi.list-data');
Route::get('/generate-presensi/list-data-dtl', 'App\Http\Controllers\Payroll\PresensiController@list_data_dtl')->name('generate-presensi.list-data-dtl');
Route::get('/generate-presensi/list-data-tmp', 'App\Http\Controllers\Payroll\PresensiController@list_data_tmp')->name('generate-presensi.list-data-tmp');
Route::post('/generate-presensi/store', 'App\Http\Controllers\Payroll\PresensiController@store')->name('generate-presensi.store');
Route::post('/generate-presensi/generate', 'App\Http\Controllers\Payroll\PresensiController@generate')->name('generate-presensi.generate');
Route::post('/generate-presensi/destroy/{id}', 'App\Http\Controllers\Payroll\PresensiController@destroy')->name('generate-presensi.destroy');
Route::post('/generate-presensi/destroy-dtl/{id}', 'App\Http\Controllers\Payroll\PresensiController@destroy_dtl')->name('generate-presensi.destroydtl');
Route::post('/generate-presensi/destroy-tmp', 'App\Http\Controllers\Payroll\PresensiController@destroy_tmp')->name('generate-presensi.destroytmp');

// Lembur //
Route::get('/generate-lembur', 'App\Http\Controllers\Payroll\LemburController@index')->name('generate-lembur');
Route::get('/generate-lembur/list-data', 'App\Http\Controllers\Payroll\LemburController@list_data')->name('generate-lembur.list-data');
Route::get('/generate-lembur/list-data-dtl', 'App\Http\Controllers\Payroll\LemburController@list_data_dtl')->name('generate-lembur.list-data-dtl');
Route::get('/generate-lembur/list-data-tmp', 'App\Http\Controllers\Payroll\LemburController@list_data_tmp')->name('generate-lembur.list-data-tmp');
Route::post('/generate-lembur/store', 'App\Http\Controllers\Payroll\LemburController@store')->name('generate-lembur.store');
Route::post('/generate-lembur/generate', 'App\Http\Controllers\Payroll\LemburController@generate')->name('generate-lembur.generate');
Route::post('/generate-lembur/destroy/{id}', 'App\Http\Controllers\Payroll\LemburController@destroy')->name('generate-lembur.destroy');
Route::post('/generate-lembur/destroy-dtl/{id}', 'App\Http\Controllers\Payroll\LemburController@destroy_dtl')->name('generate-lembur.destroydtl');
Route::post('/generate-lembur/destroy-tmp', 'App\Http\Controllers\Payroll\LemburController@destroy_tmp')->name('generate-lembur.destroytmp');

// Gaji //
Route::get('/generate-gaji', 'App\Http\Controllers\Payroll\GajiController@index')->name('generate-gaji');
Route::get('/generate-gaji/list-data', 'App\Http\Controllers\Payroll\GajiController@list_data')->name('generate-gaji.list-data');
Route::get('/generate-gaji/list-data-dtl', 'App\Http\Controllers\Payroll\GajiController@list_data_dtl')->name('generate-gaji.list-data-dtl');
Route::get('/generate-gaji/list-data-tmp', 'App\Http\Controllers\Payroll\GajiController@list_data_tmp')->name('generate-gaji.list-data-tmp');
Route::post('/generate-gaji/store', 'App\Http\Controllers\Payroll\GajiController@store')->name('generate-gaji.store');
Route::post('/generate-gaji/get-data-presensi', 'App\Http\Controllers\Payroll\GajiController@get_data_presensi')->name('generate-gaji.get-presensi');
Route::post('/generate-gaji/get-data-lembur', 'App\Http\Controllers\Payroll\GajiController@get_data_lembur')->name('generate-gaji.get-lembur');
Route::post('/generate-gaji/generate', 'App\Http\Controllers\Payroll\GajiController@generate')->name('generate-gaji.generate');
Route::post('/generate-gaji/destroy/{id}', 'App\Http\Controllers\Payroll\GajiController@destroy')->name('generate-gaji.destroy');
Route::post('/generate-gaji/destroy-dtl/{id}', 'App\Http\Controllers\Payroll\GajiController@destroy_dtl')->name('generate-gaji.destroydtl');
Route::post('/generate-gaji/destroy-tmp', 'App\Http\Controllers\Payroll\GajiController@destroy_tmp')->name('generate-gaji.destroytmp');

// Penggajian //
Route::get('/slip-gaji', 'App\Http\Controllers\Payroll\PenggajianController@index')->name('slip-gaji');
Route::get('/slip-gaji/list-data', 'App\Http\Controllers\Payroll\PenggajianController@list_data');
Route::get('/slip-gaji/list-data-dtl', 'App\Http\Controllers\Payroll\PenggajianController@list_data_dtl');
Route::get('/slip-gaji/print/{tahun}/{bulan}/{nrp}', 'App\Http\Controllers\Payroll\PenggajianController@print_slip');

// Rekap Absensi
Route::get('/rekap-absensi-office', 'App\Http\Controllers\Absensi\RekapAbsensiController@index')->name('rekap-absensi-office');
Route::get('/rekap-absensi-office/list-data', 'App\Http\Controllers\Absensi\RekapAbsensiController@list_data');

Route::get('/dashboard/notifikasi', 'App\Http\Controllers\DashboardController@notifikasi');

//Clear Cache facade value:
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});