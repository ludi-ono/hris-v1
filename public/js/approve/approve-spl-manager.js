$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    list_data();

    function list_data() {
        $("#tbl_approve").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/approve-spl-manager/list-data-spl",
                type: "GET",
            },
            columns: [
                { data: "id", name: "id", visible: false }, // 0
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false }, // 1
                { data: "id_karyawan", name: "id_karyawan", visible: false }, // 2
                { data: "nama_karyawan", name: "nama_karyawan", visible: true }, // 2
                { data: "tgl_lembur", name: "tgl_lembur", visible: true }, // 2
                { data: "keterangan", name: "keterangan", visible: true }, // 2
                { data: "action", name: "action", visible: true }, // 12
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

    $('body').on('click', '#approve', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_approve').DataTable().row($row).data();

        id = data['id'];
        swal({
            title: 'Yakin Approve SPL Ini ?',
            text: "Setujui SPL ?",
            type: 'warning',
            buttons: {
                confirm: {
                    text: 'Ya, Approve!',
                    className: 'btn btn-success'
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
                    url: "/approve-spl-manager/approve-spl/" + id,
                    success: function (response) {
                        for (var key in response) {
                            var flag = response["success"];
                        }

                        console.log(flag);

                        if ($.trim(flag) == "true") {
                            swal({
                                title: 'Berhasil Approve SPL',
                                icon: 'success',
                                type: 'success',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-info'
                                    }
                                }
                            });
                            var oTableHdr = $("#tbl_approve").dataTable();
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

    $('body').on('click', '#reject', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_approve').DataTable().row($row).data();

        id = data['id'];
        swal({
            title: 'Yakin Reject SPL Ini ?',
            text: "Reject SPL ?",
            type: 'warning',
            buttons: {
                confirm: {
                    text: 'Ya, Reject!',
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
                    url: "/approve-spl-manager/reject-spl/" + id,
                    success: function (response) {
                        for (var key in response) {
                            var flag = response["success"];
                        }

                        if ($.trim(flag) == "true") {
                            swal({
                                title: 'Berhasil Reject SPL',
                                icon: 'success',
                                type: 'success',
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-info'
                                    }
                                }
                            });
                            var oTableHdr = $("#tbl_approve").dataTable();
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