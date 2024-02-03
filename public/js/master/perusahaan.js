$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    form_state('LOAD');
    list_data();

    function list_data() {
        $("#tbl_perusahaan").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/master-perusahaan/list-data",
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
                    data: "logo",
                    name: "logo",
                    // visible: false
                }, // 5
                {
                    data: "nama",
                    name: "nama",
                    // visible: false
                }, // 2
                {
                    data: "kode",
                    name: "kode",
                    // visible: false
                }, // 3
                {
                    data: "alamat",
                    name: "alamat",
                    // visible: false
                }, // 4
                
            ],
            //      aligment left, right, center row dan coloumn
            order: [
                ["5", "asc"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4, 5]
                },
                {
                    width: "20%",
                    targets: 5
                },
            ],
            bAutoWidth: false,
            responsive: true,
        });
        // $("#tbl_perusahaan").css("cursor", "pointer");
    }

    function save_data(state) {
        if (state == 'ADD') {
            url_save = '/master-perusahaan/store';
        } else {
            url_save = '/master-perusahaan/update';
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
                    var oTableHdr = $("#tbl_perusahaan").dataTable();
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
        $('#nama_perusahaan').val('');
        $('#kode_perusahaan').val('');
        $('#alamat').val('');
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
                $('#title_input').html('Tambah Data Perusahaan');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                $('#title_input').html('Edit Data Perusahaan');
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
        if ($('#nama_perusahaan').val().length == 0) {
            message = 'Nama Perusahaan Belum Diisi';
        } else if ($('#kode_perusahaan').val().length == 0) {
            message = 'Kode Perusahaan Belum Diisi';
        } else if ($('#alamat').val().length == 0) {
            message = 'Alamat Perusahaan Belum Diisi';
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
});