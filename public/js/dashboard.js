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

    var barChart = document.getElementById('barChart').getContext('2d');

    var myBarChart = new Chart(barChart, {
        type: 'bar',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets : [{
                label: "Sales",
                backgroundColor: 'rgb(23, 125, 255)',
                borderColor: 'rgb(23, 125, 255)',
                data: [3, 2, 9, 5, 4, 6, 4, 6, 7, 8, 7, 4],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },
        }
    });

});