@section('title', 'Absensi Office')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">ABSENSI KARYAWAN OFFICE</h1>
                    </div>
                </div>
            </div>
        </div>
        <div id="list_data" class="page-inner mt--5">
            <div id="show_header" class="row mt--2">
                <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <a id="tambah_data" name="tambah_data" style="color:white" class="btn btn-success"><i class="fa-regular fa-file-excel"></i> &nbsp; Upload Dokumen Excell</a>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="form-group">
                                        <p style="color:red"><b>Last Upload : 21-01-2023</b></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="tbl_absensi_office_hdr" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>FILE UPLOAD</th>
                                                        <th>TANGGAL</th>
                                                        <th>USER</th>
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

            <div id="show_detail" class="row mt--2" style="display:none;">
                <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 text-left">
                                    <div class="form-group">
                                        <a id="back_show" style="color:white" class="btn btn-primary"><i class="fas fa-arrow-left"></i> &nbsp; Kembali</a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" id="id_hdr">
                                        <div class="table-responsive">
                                            <table id="tbl_absensi_office" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NO</th>
                                                        <th>NIK</th>
                                                        <th>NAMA KARYAWAN</th>
                                                        <th>TANGGAL</th>
                                                        <th>JAM MASUK</th>
                                                        <th>JAM KELUAR</th>
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
                                <input type="hidden" name="filename" id="filename">
                                <div class="col-md-12 text-left">
                                    <div class="form-group">
                                        <a id="back" name="back" style="color:white" class="btn btn-primary"><i class="fas fa-arrow-left"></i> &nbsp; Kembali</a>
                                    </div>
                                </div>
                                <!-- <input type="hidden" name="state" id="state">
                                <input type="hidden" id="sysid" name="sysid"> -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Import Data Absen</h2>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="file">File Excell</label>
                                        <input type="file" id="file" name="file" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <a href="javascript:void(0)" class="btn btn-success" id="import_absensi">
                                            <i clas="fa fa-download"></i> Submit
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table id="tbl_excell" class="table table-hover table-striped dt-responsive nowrap" style="width:100%;">
                                                <thead style="color:white;background:#468cff!important">
                                                    <tr>
                                                        <td>Nomor Absensi</td>
                                                        <td>Nama Karyawan</td>
                                                        <td>Tanggal</td>
                                                        <td>Jam Masuk</td>
                                                        <td>Jam Keluar</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="show_data_absen">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <a id="save_data" style="color:white" class="btn btn-primary"><i class="fas fa-save"></i> &nbsp; Simpan</a>
                                        <a id="batal_data" style="color:white" class="btn btn-danger"><i class="fa fa-times-circle"></i> &nbsp; Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="edit_data" class="page-inner mt--5" style="display: none;">
            <div class="row mt--2">
                <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <form class="row" id="form_input_edit">
                                <div class="col-md-12 text-left">
                                    <div class="form-group">
                                        <a id="back_to_list" style="color:white" class="btn btn-primary"><i class="fas fa-arrow-left"></i> &nbsp; Kembali</a>
                                    </div>
                                </div>
                                <input type="hidden" name="state" id="state">
                                <input type="hidden" id="sysid" name="sysid">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Edit Data Absen</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>File : <b class="b_file">2023/01/31.xls</b></h4>
                                    </div>
                                    <div class="form-group">
                                        <h4>Tanggal : <b class="b_tgl">2023/01/31.xls</b></h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>NIK : <b class="b_nik">2023/01/31.xls</b></h4>
                                    </div>
                                    <div class="form-group">
                                        <h4>Nama : <b class="b_nama">2023/01/31.xls</b></h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Jam Masuk</span>
                                            </div>
                                            <input id="jam_masuk" name="jam_masuk" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">Jam Keluar</span>
                                            </div>
                                            <input id="jam_keluar" name="jam_keluar" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <a id="save_edit" style="color:white" class="btn btn-primary"><i class="fas fa-save"></i> &nbsp; Simpan</a>
                                        <a id="batal_edit" style="color:white" class="btn btn-danger"><i class="fa fa-times-circle"></i> &nbsp; Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="input_ketidakhadiran" class="page-inner mt--5" style="display: none;">
            <div class="row mt--2">
                <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <form class="row" id="form_input_ketidakhadiran">
                                <div class="col-md-12 text-left">
                                    <div class="form-group">
                                        <a id="back_to_list1" style="color:white" class="btn btn-primary"><i class="fas fa-arrow-left"></i> &nbsp; Kembali</a>
                                    </div>
                                </div>
                                <input type="hidden" name="state1" id="state">
                                <input type="hidden" id="sysid1" name="sysid">
                                <input type="hidden" id="nrp" name="nrp">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h2 class="alert-info font-weight-bold text-center" id="title_input">Edit Data Absen</h2>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>File : <b class="b_file">2023/01/31.xls</b></h4>
                                    </div>
                                    <div class="form-group">
                                        <h4>Tanggal : <b class="b_tgl">2023/01/31.xls</b></h4>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h4>NRP : <b class="b_nik">2023/01/31.xls</b></h4>
                                    </div>
                                    <div class="form-group">
                                        <h4>Nama : <b class="b_nama">2023/01/31.xls</b></h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mulai">Kategori <span style="color: red;">*</span></label>
                                        <select id="kategori" name="kategori" class="form-control">
                                            <option value="Cuti">Cuti</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mulai">Mulai <span style="color: red;">*</span></label>
                                        <input id="mulai" name="mulai" class="form-control" type="date" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mulai">Sampai <span style="color: red;">*</span></label>
                                        <input id="sampai" name="sampai" class="form-control" type="date" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="mulai">Lampiran File <span style="color: red;">*</span></label>
                                        <input id="file" name="file" class="form-control" type="file" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="mulai">Keterangan </label>
                                        <textarea id="keterangan" class="form-control" cols="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <a id="save_ketidakhadiran" style="color:white" class="btn btn-primary"><i class="fas fa-save"></i> &nbsp; Simpan</a>
                                        <a id="batal_ketidakhadiran" style="color:white" class="btn btn-danger"><i class="fa fa-times-circle"></i> &nbsp; Batal</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loader" class="lds-dual-ring display-none overlay"></div>
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
<script type="text/javascript" src="{{ asset('/js/absensi/absen-office-gis.js') }}"></script>
<script>
    $("#nav-dashboard").removeClass('active');

    $("div#absensigis").siblings('a').attr('aria-expanded', 'true');
    $("div#absensigis").addClass("show");
    $("div#absensigis #absesnsi-office-gis").addClass("active");
    $("#nav-absensigis").addClass("active");

    list_data_hdr();
    function list_data_hdr() {
         $.ajax({
            url: "{{route('absensi-office.list.hdr')}}",
            type: 'GET',
            success: function(response) {
                $('#tbl_absensi_office_hdr').DataTable({
                    destroy: true,
                    processing: true,
                    select: true,
                    language: {
                        paginate: {
                            previous: "<i class='fas fa-chevron-left'>",
                            next: "<i class='fas fa-chevron-right'>"
                            }
                    },
                    data: response,
                    columns: [{ data: "id", name: "id", visible: false }, // 0
                        { data: "id", name: "id", orderable: false, searchable: false,
                            render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1,
                        }, // 1
                        { data: "file_name", name: "file_name", visible: true }, // 2
                        { data: "created_at", name: "created_at", visible: true,
                            render: function (d) {
                                return moment(d).format('DD-MM-YYYY');
                            }
                        }, // 3
                        { data: "user_at", name: "user_at", visible: true }, // 3
                        { data: "id", name: "id", visible: true,
                            render: (data) => {
                                return "<a href='javascript:void(0)' id='show_dtl' data-toggle='tooltip' title='Show Detail' data-id='"+data+"' data-original-title='' class='Edit btn btn-warning btn-sm'><i class='fas fa-eye'></i> &nbsp; Detail </a>";
                            }
                        }, // 3
                    ],
                    columnDefs: [{
                        className: "text-center",
                        targets: [0, 1, 2, 3, 4, 5]
                    }]
                })
              // $("#tbl_absensi_office_hdr").DataTable({
              //     destroy: true,
              //     processing: true,
              //     serverSide: true,
              //     select: true,
              //     language: {
              //       paginate: {
              //         previous: "<i class='bx bx-chevron-left'>",
              //         next: "<i class='bx bx-chevron-right'>"
              //       }
              //     },
              //     data: response,
                  // columns: [{ data: "id", name: "id", visible: false }, // 0
                  //     { data: "id", name: "id", orderable: false, searchable: false,
                  //       render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1,
                  //     }, // 1
                  //     { data: "file_name", name: "file_name", visible: true }, // 2
                  //     { data: "created_at", name: "created_at", visible: true,
                  //       render: function (d) {
                  //           return moment(d).format('DD-MM-YYYY');
                  //         }
                  //     }, // 3
                  //     { data: "user_at", name: "user_at", visible: true }, // 3
                  // ],
              //     //      aligment left, right, center row dan coloumn
              //     order: [
              //         ["1", "desc"]
              //     ],
              //     columnDefs: [{
              //             className: "text-center",
              //             targets: [0, 1, 2, 3, 4]
              //         }
              //     ],
              //     bAutoWidth: false,
              //     responsive: true,
              // });
              $("#tbl_absensi_office_hdr").css("cursor", "pointer");
            }
        });
    }

    function list_data_dtl(id_hdr=0) {
      $("#tbl_absensi_office").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          select: true,
          language: {
            paginate: {
              previous: "<i class='fas fa-chevron-left'>",
              next: "<i class='fas fa-chevron-right'>"
            }
          },
          ajax: {
              url: "{{route('absensi-office.list.dtl')}}",
              type: "GET",
              data: {id_hdr:id_hdr},
          },
          columns: [{ data: "id", name: "id", visible: false }, // 0
              { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false }, // 1
              { data: "nik", name: "nik", visible: true }, // 2
              { data: "nama", name: "nama", visible: true }, // 3
              { data: "tanggal", name: "tanggal", visible: true,
                render: function (d) {
                    return moment(d).format('DD-MM-YYYY');
                  }
              }, // 3
              { data: "jam_masuk", name: "jam_masuk", visible: true }, // 3
              { data: "jam_keluar", name: "jam_keluar", visible: true }, // 3
              { data: "statusabsensi", name: "statusabsensi", visible: true,
                render: function (d) {
                    if(d == 'Hadir'){
                        return '<span class="badge badge-success">'+d+'</span>'
                    }else if(d == 'Tidak Absen Pulang'){
                        return '<span class="badge badge-warning">'+d+'</span>'
                    }else if(d == 'Tidak Absen Masuk'){
                        return '<span class="badge badge-warning">'+d+'</span>'
                    }else if(d == 'Tidak Hadir'){
                        return '<span class="badge badge-danger">'+d+'</span>'
                    }else if(d == 'Telat'){
                        return '<span class="badge badge-dark">'+d+'</span>'
                    }else if(d == 'Absen Pulang Dulu'){
                        return '<span class="badge badge-danger">'+d+'</span>'
                    }else if(d == 'Cuti' || d == 'Izin' || d == 'Sakit'){
                        return '<span class="badge badge-info">'+d+'</span>'
                    }else{
                        return d
                    }
                }
              }, // 3
              { data: "action", name: "action", visible: true }, // 3
              { data: "file_name", name: "file_name", visible: false }, // 0
          ],
          //      aligment left, right, center row dan coloumn
          order: [
              ["1", "desc"]
          ],
          columnDefs: [{
                  className: "text-center",
                  targets: [0, 1, 7, 8]
              }
          ],
          bAutoWidth: false,
          responsive: true,
          // fixedColumns:   {
          //       left: 2
          //   }
      });
      $("#tbl_absensi_office").css("cursor", "pointer");
    }

    // $('#tbl_absensi_office_hdr').on('click', 'tbody tr', function() {
    //     var $row = $(this).closest("tr");
    //     var data = $('#tbl_absensi_office_hdr').DataTable().row($row).data();
    //     list_data_dtl(data['id'])
    // });

    $('#import_absensi').click(function(e) {
        var form = $('#form_input')[0];
        var formData = new FormData(form);
        // alert(formData);
        $.ajax({
            type: "post",
            url: "{{route('absensi-office.import')}}",
            processData: false,
            contentType: false,
            data: formData,
            
            success: function(response) {
                for (var key in response) {
                    var flag = response["success"];
                    var message = response["message"];
                }

                if ($.trim(flag) == "true") {
                    // swal('Success!', message, {
                    //     icon: 'success',
                    //     buttons: {
                    //         confirm: {
                    //             className: 'btn btn-success'
                    //         }

                    //     }
                    // });
                    set_tabel_excel(message);
                } else {
                    swal('Perhatian!', message, {
                        icon: 'warning',
                        buttons: {
                            confirm: {
                                className: 'btn btn-warning'
                            }

                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ": " + xhr.statusText;
                swal('Error!', errorMessage, {
                    icon: 'danger',
                    buttons: {
                        confirm: {
                            className: 'btn btn-danger'
                        }

                    }
                });
            },
            
        });
    });
    // set_tabel_excel();
    function set_tabel_excel(message=''){
        html_e = '';
        var no = 1;
        var data_tmp;
        
        $.ajax({
          url: "{{route('absensi-office.data.tmp')}}",
          type: 'GET',
          // beforeSend: function() {
          //       $('#loader').removeClass('display-none')
          //   },
          success: function (response) {
              $.each(response, function(key, value) {
                selisih_jam = parseFloat(value.selisih_jam);console.log(selisih_jam)
                masuk_jam = parseFloat(value.format_jam_masuk);
                keluar_jam = parseFloat(value.format_jam_keluar);
                if(selisih_jam <= 4){
                    if(masuk_jam > 8 && masuk_jam <= 12){
                        jam_masuk = value.jam_masuk
                        if(keluar_jam > 8 && keluar_jam <= 12){
                            jam_keluar =''
                        }else{
                            jam_keluar = value.jam_keluar
                        }
                    }else if(keluar_jam > 13 && keluar_jam <= 17){
                        jam_keluar = value.jam_keluar
                        if(masuk_jam > 13 && masuk_jam <= 17){
                            jam_masuk =''
                        }else{
                            jam_masuk = value.jam_masuk
                        }
                    }else if(selisih_jam == 0){
                        if(masuk_jam <= 17){
                            jam_masuk = value.jam_masuk
                            jam_keluar = ''
                        }else{
                            jam_masuk = ''
                            jam_keluar = value.jam_keluar
                        }
                    }else{
                        jam_masuk = value.jam_masuk
                        jam_keluar = value.jam_keluar
                    }
                }else{
                    jam_masuk = value.jam_masuk
                    jam_keluar = value.jam_keluar
                }

                  html_e += '<tr>' +
                      '<td><input class="form-control" style="text-align: center;" name="nik[]" type="text" value="'+ value.nik +'" readonly ></td>' +
                      '<td><input class="form-control" style="text-align: center;" name="nama[]" type="text" value="'+ value.nama +'" readonly ></td>' +
                      '<td><input class="form-control" style="text-align: center;" name="tgl[]" type="text" value="'+ value.tgl +'" readonly ></td>' +
                      '<td><input class="form-control" style="text-align: center;" name="jam_masuk[]" type="text" value="'+ jam_masuk +'" ></td>' +
                      '<td><input class="form-control" style="text-align: center;" name="jam_keluar[]" type="text" value="'+ jam_keluar +'" ></td>' +
                      '<td style="display:none;"><input class="form-control" style="text-align: center;" name="tanggal[]" type="text" value="'+ moment(value.tgl_masuk).format('YYYY-MM-DD') +'" ></td>' ;
                  no++;
              });

              $('#show_data_absen').html(html_e);

              
          },
          complete: function(data) {
            swal('Success!', message, {
                        icon: 'success',
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }

                        }
                    });
                // $('#loader').addClass('display-none')
          },
        });

        $('#save_data').click(function(event) {
            file = $('#file').val();
            filename = file.replace('C:\\fakepath\\', '');
            $('#filename').val(filename);

            $.ajax({
                type: "post",
                url: "{{route('absensi-office.store')}}",
                data: $("#form_input").serialize(),
                // beforeSend: function() {
                //     $('#loader').removeClass('display-none')
                // },
                success: function(response) {
                    for (var key in response) {
                        var flag = response["success"];
                        var message = response["message"];
                    }

                    if ($.trim(flag) == "true") {
                        swal('Success!', message, {
                            icon: 'success',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }

                            }
                        });

                        list_data_hdr();
                        $("#list_data").show();
                        $("#add_data").hide();
                    } else {
                        swal('Perhatian!', message, {
                            icon: 'warning',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }

                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.status + ": " + xhr.statusText;
                    swal('Error!', errorMessage, {
                        icon: 'danger',
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }

                        }
                    });
                },
                // complete: function(data) {
                //     $('#loader').addClass('display-none')
                // },
            });
        });

        

//         $('#save_data').click(function(event) {
//             var table = document.getElementById('tbl_excell');
//             var rowLength = table.rows.length;
//             for (i = 0; i < rowLength; i++){

//                //gets cells of current row
//                var oCells = table.rows.item(i).cells;
// // console.log(oCells);
//                //gets amount of cells of current row
//                var cellLength = oCells.length;
// // console.log(cellLength);
//                //loops through each cell in current row
//                for(var j = 0; j < cellLength; j++){
//                   /* get your cell info here */
//                    var cellVal = oCells.item(j); 
//                    var input = cellVal.getElementsByTagName('input');
//                    var valdata = cellVal.innerHTML;

//                    if (input != null) {
//                     var text = input.value;
//                     console.log(text);
//                    }else{
//                     console.log(valdata)
//                    }
                   
//                }
//             }
//         });
        
    }
</script>