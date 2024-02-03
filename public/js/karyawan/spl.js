$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    form_state('LOAD');
    list_data();

    function list_data() {
        $("#tbl_spl").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/karyawan-spl/list-data",
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
                    data: "no_dokumen",
                    name: "no_dokumen",
                    visible: true
                }, // 2
                {
                    data: "tgl_lembur",
                    name: "tgl_lembur",
                    visible: true
                }, // 3
                {
                    data: "keterangan",
                    name: "keterangan",
                    visible: true
                }, // 4
                {
                    data: "status",
                    name: "status",
                    visible: true
                }, // 5
                {
                    data: "action",
                    name: "action",
                    // visible: false
                }, // 6
                
            ],
            //      aligment left, right, center row dan coloumn
            order: [
                ["0", "desc"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4, 5, 6]
                },
                // {
                //     width: "20%",
                //     targets: 5
                // },
            ],
            bAutoWidth: false,
            responsive: true,
        });
        // $("#tbl_spl").css("cursor", "pointer");
    }

    function save_data(state) {
        if (state == 'ADD') {
            url_save = '/karyawan-spl/store';
        } else {
            url_save = '/karyawan-spl/update';
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
                    var oTableHdr = $("#tbl_spl").dataTable();
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
        $('#tgl_lembur').val('');
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
                $('#title_input').html('Input Data Lembur');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                $('#title_input').html('Edit Data Lembur');
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

    $('#back').click(function () {
        $('#list_data').show('slow');
        $('#add_data').hide('slow');
    });

    $('#batal').click(function () {
        $('#list_data').show('slow');
        $('#add_data').hide('slow');
    });

    $('#save').click(function (event) {
        state = $('#state').val();
        message = '';
        if ($('#tgl_lembur').val().length == 0) {
            message = 'Tanggal Mulai Cuti Belum Diisi';
        } else if ($('#keterangan').val().length == 0) {
            message = 'Keterangan Lembur Belum Diisi';
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
        var data = $('#tbl_spl').DataTable().row($row).data();

        id = data['id'];
        $.ajax({
            url: '/karyawan-spl/get-data',
            data: { id: id },
            type: 'GET',
            success: function(response) {
                $('#sysid').val(response.id);
                $('#tgl_lembur').val(response.tgl_lembur);
                $('#keterangan').val(response.keterangan);
            }
        }).done(function(data){
            form_state('EDIT_HDR');
        });
    });

    $('body').on('click', '#delete', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_spl').DataTable().row($row).data();

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
                    url: "/karyawan-spl/destroy/" + id,
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
                            var oTableHdr = $("#tbl_spl").dataTable();
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

    $('.browe_karyawan').click(function(event) {
        $('#modal_browse_karyawan').modal('show');
    });

    $('#batal_karyawan').click(function(event) {
        $('#modal_browse_karyawan').modal('toggle');
    });
});