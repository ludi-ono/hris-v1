$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    function currencyFormat(num, decimal = 0) {
        return accounting.formatMoney(num, "", decimal, ",", ".");
    }

    function amountToFloat(amount) {
        return parseFloat(accounting.unformat(amount));
    }

    form_state('LOAD');
    list_data();

    function list_data() {
        $("#tbl_bpjs").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/bpjs-ketenagakerjaan/list-data",
                type: "GET",
            },
            columns: [
                { data: "id", name: "id", visible: false }, // 0
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false }, // 1
                { data: "nama_karyawan", name: "nama_karyawan", visible: true }, // 2
                { data: "nama_devisi", name: "nama_devisi", visible: false }, // 2
                { data: "nilai", name: "nilai", visible: true, render:function(d){return currencyFormat(d)}}, // 2
                { data: "action", name: "action", visible: true }, // 12
                { data: "id_karyawan", name: "id_karyawan", visible: false }, // 2
            ],
            //		aligment left, right, center row dan coloumn
            order: [["4", "asc"]],
            columnDefs: [
                {
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4, 5],
                },
            ],
            bAutoWidth: false,
            responsive: true,
        });
    }

    function save_data(state) {
        if (state == 'ADD') {
            url_save = '/bpjs-ketenagakerjaan/store';
        } else {
            url_save = '/bpjs-ketenagakerjaan/update';
        }

        $.ajax({
            type: "post",
            url: url_save,
            data: $("#form_input").serialize(),
            success: function (response) {
                for (var key in response) {
                    var flag = response["success"];
                    var message = response["message"];
                }

                if ($.trim(flag) == "true") {
                    var oTableHdr = $("#tbl_bpjs").dataTable();
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
        $('#nilai').val('');
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
                $('#title_input').html('Tambah Data BPJS Kesehatan');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                $('#title_input').html('Edit Data BPJS Kesehatan');
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
        if ($('#nilai').val().length == 0) {
            message = 'Nilai BPJS Kesehatan Belum Diisi';
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

    $('body').on('click', '#input', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_bpjs').DataTable().row($row).data();

        form_state('ADD_HDR');

        id_karyawan = data['id_karyawan'];
        $('#id_karyawan').val(id_karyawan);
    });

    $('body').on('click', '#edit', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_bpjs').DataTable().row($row).data();

        form_state('EDIT_HDR');

        id = data['id'];
        nilai = data['nilai'];
        id_karyawan = data['id_karyawan']

        $('#sysid').val(id);
        $('#nilai').val(nilai);
        $('#id_karyawan').val(id_karyawan);     
    });

    $('body').on('click', '#delete', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_bpjs').DataTable().row($row).data();

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
                    url: "/bpjs-ketenagakerjaan/destroy/" + id,
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
                            var oTableHdr = $("#tbl_bpjs").dataTable();
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