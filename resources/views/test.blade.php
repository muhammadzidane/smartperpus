<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smartperpus</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/dist/book-carousel.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('js/dist/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom-css.css') }}">
    <style>
        .footer-logo {
            overflow: hidden;
            text-align: center;
        }

        .footer-logo:before,
        .footer-logo:after {
            background-color: #E2D5D5;
            content: "";
            display: inline-block;
            height: 5px;
            position: relative;
            vertical-align: middle;
            width: 50%;
        }

        .footer-logo:before {
            right: 9.80vw;
            margin-left: -50%;
        }

        .footer-logo:after {
            left: 9.80vw;
            margin-right: -50%;
        }
    </style>
</head>
<body>
<footer>
    <div>
        <div class="footer-logo">
            <img class="w-15" src="{{ asset('img/logo.png') }}" alt="">
        </div>
        <div class="d-flex justify-content-center mt-5 bg-grey-2 py-4">
            <div class="mr-5"><i class="fab fa-facebook-f"></i></div>
            <div class="mr-5"><i class="fab fa-twitter"></i></div>
            <div><i class="fab fa-instagram"></i></div>
        </div>
        <div class="white-content-0 pt-5">
            <div class="container d-flex">
                <div class="footer-content">
                    <div>
                        <h4 class="hd-18">Tentang Smartperpus</h4>
                    </div>
                    <div class="mt-4">
                        <div class="text-grey">
                            Smartperpus adalah toko online / offline yang menyediakan buku-buku berkualitas dan original yang
                            tersedia dalam bentuk buku cetak dan ebook (file pdf). Toko offline berada di Jl. Pasir Honje No.
                            221 RT004/01, Cimenyan, Kota Bandung dan telah berdiri sejak tahun 2021.
                        </div>
                    </div>
                </div>
                <div class="footer-content">
                    <div>
                        <div>
                            <h4 class="hd-18">Pembayaran</h4>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <img src="{{ asset('img/transfer/bri-edit.png') }}">
                                </div>
                                <div class="mr-3">
                                    <img src="{{ asset('img/transfer/gopay-edit.png') }}">
                                </div>
                                <div>
                                    <img src="{{ asset('img/transfer/bni-edit.png') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mt-c">
                            <h4 class="hd-18">Pengiriman</h4>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <img src="{{ asset('img/transfer/bri-edit.png') }}">
                                </div>
                                <div class="mr-3">
                                    <img src="{{ asset('img/transfer/gopay-edit.png') }}">
                                </div>
                                <div>
                                    <img src="{{ asset('img/transfer/bni-edit.png') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="footer-content">
                    <div>
                        <h4 class="hd-18">Kontak Kami</h4>
                    </div>
                    <div class="mt-4 text-grey">
                        <div><i class="fas fa-phone mr-1"></i><span>(WA) 0895364040902</span></div>
                        <div><i class="fas fa-phone mr-1"></i><span>(WA) 081321407123</span></div>
                        <div><i class="fas fa-phone mr-1"></i><span>(Telp) 0223938123</span></div>
                    </div>
                </div>
            </div>
            <div class="footer-year"><h4 class="hd-14">Smartperpus - 2021</h4></div>
        </div>
    </div>
</footer>
</body>
</html>
