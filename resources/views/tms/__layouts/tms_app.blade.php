<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TMS</title>

    <!-- Fonts and icons -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('login-page/images/icons/favicon.ico') }}"/>

    <!-- CSS Files -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('vendor/srtdash/images/icon/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/bootstrap-datetimepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/slicknav.min.css') }}">
    <!-- Start datatable css -->
    <link rel="stylesheet" href="{{ asset('vendor/DataTables-1.10.20/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/DataTables-1.10.20/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/Responsive-2.2.3/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/Responsive-2.2.3/css/responsive.jqueryui.min.css') }}">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/tms_styles.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/srtdash/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    <!-- modernizr css -->
    <script src="{{ asset('vendor/srtdash/js/vendor/modernizr-2.8.3.min.js') }}"></script>

    <script src="{{ asset('vendor/srtdash/js/vendor/jquery-2.2.4.min.js') }}"></script>


</head>
<body>
    <!-- <div id="preloader">
        <div class="loader"></div>
    </div> -->

    <div class="page-container" id="tmsContainer">
        <!-- Sidebar -->
        @include('partials.sidebar')
        <!-- End Sidebar -->

        <div class="main-content">
                <!-- Navbar -->
                @include('tms.__layouts.tms_header')
                <!-- End Navbar -->
            <div class="sticky-header" id="tmsHeader">
                <!-- Page Title -->
                @include('tms.__layouts.tms_pagetitle')
                <!-- End Page Title -->
                <!-- Horizontal Menu -->
                @yield('tms_content_menuHorizontal')
                <!-- End Horizontal Menu -->
            </div>
        <!-- Content -->
        @yield('tms_content')
        <!-- End Content -->
        </div>
        <!-- Footer -->
        @include('tms.__layouts.tms_footer')
        <!-- End Footer -->
    </div>

    <!-- offset area start -->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
        <ul class="nav offset-menu-tab">
            <li><a class="active" data-toggle="tab" href="#activity">Activity</a></li>
            <li><a data-toggle="tab" href="#settings">Settings</a></li>
        </ul>
        <div class="offset-content tab-content">
            <div id="activity" class="tab-pane fade in show active">
                <div class="recent-activity">
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-check"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Added</h4>
                            <span class="time"><i class="ti-time"></i>7 Minutes Ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You missed you Password!</h4>
                            <span class="time"><i class="ti-time"></i>09:20 Am</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Member waiting for you Attention</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You Added Kaji Patha few minutes ago</h4>
                            <span class="time"><i class="ti-time"></i>01 minutes ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Ratul Hamba sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Hello sir , where are you, i am egerly waiting for you.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                </div>
            </div>
            <div id="settings" class="tab-pane fade">
                <div class="offset-settings">
                    <h4>General Settings</h4>
                    <div class="settings-list">
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch1" />
                                    <label for="switch1">Toggle</label>
                                </div>
                            </div>
                            <p>Keep it 'On' When you want to get all the notification.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show recent activity</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch2" />
                                    <label for="switch2">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show your emails</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch3" />
                                    <label for="switch3">Toggle</label>
                                </div>
                            </div>
                            <p>Show email so that easily find you.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show Task statistics</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch4" />
                                    <label for="switch4">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch5" />
                                    <label for="switch5">Toggle</label>
                                </div>
                            </div>
                            <p>Use checkboxes when looking for yes or no answers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- offset area end -->

    <!-- bootstrap 4 js -->
    <script src="{{ asset('vendor/srtdash/js/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/jquery.slicknav.min.js') }}"></script>
    <!-- bootstrap 4 js -->
    <script src="{{ asset('vendor/srtdash/js/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/srtdash/js/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Start datatable js -->
    <script src="{{ asset('vendor/DataTables-1.10.20/js/jquery.dataTables.min.js') }}"></script> -->
    <script src="{{ asset('vendor/DataTables-1.10.20/js/dataTables.bootstrap4.min.js') }}"></script> -->
    <script src="{{ asset('vendor/Responsive-2.2.3/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/Responsive-2.2.3/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- start chart js -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script> -->
    <!-- start highcharts js -->
    <script src="{{ asset('vendor/Highcharts-8.0.4/code/highcharts.js') }}"></script>
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
    <script src="{{ asset('js/custom_tms_cmb.js') }}"></script>
    <script src="{{ asset('js/custom_tms_datatable.js') }}"></script>
    <script src="{{ asset('js/custom_tms_chart.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.2/handlebars.min.js"></script>

    <script>
        window.onscroll = function() {onscrollFunction()};

        var header = document.getElementById("tmsHeader");
        var container = document.getElementById("tmsContainer");
        var sticky = header.offsetTop;

        function onscrollFunction() {
          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
            //container.classList.add("sbar_collapsed");
          } else {
            header.classList.remove("sticky");
            //container.classList.remove("sbar_collapsed");
          }
        }
    </script>

    @yield('jquery_content')

</body>
</html>
