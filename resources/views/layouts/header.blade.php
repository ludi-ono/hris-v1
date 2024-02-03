<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>HRIS System | @yield('title')</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{ asset('/images/icon-hris.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Fonts and icons -->
    <script src="{{ asset('/main-assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            // custom: {
            //     "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
            //     urls: ["{{ asset('/main-assets/css/fonts.min.css') }}"]
            // },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('/main-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/main-assets/css/atlantis.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/main-assets/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/main-assets/css/datatables.select.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/main-assets/css/fixedColumns.dataTables.min.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('main-assets/css/bootstrap-glyphicons.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('/main-assets/js/plugin/select2/select2.min.css') }}">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <!-- <link rel="stylesheet" href="{{ asset('/main-assets/chart-js/Chart.js') }}"> -->

    <!-- material date time -->
    <link rel="stylesheet" href="{{ asset('/main-assets/bootstrap-material-datetimepicker/css/materialDateTimePicker.css') }}">

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<style type="text/css">
    .font_merah {
        cursor: pointer;
        font-weight: bold;
        color: red;
    }

    .font_biru {
        cursor: pointer;
        font-weight: bold;
        color: blue;
    }

    .display-none {
        display: none !important;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, .8);
        z-index: 999;
        opacity: 1;
        transition: all 0.5s;
    }


    .lds-dual-ring {
        display: inline-block;
    }

    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 30% auto;
        border-radius: 50%;
        border: 6px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }

    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="orange">

                <a href="/dashboard" class="logo">
                    <img src="{{ asset('/images/logo-hris.png') }}" style="width:170px" alt="navbar brand" class="navbar-brand">
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="orange2">

                <div class="container-fluid">
                    <div class="collapse" id="search-nav">
                        <form class="navbar-left navbar-form nav-search mr-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pr-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control">
                            </div>
                        </form>
                    </div>
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item toggle-nav-search hidden-caret">
                            <a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
                                <i class="fa fa-search"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell"></i>
                                <span class="notification jml_notifikasi">0</span>
                            </a>
                            <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                                <li>
                                    <div class="dropdown-title title_notifikasi">Anda Memiliki 0 Pemeberitahuan</div>
                                </li>
                                <li>
                                    <div class="notif-scroll scrollbar-outer">
                                        <div class="notif-center notifikasi">
                                            <a href="#">
                                                <div class="notif-icon notif-primary"> <i class="fa fa-user-plus"></i> </div>
                                                <div class="notif-content">
                                                    <span class="block">
                                                        New user registered
                                                    </span>
                                                    <span class="time">5 minutes ago</span>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="notif-icon notif-success"> <i class="fa-solid fa-right-from-bracket"></i> </div>
                                                <div class="notif-content">
                                                    <span class="block">
                                                        Rahmad Mengajukan Cuti
                                                    </span>
                                                    <span class="time">12 minutes ago</span>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="notif-img">
                                                    <img src="../assets/img/profile2.jpg" alt="Img Profile">
                                                </div>
                                                <div class="notif-content">
                                                    <span class="block">
                                                        Reza send messages to you
                                                    </span>
                                                    <span class="time">12 minutes ago</span>
                                                </div>
                                            </a>
                                            <a href="#">
                                                <div class="notif-icon notif-danger"> <i class="fa fa-heart"></i> </div>
                                                <div class="notif-content">
                                                    <span class="block">
                                                        Farrah liked Admin
                                                    </span>
                                                    <span class="time">17 minutes ago</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <!-- <li>
                                    <a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i> </a>
                                </li> -->
                            </ul>
                        </li>
                        <li class="nav-item hidden-caret">
                            <p style="color:white;text-align:right;margin-top:3px;font-size:14px;font-weight:bold;">{{ Session::get('sess_nama') }}&nbsp; </p>
                            <p style="color:white;text-align:right;margin-top:-22px;font-size:12px;">{{ Session::get('sess_devisi') }} &nbsp; </p>
                            <!-- <p style="padding-top:-10px;color:#595151;">Human Resource &nbsp; </p> -->
                        </li>
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <img src="{{ asset('/images/user.png') }}" alt="..." class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg"><img src="{{ asset('/images/user.png') }}" alt="image profile" class="avatar-img rounded"></div>
                                            <div class="u-text">
                                                <h4>{{ Session::get('sess_nama') }}</h4>
                                                {{-- sementara di hidden tidak tau fungsinya di tampilkan --}}
                                                <p class="text-muted" style="display: none;">{{ Session::get('sess_id_karyawan') }}</p> 
                                                <p class="text-muted">{{ Session::get('sess_devisi') }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/profil">Profil Saya</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/logout">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>

        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2">
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-warning">
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Information</h4>
                        </li>
                        <li class="nav-item active" id="nav-dashboard">
                            <a href="/dashboard">
                                <i class="fas fa-chart-bar"></i>
                                <p>Dashboard</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>

                        @if(Session::get('sess_id_devisi') == 1 || Session::get('sess_id_devisi') == 10)
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Employee</h4>
                        </li>
                        <li class="nav-item" id="nav-master">
                            <a data-toggle="collapse" href="#master">
                                <i class="fas fa-database"></i>
                                <p>Master Data</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="master">
                                <ul class="nav nav-collapse">
                                    <li id="master-perusahaan">
                                        <a href="/master-perusahaan">
                                            <span class="sub-item">Master Perusahaan</span>
                                        </a>
                                    </li>
                                    <li id="master-devisi">
                                        <a href="/master-devisi">
                                            <span class="sub-item">Master Devisi</span>
                                        </a>
                                    </li>
                                    <li id="master-jabatan">
                                        <a href="/master-jabatan">
                                            <span class="sub-item">Master Jabatan</span>
                                        </a>
                                    </li>
                                    <li id="master-karyawan">
                                        <a href="/master-karyawan">
                                            <span class="sub-item">Master Karyawan</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item" id="nav-thp-karyawan">
                            <a href="/thp-karyawan">
                                <i class="fa-solid fa-users"></i>
                                <p>THP Karyawan</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-bpjs-kesehatan">
                            <a href="/bpjs-kesehatan">
                                <i class="fa-solid fa-truck-medical"></i>
                                <p>BPJS Kesehatan</p>
                            </a>
                        </li>
                        <li class="nav-item" id="nav-bpjs-ketenagakerjaan">
                            <a href="/bpjs-ketenagakerjaan">
                                <i class="fa-solid fa-users"></i>
                                <p>BPJS Ketenaga Kerjaan</p>
                            </a>
                        </li>
                        @endif

                        @if(Session::get('sess_id_devisi') == 1 || Session::get('sess_id_devisi') == 10)
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Attendance</h4>
                        </li>
                        <li class="nav-item" id="nav-absen-karyawan">
                            <a href="/absen-karyawan">
                                <i class="fas fa-download"></i>
                                <p>Absen Karyawan</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                            <div class="collapse" id="absensi">
                                <ul class="nav nav-collapse">
                                    <li id="absensi-office">
                                        <a href="/absesnsi-office-gis">
                                            <span class="sub-item">Import Absen Office</span>
                                        </a>
                                    </li>
                                    <li id="absensi-office">
                                        <a href="/absesnsi-cleaning-service">
                                            <span class="sub-item">Import Absen Cleaning Service</span>
                                        </a>
                                    </li>
                                    <li id="absensi-office">
                                        <a href="/absesnsi-security">
                                            <span class="sub-item">Import Absen Security</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item" id="nav-absen-officeboy">
                            <a href="/absen-officeboy">
                                <i class="fas fa-download"></i>
                                <p>Absen Office Boy</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-absen-security">
                            <a href="/absen-security">
                                <i class="fas fa-download"></i>
                                <p>Absen Security</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-absen-potongan">
                            <a href="/absen-potongan">
                                <i class="fa-solid fa-user-minus"></i>
                                <p>Potongan Cuti Bersama</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        @endif

                        @if(Session::get('sess_approve') == 1 || Session::get('sess_approve') == 2)
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Approval</h4>
                        </li>
                        @endif

                        @if(Session::get('sess_approve') == 2)
                        <li class="nav-item" id="nav-approve-spl">
                            <a href="/approve-spl-manager">
                                <i class="fa-solid fa-list-check"></i>
                                <p>Approve SPL Manager</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-approve-tidak-hadir-manager">
                            <a href="/approve-absen-manager">
                                <i class="fa-solid fa-house-circle-check"></i>
                                <p>Approve CIS Manager</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        @endif

                        @if(Session::get('sess_approve') == 1)
                        <li class="nav-item" id="nav-approve-spl">
                            <a href="/approve-spl-hrd">
                                <i class="fa-solid fa-list-check"></i>
                                <p>Approve SPL HRD</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-approve-tidak-hadir-hrd">
                            <a href="/approve-absen-hrd">
                                <i class="fa-solid fa-house-circle-check"></i>
                                <p>Approve CIS HRD</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <!-- <li class="nav-item" id="nav-approve-kasbon">
                            <a href="/approve-kasbon">
                                <i class="fa-solid fa-money-check-dollar"></i>
                                <p>Approve Kasbon</p>
                            </a>
                        </li> -->
                        @endif

                        @if(Session::get('sess_id_devisi') == 1 || Session::get('sess_id_devisi') == 10)
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Payroll</h4>
                        </li>
                        <li class="nav-item" id="nav-generate-presensi">
                            <a href="/generate-presensi">
                                <i class="fa-solid fa-money-bill-trend-up"></i>
                                <p>Perhitungan Presensi</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-generate-lembur">
                            <a href="/generate-lembur">
                                <i class="fa-brands fa-stack-overflow"></i>
                                <p>Perhitungan Lembur</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>

                        <li class="nav-item" id="nav-generate-gaji">
                            <a href="/generate-gaji">
                                <i class="fa-solid fa-money-bills"></i>
                                <p>Perhitungan Gaji</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>

                        <li class="nav-item" id="nav-generate-thp">
                            <a href="/generate-thp">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                                <p>Take Home Pay (PPh 21)</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        @endif
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Karyawan</h4>
                        </li>
                        <li class="nav-item" id="nav-karyawan-cuti">
                            <a href="/karyawan-cuti">
                                <i class="fa-solid fa-file-export"></i>
                                <p>Pengajuan Cuti</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-karyawan-izin">
                            <a href="/karyawan-izin">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <p>Izin Tidak Hadir</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-karyawan-sakit">
                            <a href="/karyawan-sakit">
                                <i class="fa-solid fa-face-dizzy"></i>
                                <p>Izin Sakit</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <li class="nav-item" id="nav-karyawan-spl">
                            <a href="/karyawan-spl">
                                <i class="fa-solid fa-briefcase"></i>
                                <p>Surat Perintah Lembur</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                        <!-- <li class="nav-item" id="nav-karyawan-kasbon">
                            <a href="/karyawan-kasbon">
                                <i class="fa-solid fa-book"></i>
                                <p>Kasbon</p>
                            </a>
                        </li> -->
                        <li class="nav-item" id="nav-slip-gaji">
                            <a href="/slip-gaji">
                                <i class="fa-solid fa-money-check-dollar"></i>
                                <p>Slip Gaji</p>
                                <!-- <span class="badge badge-success">4</span> -->
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->