@section('title', 'Master User')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-primary">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">MASTER USER</h1>
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
                                        <h2 class="text-center">Data Semua User</h2>
                                        <div class="table-responsive">
                                            <table id="tbl_user" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#16b3ac">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NIP</th>
                                                        <th>NAMA</th>
                                                        <th>GOLONGAN</th>
                                                        <th>DIREKTORAT</th>
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
                                <input type="hidden" id="id" name="id">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Tambah Data Supplier</h2>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <p style="font-size:16px" class="font-weight-bold text-center">Unit Kerja : {{ Session::get('sess_nama_direktorat') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nip">NIP <span style="color: red;">*</span></label>
                                        <input id="nip" name="nip" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="golongan">Golongan <span style="color: red;">*</span></label>
                                        <select id="golongan" name="golongan" class="form-control" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama <span style="color: red;">*</span></label>
                                        <input id="nama" name="nama" class="form-control" type="text" />
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
            2021, made by <a href="https://www.kemkes.go.id">Kementerian Kesehatan</a>
        </div>
    </div>
</footer>
</div>
@include('layouts.footer')
<script type="text/javascript" src="{{ asset('/js/master/users.js') }}"></script>