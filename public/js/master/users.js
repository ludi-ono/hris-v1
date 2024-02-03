$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    form_state('LOAD');
    list_data();

    function list_data() {
        $("#tbl_user").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/master-user/list-data",
                type: "GET",
            },
            columns: [
                { data: "id", name: "id", visible: false }, // 0
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false }, // 1
                { data: "nip", name: "nip", visible: true }, // 2
                { data: "nama", name: "nama", visible: true }, // 3
                { data: "golongan", name: "golongan", visible: true }, // 4
                { data: "nama_direktorat", name: "nama_direktorat", visible: true }, // 5          
                { data: "action", name: "action", visible: true, orderable: false }, // 6
            ],
            //		aligment left, right, center row dan coloumn
            order: [["0", "desc"]],
            columnDefs: [
                {
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4, 5, 6],
                },
            ],
            bAutoWidth: false,
            responsive: true,
        });
    }

    function save_data(state) {
        var nip = document.getElementById("nip").value;
        var nama = document.getElementById("nama").value;
        var golongan = document.getElementById("golongan").value;
        if (nip == "" || nama == "" || golongan == "") {
            swal("Peringatan!", "Data tidak boleh kosong!", {
                icon: "danger",
                buttons: {
                    confirm: {
                        className: 'btn btn-danger'
                    }
                },
            });
        } else {
            if (state == 'ADD') {
                url_save = '/master-user/store';
            } else {
                url_save = '/master-user/update';
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
                        var oTableHdr = $("#tbl_user").dataTable();
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
                    } else if ($.trim(message) == "true") {
                        swal('Warning!', message, {
                            icon: 'warning',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-warning'
                                }
                            }
                        });

                        form_state('LOAD');
                    } else {
                        swal('Peringatan!', message, {
                            icon: 'info',
                            buttons: {
                                confirm: {
                                    className: 'btn btn-info'
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
    }

    function reset_input() {
        $('#id').val('');
        $('#nip').val('');
        $('#nama').val('');
        $('#golongan').val('');
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
                $('#title_input').html('Tambah Data User');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                $('#title_input').html('Edit Data User');
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
        save_data(state);
    });

    $('body').on('click', '#edit', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_user').DataTable().row($row).data();

        form_state('EDIT_HDR');

        id = data['id'];
        nip = data['nip'];
        nama = data['nama'];
        golongan = data['golongan'];
        direktorat = data['direktorat'];

        $('#id').val(id);
        $('#nip').val(nip);
        $('#nama').val(nama);
        $('#golongan').val(golongan);
        $('#direktorat').val(direktorat);
    });

    $('body').on('click', '#delete', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_user').DataTable().row($row).data();

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
                    url: "/master-user/destroy/" + id,
                    success: function (response) {
                        for (var key in response) {
                            var flag = response["success"];
                        }

                        if ($.trim(flag) == "true") {
                            swal({
                                title: 'Berhasil Menghapus Data',
                                type: 'success',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-info'
                                    }
                                }
                            });
                            var oTableHdr = $("#tbl_user").dataTable();
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