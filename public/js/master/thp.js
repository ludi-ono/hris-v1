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
        $("#tbl_thp").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/thp-karyawan/list-data",
                type: "GET",
            },
            columns: [
                { data: "id", name: "id", visible: false }, // 0
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false }, // 1
                { data: "nama_karyawan", name: "nama_karyawan", visible: true }, // 2
                { data: "nama_devisi", name: "nama_devisi", visible: false }, // 2
                { data: "gapok", name: "gapok", visible: true, render:function(d){return currencyFormat(d)}}, // 2
                { data: "presensi", name: "presensi", visible: true, render:function(d){return currencyFormat(d)}}, // 2
                { data: "tunj_jabatan", name: "tunj_jabatan", visible: true, render:function(d){return currencyFormat(d)}}, // 2
                { data: "tunj_pulsa", name: "tunj_pulsa", visible: true, render:function(d){return currencyFormat(d)}}, // 2
                { data: "action", name: "action", visible: true, orderable: false }, // 12
            ],
            //		aligment left, right, center row dan coloumn
            order: [["4", "asc"], ["5", "asc"], ["6", "asc"], ["7", "asc"]],
            columnDefs: [
                {
                    className: "text-center",
                    targets: [0, 1],
                },
                {
                    className: "text-right",
                    targets: [4, 5, 6, 7],
                },
            ],
            bAutoWidth: false,
            responsive: true,
        });
    }

    function save_data(state) {
        if (state == 'ADD') {
            url_save = '/thp-karyawan/store';
        } else {
            url_save = '/thp-karyawan/update';
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
                    var oTableHdr = $("#tbl_thp").dataTable();
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
        $('#gapok').val(0);
        $('#presensi').val(0);
        $('#tunj_jabatan').val(0);
        $('#tunj_pulsa').val(0);
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
                $('#title_input').html('Tambah Data THP Karyawan');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                // $("#state").val("ADD");
                $('#title_input').html('Edit Data THP Karyawan');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'REVISI_HDR':
                break;
        }
    }

    $("#gapok").on("keypress keyup", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    $("#gapok").on("keyup", function (event) {
        $(this).val(currencyFormat($(this).val()));
    });

    $("#presensi").on("keypress keyup", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    $("#presensi").on("keyup", function (event) {
        $(this).val(currencyFormat($(this).val()));
    });

    $("#tunj_jabatan").on("keypress keyup", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    $("#tunj_jabatan").on("keyup", function (event) {
        $(this).val(currencyFormat($(this).val()));
    });

    $("#tunj_pulsa").on("keypress keyup", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    $("#tunj_pulsa").on("keyup", function (event) {
        $(this).val(currencyFormat($(this).val()));
    });

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
        if ($('#gapok').val().length == 0) {
            message = 'Gaji Pokok Belum Diisi';
        } else if ($('#presensi').val().length == 0) {
            message = 'Presensi Belum Diisi';
        } else {
            message = '';
        }

        if (message == '') {
            $('#gapok').val(amountToFloat($('#gapok').val()));
            $('#presensi').val(amountToFloat($('#presensi').val()));
            $('#tunj_jabatan').val(amountToFloat($('#tunj_jabatan').val()));
            $('#tunj_pulsa').val(amountToFloat($('#tunj_pulsa').val()));
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
        var data = $('#tbl_thp').DataTable().row($row).data();

        form_state('ADD_HDR');

        id_karyawan = data['id'];
        $('#id_karyawan').val(id_karyawan);
    });

    $('body').on('click', '#edit', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_thp').DataTable().row($row).data();

        form_state('EDIT_HDR');

        id = data['id'];
        gapok = data['gapok'];
        presensi = data['presensi'];
        tunj_jabatan = data['tunj_jabatan'];
        tunj_pulsa = data['tunj_pulsa'];
        id_karyawan = id

        $('#sysid').val(id);
        $('#gapok').val(currencyFormat(gapok));
        $('#presensi').val(currencyFormat(presensi));
        $('#tunj_jabatan').val(currencyFormat(tunj_jabatan));
        $('#tunj_pulsa').val(currencyFormat(tunj_pulsa));
        $('#id_karyawan').val(currencyFormat(id_karyawan));
    });

    $('body').on('click', '#delete', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_thp').DataTable().row($row).data();

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
                    url: "/thp-karyawan/destroy/" + id,
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
                            var oTableHdr = $("#tbl_thp").dataTable();
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