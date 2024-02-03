    </div>

    <script src="{{ asset('/main-assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/chart-circle/circles.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <!-- <script src="{{ asset('/main-assets/js/plugin/datatables/dataTables.fixedColumns.min.js') }}"></script> -->
    <!-- <script src="{{ asset('main-assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script> -->
    <script src="{{ asset('/main-assets/js/plugin/select2/select2.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/jqvmap/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/moment/moment.js') }}"></script>
    <script src="{{ asset('/main-assets/js/plugin/datepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/atlantis.min.js') }}"></script>
    <script src="{{ asset('/main-assets/js/setting-demo.js') }}"></script>
    <script src="{{ asset('/main-assets/bootstrap-material-datetimepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('/main-assets/bootstrap-material-datetimepicker/js/materialDateTimePicker.js') }}"></script>
    <script src="{{ asset('/main-assets/js/accounting.js') }}"></script>
    </script>

    <script>
        $.ajax({
            url: '/dashboard/notifikasi',
            type: 'GET',
            success: function(response) {
                // console.log(response)
                $('.jml_notifikasi').html(response.jml_notif);
                $('.title_notifikasi').html('Anda Memiliki '+response.jml_notif+' Pemberitahuan');
                $('.notifikasi').empty();

                if(response.jml_notif > 0){
                    $.each(response.data, function(index, val) {
                        text_nama = val.nama;
                        text_tgl = moment(val.tgl_kontrak).format('DD-MM-YYYY');
                        text = '<a href="#">'+
                                    '<div class="notif-icon notif-success"> <i class="fa-solid fa-right-from-bracket"></i> </div>'+
                                    '<div class="notif-content">'+
                                        '<span class="block">Nama : '+
                                            text_nama+
                                        '</span>'+
                                        '<span class="time">Tanggal Habis Kontrak : '+text_tgl+'</span>'+
                                    '</div>'+
                                '</a>';
                        $('.notifikasi').html(text);
                    });
                }
                
            }
        })
    </script>
</body>

</html>