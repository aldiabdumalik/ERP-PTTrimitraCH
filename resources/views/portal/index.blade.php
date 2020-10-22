@extends('master-horizontal')

@section('content')

<div class="main-content-inner">
    <div class="container">
        <div class="row">

        <!-- Modules Area Start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="header-title">Modules</div>
                        <div class="row">
                        @foreach($modules as $module)
                            <div class="col-lg-2 col-md-4 col-sm-6 module-icon-container">
                                <div class="row icon-container">
                                    <div class="col">
                                        <a href="{{ $module->url }}">
                                            <img class="module-icon" src="{{ asset('images/module-icons/').'/'.$module->icon }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="{{ $module->url }}">{{ $module->name }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                                <!-- Start 2 column grid system -->
                                <!-- <div class="row">
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="grid-col">.col-2</div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="grid-col">.col-2</div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="grid-col">.col-2</div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="grid-col">.col-2</div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="grid-col">.col-2</div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6">
                                        <div class="grid-col">.col-2</div>
                                    </div>
                                </div> -->
                                
                            </div>
                        </div>
                    </div>
                    <!-- Bootstrap Grid end -->
                </div>
            </div>
        </div>
        <!-- main content area end -->
@endsection

@section('js')

@endsection