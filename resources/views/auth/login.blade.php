<!doctype html>
<html lang="en">

<head>
    <title>HRIS System - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="login-assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center order-md-last">
                            <div class="text w-100">
                                <!-- <h2>Selamat Datang</h2> -->
                                <img style="width: 370px;" src="login-assets/images/animasi.svg" />
                                <!-- <a href="#" class="btn btn-white btn-outline-white">Sign Up</a> -->
                            </div>
                        </div>
                        <div class="login-wrap p-4 p-lg-5">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <img style="width:200px;padding-bottom:10px" src="login-assets/images/logo-hris.png" />
                                </div>
                            </div>
                            @if(\Session::has('alert-berhasil'))
                            <div class="alert alert-success" style="padding:10px" role="alert">
                                <i class="fas fa-check"></i> &nbsp; {{ Session::get('alert-berhasil') }}
                            </div>
                            @endif
                            @if(\Session::has('alert-verifikasi'))
                            <div class="alert alert-warning" style="padding:10px" role="alert">
                                <i class="fas fa-times-circle"></i> &nbsp; {{ Session::get('alert-verifikasi') }}
                            </div>
                            @endif
                            @if(\Session::has('alert-wrong'))
                            <div class="alert alert-danger" style="padding:10px" role="alert">
                                <i class="fas fa-times-circle"></i> &nbsp; {{ Session::get('alert-wrong') }}
                            </div>
                            @endif
                            @if(\Session::has('alert-noaccount'))
                            <div class="alert alert-danger" style="padding:10px" role="alert">
                                <i class="fas fa-times-circle"></i> &nbsp; {{ Session::get('alert-noaccount') }}
                            </div>
                            @endif
                            @if(\Session::has('alert-logout'))
                            <div class="alert alert-success" style="padding:10px" role="alert">
                                <i class="fas fa-check"></i> &nbsp; {{ Session::get('alert-logout') }}
                            </div>
                            @endif
                            <form method="POST" action="{{ route('postlogin') }}" enctype="multipart/form-data" class="signin-form">
                                {{ csrf_field() }}
                                <div class="form-group mb-3">
                                    <label class="label" for="name">Username</label>
                                    <input id="username" name="username" type="text" class="form-control" placeholder="Masukan Username">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="password">Password</label>
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Masukan Password">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary submit px-3">Login</button>
                                </div>
                                <div class="form-group d-md-flex">
                                    <div class="text-left">
                                        <div class="text-md-right">
                                            <a href="/login-lupa-password">Lupa Password</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <p>Belum Memiliki Akun?<br>Silahkan <a href="/register" class="btn-link"><u>&nbsp;Register</u></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script src="login-assets/js/jquery.min.js"></script>
<script src="login-assets/js/popper.js"></script>
<script src="login-assets/js/bootstrap.min.js"></script>
<script src="login-assets/js/main.js"></script>

</html>