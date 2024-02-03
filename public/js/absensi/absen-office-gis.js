$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $('#jam_masuk').bootstrapMaterialDatePicker({
        format: 'HH.mm',
        date: false,
        shortTime: false,
        // lang: 'id',
    });

    $('#jam_keluar').bootstrapMaterialDatePicker({
        format: 'HH.mm',
        date: false,
        shortTime: false,
        // lang: 'id',
    });

    form_state('LOAD');

    // function reset_input() {
    //     $('#sysid').val('');
    //     $('#nama').val('');
    //     $('#perusahaan').val(0).change();
    // }

    function form_state(state) {
        switch (state) {
            case 'LOAD':
                $('#add_data').hide();
                $('#list_data').show();
                break;
            case 'ADD_HDR':
                // reset_input();
                $("#state").val("ADD");
                $('#title_input').html('Tambah Data Absensi');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                $('#title_input').html('Edit Data Absensi');
                $('#edit_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'REVISI_HDR':
                break;
        }
    }

    $('#tambah_data').click(function () {
        form_state('ADD_HDR');
        document.getElementById('file').value= null;
        $('#show_data_absen').empty();
    });

    $('#batal_data').click(function(event) {
        // $.ajax({
        //   url: "{{route('absensi-office.data.tmp')}}",
        //   type: 'GET',
        //   success: function (response) {
        $.ajax({
            url: "/absen-karyawan/delete-tmp",
            type: 'POST',
        }).done(function() {
            form_state('LOAD');
        });
    });

    $('#back').click(function () {
        $('#list_data').show('slow');
        $('#add_data').hide('slow');
    });

    $('body').on('click', '#show_dtl', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_absensi_office_hdr').DataTable().row($row).data();
        list_data_dtl(data['id'])
        $('#id_hdr').val(data['id'])
        $("#show_header").hide();
        $('#show_detail').show();
    });

    $('#back_show').click(function(event) {
        $("#show_header").show();
        $('#show_detail').hide();
    });

    $('body').on('click', '#edit', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_absensi_office').DataTable().row($row).data();

        id = data['id'];
        nama = data['nama'];
        nik = data['nik'];
        tgl = moment(data['tanggal']).format('DD-MM-YYYY');
        jam_masuk = data['jam_masuk'];
        jam_keluar = data['jam_keluar'];
        file_name = data['file_name'];

        $('#sysid').val(id);
        $('.b_file').text(file_name);
        $('.b_tgl').html(tgl);
        $('.b_nik').html(nik);
        $('.b_nama').html(nama);
        $('#jam_masuk').val(jam_masuk);
        $('#jam_keluar').val(jam_keluar);

        $("#list_data").hide();
        $("#edit_data").show();
    });

    $('body').on('click', '#i_ketidakhadiran', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_absensi_office').DataTable().row($row).data();

        id = data['id'];
        nama = data['nama'];
        nik = data['nik'];
        tgl = moment(data['tanggal']).format('DD-MM-YYYY');
        jam_masuk = data['jam_masuk'];
        jam_keluar = data['jam_keluar'];
        file_name = data['file_name'];

        $('#sysid1').val(id);
        $('.b_file').text(file_name);
        $('.b_tgl').html(tgl);
        $('.b_nik').html(nik);
        $('.b_nama').html(nama);
        $('#jam_masuk1').val(jam_masuk);
        $('#jam_keluar1').val(jam_keluar);
        $('#nrp').val(nik);

        $("#list_data").hide();
        $("#input_ketidakhadiran").show();
    });

    $('#save_edit').click(function(event) {
        $.ajax({
            type: "post",
            url: "/absen-karyawan/update",
            data: $("#form_input_edit").serialize(),
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

                    list_data_dtl($('#id_hdr').val());
                    $("#list_data").show();
                    $("#edit_data").hide();
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

    $('#save_ketidakhadiran').click(function(event) {
        var form = $('#form_input_ketidakhadiran')[0];
        var data = new FormData(form);
        $.ajax({
            type: "post",
            url: "/absen-karyawan/store-surat",
            data: data,
            contentType: false,
            processData: false,
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

                    list_data_dtl($('#id_hdr').val());
                    $("#list_data").show();
                    $("#input_ketidakhadiran").hide();
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

    $('#back_to_list, #back_to_list1, #batal_edit, #batal_ketidakhadiran').click(function(event) {
        $("#list_data").show();
        $("#edit_data").hide();
        $("#input_ketidakhadiran").hide();
    });

});