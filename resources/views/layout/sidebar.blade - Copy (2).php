<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
  <ul class="nav sidebar-nav">
    <li class="nav-item nav-profile not-navigation-link">
    </li>
    <li class="nav-item sidebar-nav-item quotation-meun {{ active_class(['quotation']) }} ">
      <a class="nav-link sidebar-nav-item" href="{{ url('quotation') }}">
        {{-- <i class="fa fa-pencil-square-o" aria-hidden="true"></i> --}}
        <i class="mdi mdi-pencil-box-outline"></i>
        <label class="sidebar-title menu-title">ใบเสนอราคา</label>
      </a>
    </li>
    <li class="nav-item sidebar-nav-item orders-meun {{ active_class(['orders']) }} ">
      <a class="nav-link sidebar-nav-item" href="{{ url('orders') }}">
        <i class="mdi mdi-layers"></i>
        <label class="sidebar-title menu-title">รายการบิลหลัก</label>
      </a>
    </li>
    <li class="nav-item sidebar-nav-item customer-meun {{ active_class(['customer']) }} ">
      <a class="nav-link sidebar-nav-item" href="{{ url('customer') }}">
        <i class="mdi mdi-store"></i>
        <label class="sidebar-title menu-title">รายชื่อร้านค้า</label>
      </a>
    </li>
<!--
Pera add 
-->
	<li class="nav-item sidebar-nav-item customer-meun {{ active_class(['customer/barcode']) }} ">
      <a class="nav-link sidebar-nav-item" href="{{ url('customer/barcode') }}">
        <i class="mdi mdi-store"></i>
        <label class="sidebar-title menu-title">บาร์โค๊ด</label>
      </a>
    </li>
<!--
Pera end 
-->
    <li class="nav-item {{ active_class(['report/*']) }}">
        <a class="nav-link sidebar-nav-item {{ active_class(['report/*']) }}" data-toggle="collapse" href="#report_page" aria-expanded="{{ is_active_route(['report/*']) }}"  aria-controls="report_page">
            <i class="mdi mdi-file-document"></i>
            <label class="sidebar-title menu-title">รายงาน</label>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse {{ show_class(['report/*']) }}" id="report_page">
          <ul class="nav flex-column sub-menu">
            {{-- <li class="nav-item sidebar-nav-item users-meun submenu {{ active_class(['report/quotation']) }} ">
                <a class="nav-link sidebar-nav-item" href="{{ url('/report/quotation') }}">
                  <label class="sidebar-submenu menu-title">รายงานยอดสั่งซื้อใบเสนอราคา</label>
                  </a>
            </li> --}}
            <li class="nav-item sidebar-nav-item users-meun submenu {{ active_class(['report/orders']) }} ">
              <a class="nav-link sidebar-nav-item" href="{{ url('/report/orders') }}">
                <label class="sidebar-submenu menu-title">รายงานยอดสั่งซื้อบิลหลัก</label>
                </a>
            </li>

            {{-- <li class="nav-item {{ active_class(['basic-ui/dropdowns']) }}">
              <a class="nav-link" href="{{ url('/basic-ui/dropdowns') }}">Dropdowns</a>
            </li>
            <li class="nav-item {{ active_class(['basic-ui/typography']) }}">
              <a class="nav-link" href="{{ url('/basic-ui/typography') }}">Typography</a>
            </li> --}}
          </ul>
        </div>
    </li>

    @if(Auth::user()->role_id != 3)
    <li class="nav-item sidebar-nav-item users-meun {{ active_class(['users']) }} ">
        <a class="nav-link sidebar-nav-item" href="{{ url('users') }}">
            <i class="mdi mdi-lock"></i>
            <label class="sidebar-title menu-title">ผู้ดูแลระบบ</label>
        </a>
    </li>
    @endif
    {{--

    <li class="nav-item {{ active_class(['charts/chartjs']) }}">
      <a class="nav-link" href="{{ url('/charts/chartjs') }}">
        <i class="menu-icon mdi mdi-chart-line"></i>
        <span class="menu-title">Charts</span>
      </a>
    </li> --}}
    {{-- <li class="nav-item {{ active_class(['tables/basic-table']) }}">
      <a class="nav-link" href="{{ url('/tables/basic-table') }}">
        <i class="menu-icon mdi mdi-table-large"></i>
        <span class="menu-title">Tables</span>
      </a>
    </li>
    <li class="nav-item {{ active_class(['icons/material']) }}">
      <a class="nav-link" href="{{ url('/icons/material') }}">
        <i class="menu-icon mdi mdi-emoticon"></i>
        <span class="menu-title">Icons</span>
      </a>
    </li>
    <li class="nav-item {{ active_class(['user-pages/*']) }}">
      <a class="nav-link" data-toggle="collapse" href="#user-pages" aria-expanded="{{ is_active_route(['user-pages/*']) }}" aria-controls="user-pages">
        <i class="menu-icon mdi mdi-lock-outline"></i>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse {{ show_class(['user-pages/*']) }}" id="user-pages">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item {{ active_class(['user-pages/login']) }}">
            <a class="nav-link" href="{{ url('/user-pages/login') }}">Login</a>
          </li>
          <li class="nav-item {{ active_class(['user-pages/register']) }}">
            <a class="nav-link" href="{{ url('/user-pages/register') }}">Register</a>
          </li>
          <li class="nav-item {{ active_class(['user-pages/lock-screen']) }}">
            <a class="nav-link" href="{{ url('/user-pages/lock-screen') }}">Lock Screen</a>
          </li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="https://www.bootstrapdash.com/demo/star-laravel-free/documentation/documentation.html" target="_blank">
        <i class="menu-icon mdi mdi-file-outline"></i>
        <span class="menu-title">Documentation</span>
      </a>
    </li> --}}
  </ul>
</nav>
