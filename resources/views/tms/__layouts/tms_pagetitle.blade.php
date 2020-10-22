<!-- page title area start -->
<div class="page-title-area" id="tmsPageTitle">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Dashboard</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ url('/tms_home') }}">Home</a></li>
                    <li><span>Dashboard</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            <div class="user-profile pull-right">
                <h4 class="user-name dropdown-toggle" data-toggle="dropdown">
                    {{ Auth::user()->FullName }}
                    <i class="fa fa-angle-down"></i>
                </h4>
                <div class="dropdown-menu">
                    @if (Auth::guest())
                        <a href="{{ route('login') }}">Login</a>
                    @else
                        <a class="dropdown-item" href="#">
                            Change Password
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="#">
                            Logout
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- page title area end -->

