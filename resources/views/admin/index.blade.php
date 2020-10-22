@extends('master')

@section('content')           

<div class="main-content-inner">

    <div class="sales-report-area mt-5 mb-5">
        <div class="row">
            <div class="col-md-4">
                <div class="single-report mb-xs-30">
                    <div class="s-report-inner pr--20 pt--15 mb-3">
                        <div class="icon"><i class="ti-user"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Users</h4>
                            <h3>{{ $countUser }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-report mb-xs-30">
                    <div class="s-report-inner pr--20 pt--15 mb-3">
                        <div class="icon"><i class="ti-key"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Roles</h4>
                            <h3>{{ $countRole }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="single-report">
                    <div class="s-report-inner pr--20 pt--15 mb-3">
                        <div class="icon"><i class="ti-menu"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Modules</h4>
                            <h3>{{ $countModule }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
        
@endsection
