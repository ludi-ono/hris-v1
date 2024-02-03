@section('title', 'Karyawan - Cuti')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">Karyawan - Cuti</h1>
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
                                        <h2 class="text-center">Daftar Cuti</h2>
                                        <div class="table-responsive">
                                            <table id="tbl_cuti" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NRP</th>
                                                        <th>Nama Karyawan</th>
                                                        <th>Nomor Dokumen</th>
                                                        <th>Mulai</th>
                                                        <th>Sampai</th>
                                                        <th>Jumlah Hari</th>
                                                        <th>Status</th>
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
        <div id="add_data" class="page-inner mt--5" style="display:none;">
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
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Tambah Pengajuan Cuti</h2>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mulai">Mulai Cuti <span style="color: red;">*</span></label>
                                        <input id="mulai" name="mulai" class="form-control" type="date" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sampai">Sampai Dengan <span style="color: red;">*</span></label>
                                        <input id="sampai" name="sampai" class="form-control" type="date" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="file">Lampiran File <span style="color: red;">*</span></label>
                                        <input id="file" name="file" class="form-control" type="file" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
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

        <div id="detail_data" class="page-inner mt--5" style="display:none;">
            <div class="row mt--2">
                <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <form class="row">
                                <div class="col-md-12 text-left">
                                    <div class="form-group">
                                        <a id="back_d" style="color:white" class="btn btn-primary"><i class="fas fa-arrow-left"></i> &nbsp; Kembali</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center">Detail Data Cuti</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_dok">No. Dokumen</label>
                                        <input type="text" class="form-control" id="no_dok" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tgl_cuti">Tgl. Cuti</label>
                                        <input type="text" class="form-control" id="tgl_cuti" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="keterangan_d">Keterangan</label>
                                        <textarea id="keterangan_d" class="form-control" rows="3" disabled></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <!-- <iframe id="file_show" src="{{asset('/file_upload/C-63fad1dac3d8d.pdf')}}" width="100%" height="100%"></iframe> -->
                                        <input type="hidden" class="form-control" id="file_show">
                                        <a id="show_lampiran" href="javascript:void(0)">Klik untuk melihat lampiran</a>
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
<script type="text/javascript" src="{{ asset('/js/karyawan/cuti.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    $("div#master").siblings('a').attr('aria-expanded', 'false');
    // $("div#master").addClass("show");
    // $("div#master #master-karyawan").addClass("active");
    // $("#nav-master").addClass("active");
    $("#nav-karyawan-cuti").addClass("active");
</script>