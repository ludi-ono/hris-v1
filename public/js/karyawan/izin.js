$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    form_state('LOAD');
    list_data();

    function list_data() {
        $("#tbl_cuti").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/karyawan-izin/list-data",
                type: "GET",
            },
            columns: [{
                    data: "id",
                    name: "id",
                    visible: false,
                    orderable: false
                }, // 0
                {
                    data: "DT_RowIndex",
                    name: "DT_RowIndex",
                    orderable: false
                }, // 1
                {
                    data: "nrp",
                    name: "nrp",
                    visible: false
                }, // 2
                {
                    data: "nama_karyawan",
                    name: "nama_karyawan",
                    visible: false
                }, // 3
                {
                    data: "no_dokumen",
                    name: "no_dokumen",
                    // visible: false
                }, // 4
                {
                    data: "mulai",
                    name: "mulai",
                    // visible: false
                }, // 5
                {
                    data: "sampai",
                    name: "sampai",
                    // visible: false
                }, // 6
                {
                    data: "jumlah_hari",
                    name: "jumlah_hari",
                    // visible: false
                }, // 7
                {
                    data: "status",
                    name: "status",
                    render: function(d){
                        if(d == 1){
                            return "Menunggu Persetujuan Atasan"
                        }else if(d == 2){
                            return "Menunggu Persetujuan HRD"
                        }else if(d == 3){
                            return "Cuti Anda Di Setujui"
                        } else {
                            return "-"
                        }
                    }
                }, // 8
                {
                    data: "action",
                    name: "action",
                    // visible: false
                }, // 9
                
            ],
            //      aligment left, right, center row dan coloumn
            order: [
                ["0", "desc"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 3, 4, 5, 6, 7, 8, 9]
                },
                // {
                //     width: "20%",
                //     targets: 5
                // },
            ],
            bAutoWidth: false,
            responsive: true,
        });
        // $("#tbl_cuti").css("cursor", "pointer");
    }

    function save_data(state) {
        if (state == 'ADD') {
            url_save = '/karyawan-izin/store';
        } else {
            url_save = '/karyawan-izin/update';
        }

        var form = $('#form_input')[0];
        var data = new FormData(form);

        $.ajax({
            type: "post",
            url: url_save,
            data: data,
            contentType: false,
            processData: false,
            success: function (response) {
                for (var key in response) {
                    var flag = response["success"];
                    var message = response["message"];
                }

                if ($.trim(flag) == "true") {
                    var oTableHdr = $("#tbl_cuti").dataTable();
                    oTableHdr.fnDraw(false);

                    swal('Success!', message, {
                        icon: 'success',
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }

                        }
                    });

                    form_state('LOAD');
                    reset_input();
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
            error: function (xhr, status, error) {
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
    }

    function reset_input() {
        $('#sysid').val('');
        $('#mulai').val('');
        $('#sampai').val('');
        $('#file').val('');
        $('#keterangan').val('');
    }

    function form_state(state) {
        switch (state) {
            case 'LOAD':
                $('#add_data').hide();
                $('#list_data').show();
                break;
            case 'ADD_HDR':
                reset_input();
                $("#state").val("ADD");
                $('#title_input').html('Input Data Izin');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                $('#title_input').html('Edit Data Izin');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'REVISI_HDR':
                break;
        }
    }

    $('#tambah_data').click(function () {
        form_state('ADD_HDR');
    });

    $('#back, #back_d').click(function () {
        $('#list_data').show('slow');
        $('#add_data').hide('slow');
        $('#detail_data').hide('slow');
    });

    $('#batal').click(function () {
        $('#list_data').show('slow');
        $('#add_data').hide('slow');
    });

    $('#save').click(function (event) {
        state = $('#state').val();
        message = '';
        if ($('#mulai').val().length == 0) {
            message = 'Tanggal Mulai Izin Belum Diisi';
        } else if ($('#sampai').val().length == 0) {
            message = 'Tanggal Sampai Izin Belum Diisi';
        } else {
            message = '';
        }

        if (message == '') {
            save_data(state);
        } else {
            swal('Perhatian!', message, {
                icon: 'warning',
                buttons: {
                    confirm: {
                        className: 'btn btn-warning'
                    }

                }
            });
            return false;
        }
    });

    $('body').on('click', '#edit', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_cuti').DataTable().row($row).data();

        id = data['id'];
        $.ajax({
            url: '/karyawan-izin/get-data',
            data: { id: id },
            type: 'GET',
            success: function(response) {
                $('#sysid').val(response.id);
                $('#mulai').val(response.mulai);
                $('#sampai').val(response.sampai);
                // $('#file').val(response.filename);
                $('#keterangan').val(response.keterangan);
            }
        }).done(function(data){
            form_state('EDIT_HDR');
        });
    });

    $('body').on('click', '#detail', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_cuti').DataTable().row($row).data();
        id = data['id'];
        $.ajax({
            url: '/karyawan-cuti/get-data',
            data: { id: id },
            type: 'GET',
            success: function(response) {
                var url = $(location).attr("origin");   //base url
                // var url = $(location).attr("href");     //current url
                // var url = $(location).attr("pathname"); //for find the pathname of the current URL
                file_url = url+'/file_upload/'+response.filename
                mulai = moment(response.mulai).format('DD-MM-YYYY');
                sampai = moment(response.sampai).format('DD-MM-YYYY');
                $('#tgl_cuti').val(mulai + ' s/d ' + sampai);
                $('#no_dok').val(response.no_dokumen);
                $('#keterangan_d').val(response.keterangan);console.log(file_url)
                $('#file_show').val(response.filename);
            }
        }).done(function(data){
            $('#list_data').hide('slow');
            $('#detail_data').show('slow');
        });
        
    });

    $('#show_lampiran').click(function(event) {
        var url = $(location).attr("origin");   //base url
        file_url = url+'/file_upload/'+$('#file_show').val()
        window.open(file_url)
    });

    $('body').on('click', '#delete', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_cuti').DataTable().row($row).data();

        id = data['id'];
        swal({
            title: 'Yakin Menghapus Data Ini ?',
            text: "Jika Dihapus Data Akan Hilang Pada Table Ini",
            type: 'warning',
            buttons: {
                confirm: {
                    text: 'Ya, Hapus!',
                    className: 'btn btn-danger'
                },
                cancel: {
                    visible: true,
                    text: 'Batal',
                    className: 'btn btn-dark'
                }
            }
        }).then((Delete) => {
            if (Delete) {
                $.ajax({
                    type: "post",
                    url: "/karyawan-izin/destroy/" + id,
                    success: function (response) {
                        for (var key in response) {
                            var flag = response["success"];
                        }

                        if ($.trim(flag) == "true") {
                            swal({
                                title: 'Berhasil Menghapus Data',
                                icon: 'success',
                                type: 'success',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-info'
                                    }
                                }
                            });
                            var oTableHdr = $("#tbl_cuti").dataTable();
                            oTableHdr.fnDraw(false);
                        } else {
                            swal('Error!', 'Kesalahan proses', {
                                icon: 'warning',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-warning'
                                    }
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
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
            } else {
                swal.close();
            }
        });
    });
});