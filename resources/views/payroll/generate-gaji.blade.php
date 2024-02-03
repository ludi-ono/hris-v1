@section('title', 'Gaji Karyawan')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">Gaji Karyawan</h1>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <a id="tambah_data" name="tambah_data" style="color:white" class="btn btn-primary"><i class="fas fa-plus"></i> &nbsp; Tambah Data</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <!-- <h2 class="text-center">Data Devisi</h2> -->
                                        <div class="table-responsive">
                                            <table id="tbl_gaji_hdr" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NO DOKUMEN</th>
                                                        <th>TANGGAL GENERATE</th>
                                                        <th>BULAN</th>
                                                        <th>TAHUN</th>
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Generate Gaji</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun">Pilih Tahun</label>
                                        <select id="tahun" name="tahun" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bulan">Pilih Bulan</label>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="presensi">Pilih Periode Presensi</label>
                                        <select id="presensi" name="presensi" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lembur">Pilih Periode Lembur</label>
                                        <select id="lembur" name="lembur" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <a id="generate" class="btn btn-success" href="javascript::void(0)"><i class="fas fa-database"></i>&nbsp; Generate</a>
                                        <a id="reset" class="btn btn-warning" href="javascript::void(0)"><i class="fa-solid fa-rotate"></i>&nbsp; Reset</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <!-- <h2 class="text-center">Data Devisi</h2> -->
                                        <div class="table-responsive">
                                            <table id="tbl_gaji_dtl" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NAMA KARYAWAN</th>
                                                        <th>NAMA DEVISI</th>
                                                        <th>NILAI</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
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
        <div id="modal_detail" style="overflow-y: auto;" class="modal hide" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="overflow: hidden;">
                        <h4 style="font-weight:bold;color:#407290;text-shadow: 1px grey; text-align:center;" class="modal-title">Detail Lembur Periode : </h4>
                        <hr style='height:1px;border-width:0;color:black;background-color:black'>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <!-- <h2 class="text-center">Data Devisi</h2> -->
                                    <div class="table-responsive">
                                        <table id="tbl_gaji_detail" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                            <thead style="color:white;background:#468cff!important">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>NO</th>
                                                    <th>NAMA KARYAWAN</th>
                                                    <th>NAMA DEVISI</th>
                                                    <th>NILAI</th>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="p_cancel_validasi">
                            <span class="btn-label"><i class="ti-close"></i></span> Close
                        </button>
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
<script type="text/javascript" src="{{ asset('/js/payroll/gaji.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    // $("div#master").siblings('a').attr('aria-expanded', 'true');
    // $("div#master").addClass("show");
    // $("div#master #master-devisi").addClass("active");
    $("#nav-generate-gaji").addClass("active");
</script>