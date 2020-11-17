<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'TMS | Trimitra Manufacturing System')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tms-icon-blue.ico') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <!-- SRTDash native css -->
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/responsive.css') }}">

    <!-- Custom css -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <!-- modernizr css -->
    <script src="{{ asset('vendor/srtdash/js/vendor/modernizr-2.8.3.min.js') }}"></script>

    <script src="{{ asset('vendor/srtdash/js/vendor/jquery-2.2.4.min.js') }}"></script>
    
    @yield('css')

</head>

<body>
    
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->

    <!-- page container area start -->
    <div class="page-container">
        @include('partials.sidebar')

        <!-- main content area start -->
        <div class="main-content">
            @include('partials.header')
            @include('partials.page-title')
            @yield('content')
        </div>
        <!-- main content area end -->

        <footer>
            <div class="footer-area">
                <p>TMS - Web Version. Developed by IT Dept. <a href="https://pttrimitra.com">PT Trimitra Chitrahasta</a>.</p>
            </div>  
        </footer>
    </div>

    <!-- page container area end -->
    



    <!-- SRTDash native js -->
    
    <script src="{{ asset('vendor/srtdash/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/bootstrap.min.js') }}"></script>
    <!-- <script src="{{ asset('vendor/srtdash/js/owl.carousel.min.js') }}"></script> -->
    <script src="{{ asset('vendor/srtdash/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/jquery.slicknav.min.js') }}"></script>
    <!-- <script src="{{ asset('vendor/srtdash/js/line-chart.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/pie-chart.js') }}"></script> -->
    <script src="{{ asset('vendor/srtdash/js/plugins.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/scripts.js') }}"></script>
    <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
    @include('sweetalert::alert')
    <!-- Custom js -->
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> --}}
    <script src="{{ asset('js/custom-general.js') }}"></script>
    @stack('js')

    @yield('script')
    
</body>

</html>