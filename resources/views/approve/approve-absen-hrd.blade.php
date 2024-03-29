@section('title', 'Approval Ketidakhadiran')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">Approval Ketidakhadiran</h1>
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="text-center">Data Ketidakhadiran</h2>
                                        <div class="table-responsive">
                                            <table id="tbl_approve" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>ID KARYAWAN</th>
                                                        <th>NAMA KARYAWAN</th>
                                                        <th>JENIS</th>
                                                        <th>MULAI</th>
                                                        <th>SAMPAI</th>
                                                        <th>JUMLAH HARI</th>
                                                        <th>KETERANGAN</th>
                                                        <th>LAMPIRAN FILE</th>
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
<script type="text/javascript" src="{{ asset('/js/approve/approve-absen-hrd.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    $("div#master").siblings('a').attr('aria-expanded', 'true');
    $("div#master").addClass("show");
    $("div#master #master-devisi").addClass("active");
    $("#nav-master").addClass("active");
</script>