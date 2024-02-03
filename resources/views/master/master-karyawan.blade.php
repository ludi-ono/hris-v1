@section('title', 'Master Karyawan')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">MASTER KARYAWAN</h1>
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
                                <div class="col-md-12 text-left">
                                    <div class="form-group">
                                        <a id="tambah_data" name="tambah_data" style="color:white" class="btn btn-primary"><i class="fas fa-plus"></i> &nbsp; Tambah Data</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="text-center">Data Karyawan</h2>
                                        <div class="table-responsive">
                                            <table id="tbl_karyawan" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NRP</th>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>TGL. MASUK</th>
                                                        <th>ALAMAT</th>
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
        <div id="add_data" class="page-inner mt--5">
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Tambah Data Karyawan</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nrp">NRP <span style="color: red;">*</span></label>
                                        <input id="nrp" name="nrp" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nik">NIK <span style="color: red;">*</span></label>
                                        <input id="nik" name="nik" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tgl_masuk">Tgl. Masuk <span style="color: red;">*</span></label>
                                        <input id="tgl_masuk" name="tgl_masuk" class="form-control" type="date" />
                                    </div>
                                </div>
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tgl_keluar">Tgl. Keluar </label>
                                        <input id="tgl_keluar" name="tgl_keluar" class="form-control" type="date" />
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_karyawan">Status Karyawan <span style="color: red;">*</span></label>
                                        <select id="status_karyawan" name="status_karyawan" class="form-control">
                                            <option value="Kontrak">Kontrak</option>
                                            <option value="Karyawan">Karyawan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="perusahaan">Pilih Perusahaan <span style="color: red;">*</span></label>
                                        <select id="perusahaan" name="perusahaan" class="form-control">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jabatan">Pilih Jabatan <span style="color: red;">*</span></label>
                                        <select id="jabatan" name="jabatan" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="status_approve"><span style="color:blue">Pilih Jenis Approval </span><span style="color: red;">*</span></label>
                                        <select id="status_approve" name="status_approve" class="form-control">
                                            <option>
                                                <==Pilih Jenis Approve==>
                                            </option>
                                            <option value="0">
                                                Bukan Approval
                                            </option>
                                            <option value="3">
                                                Approval General Manager
                                            </option>
                                            <option value="2">
                                                Approval Manager
                                            </option>
                                            <option value="1">
                                                Approval HRD
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="nama">Nama <span style="color: red;">*</span></label>
                                        <input id="nama" name="nama" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pendidikan">Pendidikan Terakhir<span style="color: red;">*</span></label>
                                        <select id="pendidikan" name="pendidikan" class="form-control">
                                            <option value='SD'>SD</option>
                                            <option value='SMP'>SMP</option>
                                            <option value='SMA'>SMA</option>
                                            <option value='D3'>D3</option>
                                            <option value='S1'>S1</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin <span style="color: red;">*</span></label>
                                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tempat_lahir">Tempat Lahir <span style="color: red;">*</span></label>
                                        <input id="tempat_lahir" name="tempat_lahir" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tgl_lahir">Tgl. Lahir <span style="color: red;">*</span></label>
                                        <input id="tgl_lahir" name="tgl_lahir" class="form-control" type="date" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status Perkawinan<span style="color: red;">*</span></label>
                                        <select id="status" name="status" class="form-control">
                                            <option value='Belum Kawin'>Belum Kawin</option>
                                            <option value='Kawin'>Kawin</option>
                                            <option value='Cerai Hidup'>Cerai Hidup</option>
                                            <option value='Cerai Mati'>Cerai Mati</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama_pasangan">Suami/Istri <span style="color: red;">*</span></label>
                                        <input id="nama_pasangan" name="nama_pasangan" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama_ibu">Nama Ibu <span style="color: red;">*</span></label>
                                        <input id="nama_ibu" name="nama_ibu" class="form-control" type="text" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat <span style="color: red;">*</span></label>
                                        <textarea id="alamat" name="alamat" class="form-control" type="text" row="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="alamat_domisili">Alamat Domisili <span style="color: red;">*</span></label>
                                        <textarea id="alamat_domisili" name="alamat_domisili" class="form-control" type="text" row="3" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_hp">No. Hp <span style="color: red;">*</span></label>
                                        <input id="no_hp" name="no_hp" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email </label>
                                        <input id="email" name="email" class="form-control" type="email" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ktp">KTP <span style="color: red;">*</span></label>
                                        <input id="ktp" name="ktp" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="npwp">NPWP <span style="color: red;">*</span></label>
                                        <input id="npwp" name="npwp" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-12" style="text-align:center">
                                    <hr>
                                    <!-- <div class="form-group"> -->
                                    <!-- <label for="image">Upload File <span style="color: red;">*</span></label> -->
                                    <h3 style="color:#002eff"><b>Upload Dokumen Karyawan</b></h3>
                                    <!-- </div> -->
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="file_foto">Foto <span style="color: red;">*</span></label>
                                        <input type="file" name="file_foto" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="file_ktp">KTP <span style="color: red;">*</span></label>
                                        <input type="file" name="file_ktp" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="file_npwp">NPWP <span style="color: red;">*</span></label>
                                        <input type="file" name="file_npwp" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_ijazah">Ijazah <span style="color: red;">*</span></label>
                                        <input type="file" name="file_ijazah" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_sertifikat">Sertifikat </label>
                                        <input type="file" name="file_sertifikat" class="form-control">
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
<script type="text/javascript" src="{{ asset('/js/master/karyawan.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    $("div#master").siblings('a').attr('aria-expanded', 'true');
    $("div#master").addClass("show");
    $("div#master #master-karyawan").addClass("active");
    $("#nav-master").addClass("active");
</script>