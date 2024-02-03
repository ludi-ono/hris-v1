@section('title', 'Rekap Absensi')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">Rekap Absensi Karyawan</h1>
                    </div>
                </div>
            </div>
        </div>
        <div id="list_data" class="page-inner mt--5">
            <div class="row mt--2">
                <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tahun">Pilih Tahun <span style="color: red;">*</span></label>
                                        <select id="tahun" name="tahun" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bulan">Pilih Bulan <span style="color: red;">*</span></label>
                                        <select id="bulan" name="bulan" class="form-control">
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="devisi">Pilih Devisi <span style="color: red;">*</span></label>
                                        <select id="devisi" name="devisi" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <center>
                                        <a id="refresh_data" name="refresh_data" style="color:white" class="btn btn-success"><i class="fas fa-refresh"></i> &nbsp; Refresh Data</a>
                                    </center>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="tbl_rekap_absensi" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NRP KARYAWAN</th>
                                                        <th>NAMA KARYAWAN</th>
                                                        <th>TANGGAL</th>
                                                        <th>JAM MASUK</th>
                                                        <th>JAM KELUAR</th>
                                                        <th>KETERANGAN</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="add_data" class="page-inner mt--5" style="display:none">
            <div class="row mt--2">
                <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <form class="row" id="form_input">
                                <div class="col-md-12 text-left">
                                    <div class="form-group">
                                        <a id="back" name="back" style="color:white" class="btn btn-primary"><i class="fas fa-arrow-left"></i> &nbsp; Kembali</a>
                                    </div>
                                </div>
                                <input type="hidden" name="state" id="state">
                                <input type="hidden" id="sysid" name="sysid">
                                <input type="hidden" id="id_karyawan" name="id_karyawan">
                                <input type="hidden" id="id_perusahaan" name="id_perusahaan" value="{{ Session::get('sess_id_perusahaan') }}">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Tambah Data Instansi</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Gaji Pokok <span style="color: red;">*</span></label>
                                        <input id="gapok" name="gapok" class="form-control" type="text" onClick="this.select();"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Presensi <span style="color: red;">*</span></label>
                                        <input id="presensi" name="presensi" class="form-control" type="text" onClick="this.select();"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Tunj. Jabatan </label>
                                        <input id="tunj_jabatan" name="tunj_jabatan" class="form-control" type="text" onClick="this.select();"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Tunj. Pulsa </label>
                                        <input id="tunj_pulsa" name="tunj_pulsa" class="form-control" type="text" onClick="this.select();"/>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <a id="save" name="save" style="color:white" class="btn btn-primary"><i class="fas fa-save"></i> &nbsp; Simpan</a>
                                        <a id="batal" name="batal" style="color:white" class="btn btn-danger"><i class="fa fa-times-circle"></i> &nbsp; Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container-fluid">
        <div class="copyright ml-auto">
            2021, made by <a href="https://www.kemkes.go.id">GIS</a>
        </div>
    </div>
</footer>
</div>
@include('layouts.footer')
<script type="text/javascript" src="{{ asset('/js/absensi/rekap-absensi-office.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    $("div#master").siblings('a').attr('aria-expanded', 'false');
    // $("div#master").addClass("show");
    // $("div#master #master-jabatan").addClass("active");
    $("#nav-thp-karyawan").addClass("active");
</script>