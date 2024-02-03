@section('title', 'Dashboard')
@include('layouts.header')
<div class="main-panel">
    <div class="content">
        <div class="panel-header bg-warning-gradient">
            <div class="page-inner py-5">
                <div class="text-center align-items-center align-items-md-center flex-column flex-md-row">
                    <div>
                        <h1 class="text-white pb-2 fw-bold">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        <div id="list_data" class="page-inner mt--5">
            <div class="row mt--2">
                <!-- <div class="col-md-12">
                    <div id="" class="card full-height">
                        <div class="card-body">
                            <div class="row">
                                <div class="chart-container">
                                    <canvas id="chart_absensi"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Grafik Absensi Tahun Berjalan</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container-fluid">
        <div class="copyright ml-auto">
            2021, made by <a href="https://www.kemkes.go.id">GIS</a>
        </div>
    </div>
</footer>
</div>
@include('layouts.footer')
<!-- <script type="text/javascript" src="{{ asset('/js/dashboard.js') }}"></script> -->
<!-- <link rel="stylesheet" href="{{ asset('/main-assets/assets/js/plugin/chart.js/chart.min.js') }}"> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script>
    $("#nav-dashboard").addClass('active');

    $("div#master").siblings('a').attr('aria-expanded', 'false');
    $("div#master").removeClass("show");
    $("div#master #master-perusahaan").removeClass("active");
    $("div#master #master-devisi").removeClass("active");
    $("div#master #master-jabatan").removeClass("active");
    $("div#master #master-karyawan").removeClass("active");
    $("#nav-master").removeClass("active");

    var ChartHadir;
    var barChart = document.getElementById('barChart').getContext('2d');
    $.ajax({
            url: "/dashboard/list-absensi",
            type: 'GET',
            success: function (response) {
                data_s = JSON.parse(response);
                if(ChartHadir){
                    ChartHadir.destroy();
                }
                ChartHadir = new Chart(document.getElementById("barChart"), {
                    type: 'bar',
                    data: {
                        labels: data_s[5],
                        datasets: [{
                            label: "Hadir",
                            type: "bar",
                            borderColor: "#07edf5",
                            backgroundColor: '#a7f0f2',
                            data: data_s[0],
                            fill: false
                        }, {
                            label: "Telat",
                            type: "bar",
                            borderColor: "#f5a316",
                            backgroundColor: '#ebcc98',
                            data: data_s[1],
                            fill: false
                        }, {
                            label: "Cuti",
                            type: "bar",
                            borderColor: "#16f591",
                            backgroundColor: '#a6eb98',
                            data: data_s[2],
                            fill: false
                        }, {
                            label: "Tidak Hadir",
                            type: "bar",
                            borderColor: "#ff0c08",
                            backgroundColor: '#fc9c9a',
                            data: data_s[3],
                            fill: false
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        title: {
                            display: false,
                            text: 'Summary Tahun 2023'
                        },
                        legend: {
                            display: true
                        }
                    }
                });
            }
        });
    // var myBarChart = new Chart(barChart, {
    //         type: 'bar',
    //         data: {
    //             labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    //             datasets : [{
    //                 label: "Sales",
    //                 backgroundColor: 'rgba(75, 192, 192, 0.2)',
    //                 borderColor: 'rgb(75, 192, 192)',
    //                 data: [3, 2, 9, 5, 4, 6, 4, 6, 7, 8, 7, 4],
    //             }],
    //         },
    //         options: {
    //             responsive: true, 
    //             maintainAspectRatio: false,
    //             scales: {
    //                 yAxes: [{
    //                     ticks: {
    //                         beginAtZero:true
    //                     }
    //                 }]
    //             },
    //         }
    //     });
</script>