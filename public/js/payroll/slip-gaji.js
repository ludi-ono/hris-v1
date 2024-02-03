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

    function bulan_to_text(bulan) {
        switch (parseFloat(bulan)) {
            case 1:
                bln = 'Januari';
                break;
            case 2:
                bln = 'Februari';
                break;
            case 3:
                bln = 'Maret';
                break;
            case 4:
                bln = 'April';
                break;
            case 5:
                bln = 'Mei';
                break;
            case 6:
                bln = 'Juni';
                break;
            case 7:
                bln = 'Juli';
                break;
            case 8:
                bln = 'Agustus';
                break;
            case 9:
                bln = 'September';
                break;
            case 10:
                bln = 'Oktober';
                break;
            case 11:
                bln = 'November';
                break;
            case 12:
                bln = 'Desember ';
                break;
        }
        return bln;
    }

    var tahun_old = parseInt(moment().format('YYYY'))-4;
    var tahun = parseInt(moment().format('YYYY'));    
    $("#tahun").empty();
    for(i=tahun;i>=tahun_old;i--){
        $("#tahun").append('<option value=' + i + '>' + i + '</option>');
    }

    tahun = $("#tahun").val();
    bulan = '0'+$('#bulan').val();
    bulan_sebelum = '0';
    if($('#bulan').val() == 1){
        bulan_sebelum = '12'
        tahun_sebelum = parseInt(tahun) - 1
    }else{
        bulan_sebelum = '0' + (parseInt($('#bulan').val()) - 1);
        tahun_sebelum = tahun
    }
    tgl_dari_text = tahun_sebelum+'/'+bulan_sebelum.substr(bulan_sebelum.length-2)+'/22';
    tgl_sampai_text = tahun+'/'+bulan.substr(bulan.length-2)+'/21';
    tgl_dari = moment(tgl_dari_text).format('DD-MM-YYYY')
    tgl_sampai = moment(tgl_sampai_text).format('DD-MM-YYYY')
    $('.periode_tgl').html(tgl_dari+' s/d ' +tgl_sampai)

    $("#tahun").change(function(event) {
        tahun = $(this).val();
        bulan = '0'+$('#bulan').val();
        bulan_sebelum = '0';
        if($('#bulan').val() == 1){
            bulan_sebelum = '12'
            tahun_sebelum = parseInt(tahun) - 1
        }else{
            bulan_sebelum = '0' + (parseInt($('#bulan').val()) - 1);
            tahun_sebelum = tahun
        }
        
        tgl_dari_text = tahun_sebelum+'/'+bulan_sebelum.substr(bulan_sebelum.length-2)+'/22';
        tgl_sampai_text = tahun+'/'+bulan.substr(bulan.length-2)+'/21';
        tgl_dari = moment(tgl_dari_text).format('DD-MM-YYYY')
        tgl_sampai = moment(tgl_sampai_text).format('DD-MM-YYYY')
        $('.periode_tgl').html(tgl_dari+' s/d ' +tgl_sampai)
        // alert(tgl_dari+' s/d' +tgl_sampai)
    });

    $("#bulan").change(function(event) {
        tahun = $("#tahun").val();
        bulan = '0'+$(this).val();
        bulan_sebelum = '0';
        if($(this).val() == 1){
            bulan_sebelum = '12'
            tahun_sebelum = parseInt(tahun) - 1
        }else{
            bulan_sebelum = '0' + (parseInt($(this).val()) - 1);
            tahun_sebelum = tahun
        }
        
        tgl_dari_text = tahun_sebelum+'/'+bulan_sebelum.substr(bulan_sebelum.length-2)+'/22';
        tgl_sampai_text = tahun+'/'+bulan.substr(bulan.length-2)+'/21';
        tgl_dari = moment(tgl_dari_text).format('DD-MM-YYYY')
        tgl_sampai = moment(tgl_sampai_text).format('DD-MM-YYYY')
        $('.periode_tgl').html(tgl_dari+' s/d ' +tgl_sampai)
        // alert(tgl_dari+' s/d' +tgl_sampai)
    });

    list_data();
    function list_data() {
        tahun = $('#tahun').val();
        bulan = $('#bulan').val();

        $("#tbl_slip_gaji_hdr").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            language: {
                paginate: {
                  previous: "<i class='fas fa-chevron-left'>",
                  next: "<i class='fas fa-chevron-right'>"
                }
            },
            ajax: {
                url: "/slip-gaji/list-data",
                type: "GET",
                data: {tahun:tahun, bulan:bulan},
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
                    visible: true
                }, // 2
                {
                    data: "nama",
                    name: "nama",
                    visible: true
                }, // 2
                {
                    data: "devisi",
                    name: "devisi",
                    visible: true
                }, // 3
                {
                    data: "bulan",
                    name: "bulan",
                    visible: true,
                    render:function(d){
                        bulan = bulan_to_text(d)
                        return bulan.toUpperCase();
                    }
                }, // 4
                {
                    data: "tahun",
                    name: "tahun",
                    visible: true
                }, // 4
                // {
                //     data: "status",
                //     name: "status",
                //     visible: false,
                //     render:function(d){
                //         if(d == 0){
                //             ket = 'Slip Gaji Belum Dikirim';
                //             return '<span class="badge badge-danger">'+ket+'</span>'
                            
                //         }else{
                //             ket = 'Slip Gaji Sudah Dikirim';
                //             return '<span class="badge badge-success">'+ket+'</span>'
                //         }
                //     }
                // }, // 5
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
                    targets: [0, 1, 2, 3, 4, 5, 6, 7]
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

    function list_data_dtl(id_hdr) {
        $("#tbl_slip_gaji_detail").DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            language: {
                paginate: {
                  previous: "<i class='fas fa-chevron-left'>",
                  next: "<i class='fas fa-chevron-right'>"
                }
            },
            ajax: {
                url: "/generate-gaji/list-data-dtl",
                type: "GET",
                data: {id_hdr:id_hdr},
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
                    data: "nama",
                    name: "nama",
                    visible: true
                }, // 2
                {
                    data: "devisi",
                    name: "devisi",
                    visible: true
                }, // 3
                {
                    data: "nominal",
                    name: "nominal",
                    visible: true,
                    render:function(d){
                        return currencyFormat(d)
                    }
                }, // 4
                
            ],
            //      aligment left, right, center row dan coloumn
            order: [
                ["0", "desc"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 2, 3, 4]
                },
                // {
                //     width: "20%",
                //     targets: 5
                // },
            ],
            bAutoWidth: false,
            responsive: true,
        });
    }

    $('#refresh_data').click(function(event) {
        list_data();
    });

    $('body').on('click', '#print', function (e) {
        var $row = $(this).closest("tr");
        var data = $('#tbl_slip_gaji_hdr').DataTable().row($row).data();

        id = data['nrp'];
        tahun = data['tahun'];
        bulan = data['bulan'];
        
        window.open("/slip-gaji/print/"+tahun+"/"+bulan+"/"+id, '_blank');
    });

    // $('body').on('click', '#detail', function (e) {
    //     var $row = $(this).closest("tr");
    //     var data = $('#tbl_gaji_hdr').DataTable().row($row).data();

    //     id = data['id'];
    //     tahun = data['tahun'];
    //     bulan = data['bulan'];
    //     list_data_dtl(id);
    //     $('.modal-title').html('Detail Lembur Periode : '+bulan_to_text(bulan)+' '+tahun);
    //     $('#modal_detail').modal('show');
    // });

});