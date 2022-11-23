<?php 
  $groupPrefix = Request::segment(1);
  $subModule   = Request::segment(2);
  $prefixArray = [$groupPrefix, $subModule];

  $moduleData = getModuleData();
?>

<div class="sidebar p-2 py-md-3 @@cardClass version-2">
  <div class="container-fluid">
    <div class="title-text d-flex align-items-center mb-4 mt-1">
      <img class="sidebar-img img-thumbnail shadow rounded-circle" src="{{ asset('assets/img/epldt-suite-logo.png') }}" height="50" width="50" alt="ePLDT">
      <h4 class="sidebar-title mb-0 flex-grow-1 px-2 d-none d-xl-block"><span class="sm-txt fw-bold" style="color: #d0021b;">ePLDT</h4>
    </div>

    <div class="main-menu flex-grow-1">
      <ul class="menu-list">
        @foreach ($moduleData as $module)
        <li class="{{ in_array($module['Prefix'], $prefixArray) ? 'collapsed' : '' }}">
          @if (count($module['items']))
          <a class="m-link" 
            data-bs-toggle="collapse" 
            data-bs-target="#menu-{{ $module['Prefix'] }}" 
            href="#" 
            aria-expanded="{{ in_array($module['Prefix'], $prefixArray) ? 'true' : 'false' }}">
            <img src="{{ asset('uploads/icons/'.$module['Icon']) }}" alt="{{ $module['Title'] }}" width="20" height="20">
            <span class="ms-2">{{ $module['Title'] }}</span>
            <span class="arrow fa fa-angle-right ms-auto text-end"></span>
          </a>
          <ul class="sub-menu collapse {{ in_array($module['Prefix'], $prefixArray) ? 'collapsed show' : '' }}" id="menu-{{ $module['Prefix'] }}">
            @foreach ($module['items'] as $item)
            <li>
              <a class="ms-link {{ $item['Prefix'] == $subModule ? 'active' : '' }}" 
                href="{{ $item['RouteName'] ? route($item['RouteName']) : '#' }}">{{ $item['Title'] }}</a>
            </li>
            @endforeach
          </ul>
          
          @else
          <a class="m-link {{ $module['Prefix'] == $groupPrefix ? 'active' : '' }}" 
            href="{{ $module['RouteName'] ? route($module['RouteName']) : '#' }}">
            <img src="{{ asset('uploads/icons/'.$module['Icon']) }}" alt="{{ $module['Title'] }}" width="20" height="20">
            <span class="ms-2">{{ $module['Title'] }}</span>
          </a>
          @endif
        </li>
        @endforeach

        @if (Auth::user()->IsAdmin)
        <li>
          <a class="m-link {{ $subModule == 'modules' ? 'active' : '' }}" href="{{ route('modules') }}">
            <img src="{{ asset('uploads/icons/modules.png') }}" alt="Modules" width="20" height="20">
            <span class="ms-2">Modules</span>
          </a>
        </li>
        @endif
      </ul>
    </div>
  </div>
</div>


<div class="wrapper">
  <header class="page-header sticky-top px-xl-4 px-sm-2 px-0 py-lg-2 py-1">
    <div class="container-fluid">
      <nav class="navbar">
        <div class="d-flex">
          <button type="button" class="btn btn-link d-none d-xl-block sidebar-mini-btn p-0 text-primary">
            <span class="hamburger-icon">
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
          </button>
          <button type="button" class="btn btn-link d-block d-xl-none menu-toggle p-0 text-primary">
            <span class="hamburger-icon">
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
          </button>
        </div>
        <ul class="header-right justify-content-end d-flex align-items-center mb-0">
          <li>
            <div class="dropdown morphing scale-left user-profile mx-lg-3 mx-2">
              <a class="nav-link dropdown-toggle rounded-circle after-none p-0" href="#" role="button" data-bs-toggle="dropdown">
                <img class="avatar img-thumbnail rounded-circle shadow" src="{{ asset('uploads/profile/' . Auth::user()->Profile ?? 'default.png') }}" alt="">
              </a>
              <div class="dropdown-menu border-0 rounded-4 shadow p-0">
                <div class="card border-0 w240">
                  <div class="card-body border-bottom d-flex">
                    <img class="avatar rounded-circle" src="{{ asset('uploads/profile/' . Auth::user()->Profile ?? 'default.png') }}" alt="">
                    <div class="flex-fill ms-3">
                      <h6 class="card-title mb-0">{{ Auth::user()->FirstName.' '. Auth::user()->LastName }}</h6>
                      <small class="text-muted">{{ Auth::user()->Title }}</small>
                    </div>
                  </div>
                  <div class="list-group m-2 mb-3">
                    <a class="list-group-item list-group-item-action border-0" href="{{ route('user.viewProfile') }}"><i class="w30 fa fa-user"></i>My Profile</a>
                  </div>
                  <a href="{{ route('auth.logout') }}" class="btn bg-secondary text-light text-uppercase rounded-0">Sign out</a>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </nav>
    </div>
  </header>
  