<!-- main header area start -->
        <div class="mainheader-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="logo">
                            <a href="#"><img src="{{ asset('images/tch-logo.png') }}" alt="logo"></a>
                        </div>
                    </div>
                    <!-- profile info & task notification -->
                    <div class="col-md-9 clearfix text-right">
                        
                        <div class="clearfix d-md-inline-block d-block">
                            <div class="user-profile m-0">
                                <img class="avatar user-thumb" src="{{ asset('vendor/srtdash/images/author/avatar.png') }}" alt="avatar">
                                <h4 class="user-name dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->FullName }} <i class="fa fa-angle-down"></i></h4>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Change Password</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main header area end -->