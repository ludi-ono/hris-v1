<!DOCTYPE html>
<html lang="id" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <!-- Title -->
    <title>Sorry, This Page Can&#39;t Be Accessed</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous" />
    <script src="{{ asset('/main-assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('/main-assets/css/fonts.min.css') }}"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
</head>

<body style="background-color:#63716d !important" class="text-white py-5">
    <div class="container py-5">
        <div class="row justify-content-md-center">
            <div class="col-md-3 text-center">
                <p><i style="font-size:200px" class="fa fa-exclamation-triangle"></i><br />Status Code: 403</p>
            </div>
            <div class="col-md-5">
                <h3>MAAF AKSES DI TOLAK !!</h3>
                <p>Anda Tidak Memiliki Akses Pada Halaman Tersebut ...</p>
                <a class="btn btn-info" href="{{ url()->previous() }}"><i class="fas fa-arrow-left"></i> &nbsp; Kembali</a>
            </div>
        </div>
    </div>

    <div id="footer" class="text-center">
        Copyright © 2021 | <a href="http://www.kemkes.go.id" target="_blank" style="color:#3bbed5">Kementerian Kesehatan</a>
    </div>
</body>

</html>