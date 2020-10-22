<?php   use App\Http\Controllers\ModuleController as module; ?>

    <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header bg-blue">
                <div class="logo">
                    <a href="{{ route('portal') }}"><img src="{{ asset('images/tms-white.png') }}" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            
<?php echo module::generateMenu(); ?>
                           
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
<script>
    $(document).ready(function(){
        var activeParentID = $('.active-menu').attr('parent-id');
        $('#menu-'+activeParentID).addClass('active');
        $('#menu-aria-'+activeParentID).attr('aria-expanded', 'true');
        $('#menu-ul-'+activeParentID).addClass('in');
    });

</script>