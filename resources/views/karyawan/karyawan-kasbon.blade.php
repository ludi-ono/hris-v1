@section('title', 'Pengajuan Kasbon')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">Karyawan - Kasbon</h1>
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
                                        <h2 class="text-center">Daftar Pengajuan Kasbon</h2>
                                        <div class="table-responsive">
                                            <table id="tbl_cuti" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NO DOKUMEN</th>
                                                        <th>NILAI KASBON</th>
                                                        <th>STATUS</th>
                                                        <th>Action</th>
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
                                <input id="nrp" name="nrp" class="form-control" value="{{ Session::get('sess_id_karyawan') }}" placeholder="{{ Session::get('sess_id_karyawan') }}" hidden />
                                <input id="nama_karyawan" name="nama_karyawan" class="form-control" value="{{ Session::get('sess_nama') }}" placeholder="{{ Session::get('sess_nama') }}" hidden />

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Tambah Pengajuan Kasbon</h2>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nilai">Nilai Pengajuan Kasbon<span style="color: red;">*</span></label>
                                        <input id="nilai" name="nilai" class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea id="keterangan" class="form-control" cols="3"></textarea>
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
<script type="text/javascript" src="{{ asset('/js/karyawan/kasbon.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    $("div#master").siblings('a').attr('aria-expanded', 'true');
    $("div#master").addClass("show");
    $("div#master #master-karyawan").addClass("active");
    $("#nav-master").addClass("active");
</script>