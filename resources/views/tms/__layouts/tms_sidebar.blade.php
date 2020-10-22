<!-- sidebar menu area start -->
<div class="sidebar-menu">
  <div class="sidebar-header">
      <div class="logo">
          <a href="{{ url('/') }}"><img src="{{asset('vendor/srtdash/images/icon/tms-logo-2.png')}}" alt="logo"></a>
      </div>
  </div>
  <div class="main-menu">
      <div class="menu-inner">
          <nav>
              <ul class="metismenu" id="menu">
                  <li class="active">
                      <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i>
                        <span>Dashboard</span>
                      </a>
                      <ul class="collapse">
                          <li class="active"><a href="{{ route('tms_Dashboard') }}">Sales dashboard</a></li>
                          <li><a href="javascript:void(0)">Ecommerce dashboard</a></li>
                          <li><a href="javascript:void(0)">SEO dashboard</a></li>
                      </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-media-overlay"></i>
                      <span>Engineering</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="javascript:void(0)">Project</a></li>
                        <li><a href="javascript:void(0)">Maintenance</a></li>
                    </ul>
                  </li>
                  <li>
                      <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-sidebar-left"></i>
                        <span>Sales</span>
                      </a>
                      <ul class="collapse">
                          <li><a href="javascript:void(0)">RFQ</a></li>
                          <li><a href="javascript:void(0)">PO/OP</a></li>
                          <li><a href="javascript:void(0)">SO</a></li>
                          <li><a href="javascript:void(0)">SSO</a></li>
                      </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-menu-v"></i>
                      <span>Manufacturing</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="{{ route('tms_ManufacturingPlanning_Index') }}">Planning</a></li>
                        <li><a href="javascript:void(0)">Production</a></li>
                        <li><a href="javascript:void(0)">Quality</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-grid2"></i>
                      <span>Inventory</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="javascript:void(0)">MTO</a></li>
                        <li><a href="javascript:void(0)">Stock In/Out</a></li>
                        <li><a href="javascript:void(0)">Warehouse Trf</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-media-overlay"></i>
                      <span>Delivery</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="javascript:void(0)">SJ_Customer</a></li>
                        <li><a href="javascript:void(0)">CL_Customer</a></li>
                        <li><a href="javascript:void(0)">SJ_Supplier</a></li>
                        <li><a href="javascript:void(0)">CL_Supplier</a></li>
                        <li><a href="javascript:void(0)">RG</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-menu-v"></i>
                      <span>Account Receivable</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="javascript:void(0)">Invoice</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-menu-v"></i>
                      <span>Account Payable</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="javascript:void(0)">PPB</a></li>
                        <li><a href="javascript:void(0)">PO</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-menu-v"></i>
                      <span>General Ledger</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="javascript:void(0)">COA</a></li>
                        <li><a href="javascript:void(0)">Accounting</a></li>
                        <li><a href="javascript:void(0)">Budgeting</a></li>
                    </ul>
                  </li>
                  <li>
                    <a href="javascript:void(0)" aria-expanded="true"><i class="ti-layout-menu-v"></i>
                      <span>Master Data</span>
                    </a>
                    <ul class="collapse">
                        <li><a href="{{ route('tms_MasterItem_Index') }}">Master Item</a></li>
                        <li><a href="javascript:void(0)">Master BoM</a></li>
                        <li><a href="javascript:void(0)">Master Machine</a></li>
                    </ul>
                  </li>
              </ul>
          </nav>
      </div>
  </div>
</div>
<!-- sidebar menu area end -->
