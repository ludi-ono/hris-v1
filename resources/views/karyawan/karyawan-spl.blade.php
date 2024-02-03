@section('title', 'Karyawan SPL')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">Karyawan - SPL</h1>
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
                                        <h2 class="text-center">Daftar Spl</h2>
                                        <div class="table-responsive">
                                            <table id="tbl_spl" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NOMOR DOKUMEN</th>
                                                        <th>TANGGAL LEMBUR</th>
                                                        <th>KETERANGAN</th>
                                                        <th>STATUS</th>
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
        <div id="add_data" class="page-inner mt--5" style="display: none;">
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
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Tambah Data Devisi</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_dokumen">No Dokumen <span style="color: red;">*</span></label>
                                        <select id="no_dokumen" name="no_dokumen" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="karyawan">Karyawan <span style="color: red;">*</span></label>
                                        <!-- <div class="input-icon">
                                            <input type="text" class="form-control" name="karyawan">
                                            <span class="input-icon-addon">
                                                <i class="fa fa-search"></i>
                                            </span>
                                        </div> -->
                                        <div class="input-group mb-3">
                                            <input type="hidden" class="form-control" id="id_karyawan" name="id_karyawan">
                                            <input type="text" class="form-control" id="karyawan" name="karyawan">
                                            <div class="input-group-append">
                                                <button class="btn btn-default btn-border browe_karyawan" type="button"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tgl_lembur">Tanggal Lembur <span style="color: red;">*</span></label>
                                        <input id="tgl_lembur" name="tgl_lembur" class="form-control" type="date" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jam_masuk">Jam Masuk <span style="color: red;">*</span></label>
                                        <input id="jam_masuk" name="jam_masuk" class="form-control" type="time" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jam_keluar">Jam Keluar <span style="color: red;">*</span></label>
                                        <input id="jam_keluar" name="jam_keluar" class="form-control" type="time" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan <span style="color: red;">*</span></label>
                                        <textarea id="keterangan" name="keterangan" class="form-control" cols="3"></textarea>
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

        <div id="modal_browse_karyawan" class="modal hide" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Pilih Karyawan</h4>
                    </div>
                    <div class="modal-body" style="overflow: hidden;">
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <a id="pilih_karyawan" style="color:white" class="btn btn-info "><i class="fas fa-save"></i> &nbsp; Simpan</a>
                                <a id="batal_karyawan" style="color:white" class="btn btn-danger "><i class="fas fa-times-circle"></i> &nbsp; Batal</a>
                            </div>
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
<script type="text/javascript" src="{{ asset('/js/karyawan/spl.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    $("div#master").siblings('a').attr('aria-expanded', 'true');
    $("div#master").addClass("show");
    $("div#master #master-devisi").addClass("active");
    $("#nav-master").addClass("active");
</script>