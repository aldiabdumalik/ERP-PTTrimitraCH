<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="tms login">
    <title>TMS | Login</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tms-icon-blue.ico') }}">
    <link rel="stylesheet" href="{{ asset('vendor/voyager/assets/css/app.css') }}">
    
    <style>
        body {
            background-image:url('{{ asset("images/tch-bg.jpg") }}');
            background-color: #FFFFFF;
        }
        body.login .login-sidebar {
            border-top:5px solid #22A7F0;
        }
        @media (max-width: 767px) {
            body.login .login-sidebar {
                border-top:0px !important;
                border-left:5px solid #22A7F0;
            }
        }
        body.login .form-group-default.focused{
            border-color: #22A7F0;
        }
        .login-button, .bar:before, .bar:after{
            background: #22A7F0;
        }
        .remember-me-text{
            padding:0 5px;
        }
    </style>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
</head>
<body class="login">
<div class="container-fluid">
    <div class="row">
        <div class="faded-bg animated"></div>
        <div class="hidden-xs col-sm-7 col-md-8">
            <div class="clearfix">
                <div class="col-sm-12 col-md-10 col-md-offset-2">
                    <div class="logo-title-container">
                        <img class="img-responsive pull-left flip logo hidden-xs animated fadeIn" src="{{ asset('images/tms-icon-white.png') }}" alt="Logo Icon">
                        <div class="copy animated fadeIn">
                            <h1>TMS</h1>
                            <p>Welcome to TMS (Trimitra Manufacturing System)</p>
                        </div>
                    </div> <!-- .logo-title-container -->
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-5 col-md-4 login-sidebar">

            <div class="login-container">

                @yield('content')

            </div> <!-- .login-container -->

        </div> <!-- .login-sidebar -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->

@yield('js')
</body>
</html>
