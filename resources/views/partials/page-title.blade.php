
<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <?php 
                $routeName = (string) Route::currentRouteName();
                    if(isset($breadcrumbItem)){
                        $breadcrumbs = Breadcrumbs::generate($routeName, $breadcrumbItem); 
                    } else {
                        $breadcrumbs = Breadcrumbs::generate($routeName); 
                    }
                ?>
                            
                @if (count($breadcrumbs))
                    <h4 class="page-title pull-left">{{ $breadcrumbs[0]->title }}</h4>
                    <ul class="breadcrumbs pull-left">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if(!$loop->first)
                                @if ($breadcrumb->url && !$loop->last)
                                    <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                                @else
                                    <li><span>{{ $breadcrumb->title }}</span></li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="col-sm-6 clearfix">
            <div class="user-profile pull-right">
                <img class="avatar user-thumb" src="{{ asset('vendor/srtdash/images/author/avatar.png') }}" alt="avatar">
                <h4 class="user-name dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->FullName }}<i class="fa fa-angle-down"></i></h4>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Change Password</a>
                    <a class="dropdown-item" href="{{ route('logout') }}">Log Out</a>
                </div>
            </div>
        </div>
    </div>

    
</div>

<div id="div-sticky"></div>
<div id="sticky-div"></div>
    
<!-- page title area end -->