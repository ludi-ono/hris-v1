$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    form_state('LOAD');
    list_data();
    list_data_perusahaan();

    function list_data() {
        $("#tbl_karyawan").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/master-karyawan/list-data",
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
                    // visible: false
                }, // 5
                {
                    data: "nik",
                    name: "nik",
                    // visible: false
                }, // 2
                {
                    data: "nama",
                    name: "nama",
                    // visible: false
                }, // 3
                {
                    data: "tanggal_masuk",
                    name: "tanggal_masuk",
                    render:function(d){
                        return moment(d).format('DD-MM-YYYY')
                    }
                }, // 3
                {
                    data: "alamat",
                    name: "alamat",
                    // visible: false
                }, // 4
                {
                    data: "action",
                    name: "action",
                    // visible: false
                }, // 4
                
            ],
            //      aligment left, right, center row dan coloumn
            order: [
                ["5", "asc"]
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
        // $("#tbl_karyawan").css("cursor", "pointer");
    }

    function list_data_perusahaan() {
        $.ajax({
            url: '/master-jabatan/perusahaan',
            type: 'GET',
            success: function(response) {
                $('#perusahaan').empty();
                $("#perusahaan").append('<option value="0">Pilih Perusahaan</option>');

                var id = [];
                var nama = [];

                $.each(response.data, function(key, value) {
                    id.push(value);
                    nama.push(value);
                    $("#perusahaan").append('<option value=' + value.id + '>' + value.nama + '</option>');
                });
            }
        });
    }

    function list_data_devisi(id, id_devisi='') {
        //alert(id_perusahaan);
        $.ajax({
            url: '/master-jabatan/devisi',
            data: { id: id },
            type: 'GET',
            success: function(response) {
                $('#devisi').empty();
                $("#devisi").append('<option value="0">Pilih Devisi</option>');

                var id = [];
                var nama = [];

                $.each(response.data, function(key, value) {
                    id.push(value);
                    nama.push(value);
                    $("#devisi").append('<option value=' + value.id + '>' + value.nama + '</option>');
                });
            }
        }).done(function(data){
            if(id_devisi != ''){
                $("#devisi").val(id_devisi);
            }
        });
    }

    function list_data_jabatan(id, id_jabatan='') {
        //alert(id_perusahaan);
        $.ajax({
            url: '/master-jabatan/jabatan',
            data: { id: id },
            type: 'GET',
            success: function(response) {
                $('#jabatan').empty();
                $("#jabatan").append('<option value="0">Pilih Jabatan</option>');

                var id = [];
                var nama = [];

                $.each(response.data, function(key, value) {
                    id.push(value);
                    nama.push(value);
                    $("#jabatan").append('<option value=' + value.id + '>' + value.nama + '</option>');
                });
            }
        }).done(function(data){
            if(id_jabatan != ''){
                $("#jabatan").val(id_jabatan);
            }
        });
    }

    function save_data(state) {
        if (state == 'ADD') {
            url_save = '/master-karyawan/store';
        } else {
            url_save = '/master-karyawan/update';
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
                    var oTableHdr = $("#tbl_karyawan").dataTable();
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
        $('#perusahaan').val('');
        $('#devisi').val('');
        $('#jabatan').val('');
        $('#nrp').val('');
        $('#nik').val('');
        $('#nama').val('');
        $('#alamat').val('');
        $('#alamat_domisili').val('');
        $('#ktp').val('');
        $('#npwp').val('');
        $('#file_ktp').val('');
        $('#file_npwp').val('');
        $('#file_ijazah').val('');
        $('#file_sertifikat').val('');
        $('#jenis_kelamin').val('');
        $('#tempat_lahir').val('');
        $('#tgl_lahir').val('');
        $('#tgl_masuk').val('');
        $('#tgl_keluar').val('');
        $('#status').val('');
        $('#status_karyawan').val('');
        $('#pendidikan').val('SD').change();
        $('#no_hp').val('');
        $('#email').val('');
        $('#nama_ibu').val('');
        $('#nama_pasangan').val('');
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
                $('#title_input').html('Tambah Data Karyawan');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'SAVE_HDR':
                break;
            case 'EDIT_HDR':
                $("#state").val("EDIT");
                $('#title_input').html('Edit Data Karyawan');
                $('#add_data').show('slow');
                $('#list_data').hide('slow');
                break;
            case 'REVISI_HDR':
                break;
        }
    }

    $('#perusahaan').change(function (event) {
        id = $(this).val();
        list_data_devisi(id);
    });

    $('#devisi').change(function (event) {
        id = $(this).val();
        list_data_jabatan(id);
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
        if ($('#nrp').val().length == 0) {
            message = 'NRP Belum Diisi';
        } else if ($('#nik').val().length == 0) {
            message = 'NIK Belum Diisi';
        } else if ($('#nama').val().length == 0) {
            message = 'Nama Belum Diisi';
        } else if ($('#alamat').val().length == 0) {
            message = 'Alamat Belum Diisi';
        } else {
            message = '';
        }

        if($('#status').val() == 'Kawin'){
            if ($('#nama_pasangan').val().length == 0) {
                message = 'Suami/Istri Belum Diisi';
            }
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
        var data = $('#tbl_karyawan').DataTable().row($row).data();

        

        id = data['id'];
        $.ajax({
            url: '/master-karyawan/get-data',
            data: { id: id },
            type: 'GET',
            success: function(response) {
                $('#sysid').val(response.id);
                $('#nrp').val(response.nrp);
                $('#nik').val(response.nik);
                $('#tgl_masuk').val(response.tanggal_masuk);
                $('#tgl_keluar').val(response.tanggal_keluar);
                $('#status_karyawan').val(response.status_karyawan);
                $('#perusahaan').val(response.id_perusahaan);
                list_data_devisi(response.id_perusahaan, response.id_devisi);
                list_data_jabatan(response.id_devisi, response.id_jabatan);
                $('#nama').val(response.nama);
                $('#jenis_kelamin').val(response.jenis_kelamin);
                $('#status').val(response.status_perkawinan);
                $('#tempat_lahir').val(response.tempat_lahir);
                $('#tgl_lahir').val(response.tanggal_lahir);
                $('#alamat').val(response.alamat);
                $('#alamat_domisili').val(response.alamat_domisili);
                $('#ktp').val(response.ktp);
                $('#npwp').val(response.npwp);
                $('#pendidikan').val(response.pendidikan);
                $('#no_hp').val(response.no_tlp);
                $('#email').val(response.email);
                $('#nama_ibu').val(response.nama_ibu);
                $('#nama_pasangan').val(response.nama_pasangan);
            }
        }).done(function(data){
            form_state('EDIT_HDR');
        });
    });

    $('body').on('click', '#delete', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_karyawan').DataTable().row($row).data();

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
                    url: "/master-karyawan/destroy/" + id,
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
                            var oTableHdr = $("#tbl_karyawan").dataTable();
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