<!doctype html>
<html class="no-js" lang="en">

<head>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>TMS | Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tms-icon-blue.ico') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/slicknav.min.css') }}">
        <!-- others css -->
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/responsive.css') }}">
    <!-- modernizr css -->
    <script src="{{ asset('vendor/srtdash/js/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>

<body class="body-bg">
    
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->

    <!-- main wrapper start -->
    <div class="horizontal-main-wrapper">

        @include('partials.header-horizontal')
        
        @yield('content')

    </div>
    <!-- main wrapper start -->

    <!-- footer area start-->
    <footer>
        <div class="footer-area">
            <p>© Copyright 2018. All right reserved. Template by <a href="https://colorlib.com/wp/">Colorlib</a>.</p>
        </div>
    </footer>
    <!-- footer area end-->

    @include('partials.offset-area')

    <!-- jquery latest version -->
    <script src=" {{ asset('vendor/srtdash/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <!-- bootstrap 4 js -->
    <script src="{{ asset('vendor/srtdash/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/jquery.slicknav.min.js') }}"></script>

    <!-- start chart js -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script> -->
    <!-- start highcharts js -->
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
    <!-- start zingchart js -->
    <!-- <script src="https://cdn.zingchart.com/zingchart.min.js"></script> -->
    <!-- <script>
    zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
    </script> -->
    <!-- all line chart activation -->
    <script src="{{ asset('vendor/srtdash/js/line-chart.js') }}"></script>
    <!-- all pie chart -->
    <script src="{{ asset('vendor/srtdash/js/pie-chart.js') }}"></script>
    <!-- others plugins -->
    <script src="{{ asset('vendor/srtdash/js/plugins.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/scripts.js') }}"></script>

    @yield('js')
    
</body>

</html>
