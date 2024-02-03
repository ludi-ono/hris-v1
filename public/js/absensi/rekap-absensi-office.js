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

    function list_data_devisi(id, id_devisi='') {
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

    function list_data() {
        var devisi = $('#devisi').val();
      $("#tbl_rekap_absensi").DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          select: true,
          language: {
            paginate: {
              previous: "<i class='fas fa-chevron-left'>",
              next: "<i class='fas fa-chevron-right'>"
            }
          },
          ajax: {
              url: "/rekap-absensi-office/list-data",
              type: "GET",
              data: {devisi:devisi, tgl_dari:tgl_dari, tgl_sampai:tgl_sampai},
          },
          columns: [{ data: "id", name: "id", visible: false }, // 0
              { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false }, // 1
              { data: "nik", name: "nik", visible: true }, // 2
              { data: "nama", name: "nama", visible: true }, // 3
              { data: "tanggal", name: "tanggal", visible: true,
                render: function (d) {
                    return moment(d).format('DD-MM-YYYY');
                  }
              }, // 3
              { data: "jam_masuk", name: "jam_masuk", visible: true }, // 3
              { data: "jam_keluar", name: "jam_keluar", visible: true }, // 3
              { data: "statusabsensi", name: "statusabsensi", visible: true,
                render: function (d) {
                    if(d == 'Hadir'){
                        return '<span class="badge badge-success">'+d+'</span>'
                    }else if(d == 'Tidak Absen Pulang'){
                        return '<span class="badge badge-warning">'+d+'</span>'
                    }else if(d == 'Tidak Absen Masuk'){
                        return '<span class="badge badge-warning">'+d+'</span>'
                    }else if(d == 'Tidak Hadir'){
                        return '<span class="badge badge-danger">'+d+'</span>'
                    }else if(d == 'Telat'){
                        return '<span class="badge badge-dark">'+d+'</span>'
                    }else if(d == 'Absen Pulang Dulu'){
                        return '<span class="badge badge-danger">'+d+'</span>'
                    }else if(d == 'Cuti' || d == 'Izin' || d == 'Sakit'){
                        return '<span class="badge badge-info">'+d+'</span>'
                    }else{
                        return d
                    }
                }
              }, // 3
              { data: "action", name: "action", visible: true }, // 3
              { data: "file_name", name: "file_name", visible: false }, // 0
          ],
          //      aligment left, right, center row dan coloumn
          order: [
              ["1", "desc"]
          ],
          columnDefs: [{
                  className: "text-center",
                  targets: [0, 1, 7, 8]
              }
          ],
          bAutoWidth: false,
          responsive: true,
          // fixedColumns:   {
          //       left: 2
          //   }
      });
      $("#tbl_rekap_absensi").css("cursor", "pointer");
    }

    var tgl_dari='';
    var tgl_sampai='';
    var tahun_old = parseInt(moment().format('YYYY'))-4;
    var tahun = parseInt(moment().format('YYYY'));    
    $("#tahun").empty();
    for(i=tahun;i>=tahun_old;i--){
        $("#tahun").append('<option value=' + i + '>' + i + '</option>');
    }

    bulan = moment().format('M');
    $("#bulan").val(bulan - 1).change();

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
    tgl_dari = moment(tgl_dari_text).format('YYYY-MM-DD')
    tgl_sampai = moment(tgl_sampai_text).format('YYYY-MM-DD')
    // $('.periode_tgl').html(tgl_dari+' s/d ' +tgl_sampai)

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
        tgl_dari = moment(tgl_dari_text).format('YYYY-MM-DD')
        tgl_sampai = moment(tgl_sampai_text).format('YYYY-MM-DD')
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
        tgl_dari = moment(tgl_dari_text).format('YYYY-MM-DD')
        tgl_sampai = moment(tgl_sampai_text).format('YYYY-MM-DD')
        $('.periode_tgl').html(tgl_dari+' s/d ' +tgl_sampai)
        // alert(tgl_dari+' s/d' +tgl_sampai)
    });

    $('#refresh_data').click(function(event) {
        list_data();
    });

    list_data_devisi($('#id_perusahaan').val());
    list_data();

});