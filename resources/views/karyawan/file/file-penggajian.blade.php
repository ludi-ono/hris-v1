<!DOCTYPE html>
<html>

<head>
    <title>Slip Gaji Januari 2023</title>
</head>

<body style="font-family:Arial, Helvetica, sans-serif">
    <div style="width:100%">
        <!-- Gambar Sesuai Peruhaan -->
        <!-- <img style="width:20px" src="{{ asset('images/perusahaan/gis.jpeg') }}" /> -->
        <h3 style="text-align:center">{{$data->nama_perusahaan}}</h3>
        <p style="text-align:center;font-size:12px;">{{$data->alamat_perusahaan}}</p>
        <hr>
    </div>
    <div style="width:100%">
        <h4 style="text-align:center">Slip Gaji {{ $bulan }} - {{ $data->tahun }}</h4>
        <p style="text-align:center;font-size:12px;padding-top:-20px;font-weight:bold">{{ $data->nama }} - {{ $data->devisi }}</p>
    </div>
    <div style="width:100%">
        <p><b>Take Home Pay</b></p>
        <table style="width:80%;margin: 0 auto;">
            <tbody>
                <tr>
                    <td style="width:250px;padding-bottom:7px">Gaji Pokok</td>
                    <td style="width:20px;padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px">Rp. {{number_format($data->gaji_pokok)}}</td>
                </tr>
                <tr>
                    <td style="padding-bottom:7px">Presensi <b>(20/21 Hari Bekerja)</b></td>
                    <td style="padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px">Rp. {{number_format($presesnsi)}}</td>
                </tr>
                <tr>
                    <td style="padding-bottom:7px">Tunjangan Jabatan</td>
                    <td style="padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px">Rp. {{number_format($data->tunjangan_jabatan)}}</td>
                </tr>
                <tr>
                    <td style="padding-bottom:7px">Tunjangan Pulsa</td>
                    <td style="padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px">Rp. {{number_format($data->tunjangan_pulsa)}}</td>
                </tr>
                <tr>
                    <td style="padding-bottom:7px">Lembur <b>({{$jam_lembur}} Jam, {{$menit_lembur}} Menit)</b></td>
                    <td style="padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px">Rp. {{number_format($lembur)}}</td>
                </tr>
            </tbody>
        </table>
        @php
            $total_1 = $data->gaji_pokok + $presesnsi + $data->tunjangan_jabatan + $data->tunjangan_pulsa + $lembur;
            $total_2 = $data->bpjs_ketenagakerjaan + $data->bpjs_kesehatan;
            $total_3 = $total_1 - $total_2;
        @endphp
        <div style="width:80%;text-align:right;margin: 0 auto;">
            <h3 style="font-size:17px">Rp. {{number_format($total_1)}}</h3>
        </div>

        <p><b>Potongan</b></p>
        <table style="width:80%;margin: 0 auto;">
            <tbody>
                <tr>
                    <td style="width:250px;padding-bottom:7px">BPJS Ketenagakerjaan</td>
                    <td style="width:20px;padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px;color:red">- Rp. {{number_format($data->bpjs_ketenagakerjaan)}}</td>
                </tr>
                <tr>
                    <td style="width:250px;padding-bottom:7px">BPJS Kesehatan</td>
                    <td style="width:20px;padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px;color:red">- Rp. {{number_format($data->bpjs_kesehatan)}}</td>
                </tr>
                <tr>
                    <td style="width:250px;padding-bottom:7px">Kasbon</td>
                    <td style="width:20px;padding-bottom:7px">:</td>
                    <td colspan="2" style="text-align:right;padding-bottom:7px;color:red">- Rp. 0</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <div style="width:80%;text-align:right;margin: 0 auto;">
            <h3 style="color:green">Rp. {{number_format($total_3)}}</h3>
        </div>
    </div>
</body>

</html>