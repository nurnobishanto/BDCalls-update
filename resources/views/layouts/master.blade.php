<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Link of CSS files -->
    <link rel="stylesheet" href="{{asset('website')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/aos.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/all.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/odometer.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/remixicon.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/magnific-popup.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/meanmenu.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{asset('website')}}/css/style.css">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <style>
        /**{*/
        /*    font-family: "Hind Siliguri", sans-serif;*/
        /*}*/
    </style>
    <!--=== SEO Data ===-->
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}

    @include('website.includes.custom_style')
    @yield('custom_css')
</head>
<body>

<!-- Start Navbar Area -->
@include('website.includes.header')
<!-- End Navbar Area -->
@yield('content')

<!-- Start Footer Wrap Area -->
@include('website.includes.footer')

<!-- End Footer Wrap Area -->

<div class="go-top"><i class="ri-arrow-up-s-line"></i></div>

<!-- Link of JS files -->
<script src="{{asset('website')}}/js/jquery.min.js"></script>
<script src="{{asset('website')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('website')}}/js/owl.carousel.min.js"></script>
<script src="{{asset('website')}}/js/swiper-bundle.min.js"></script>
<script src="{{asset('website')}}/js/magnific-popup.min.js"></script>
<script src="{{asset('website')}}/js/meanmenu.min.js"></script>
<script src="{{asset('website')}}/js/appear.min.js"></script>
<script src="{{asset('website')}}/js/odometer.min.js"></script>
<script src="{{asset('website')}}/js/form-validator.min.js"></script>
<script src="{{asset('website')}}/js/contact-form-script.js"></script>
<script src="{{asset('website')}}/js/ajaxchimp.min.js"></script>
<script src="{{asset('website')}}/js/aos.js"></script>
<script src="{{asset('website')}}/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('sweetalert::alert')

@include('website.includes.custom_script')
@yield('custom_js')

</body>
</html>
