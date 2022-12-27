<?php 
  use App\Models\admin\Designation;

  $groupPrefix = Request::segment(1);
  $subModule   = Request::segment(2);
  $setupModule = Request::segment(3);
  $prefixArray = [$groupPrefix, $subModule, $setupModule];

  $moduleData = getModuleData();
  $notifications = DB::table('notifications')
    ->where('notifiable_id', '=', Auth::id())
    ->where('read_at', '=', null)
    ->orderBy('created_at', 'DESC')
    ->limit(5)
    ->get();
?>

<div class="sidebar p-2 py-md-3 @@cardClass version-2">
  <div class="container-fluid">
    <div class="title-text d-flex align-items-center mb-4 mt-1">
      <img class="sidebar-img img-thumbnail shadow" src="{{ asset('assets/img/epldt-suite-logo.png') }}" height="50" width="50" alt="ePLDT">
      <h4 class="sidebar-title mb-0 flex-grow-1 px-2 d-none d-xl-block"><span class="sm-txt fw-bold" style="color: #d0021b;">ePLDT</h4>
    </div>

    <div class="main-menu flex-grow-1 pb-3">

      @foreach ($moduleData as $index => $modules)
      <ul class="menu-list">
        <li class="divider py-2 lh-sm">
          <span class="small fw-bold"><?= 'MODULE '.$modules['index'] ?></span><br>
          <small class="text-muted"><?= $modules['module'] ?></small>
        </li>
        @foreach ($modules['items'] as $module)
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
      </ul>
      @endforeach

      @if (Auth::user()->IsAdmin)
      <ul class="menu-list">
        <li class="divider py-2 lh-sm">
          <span class="small fw-bold"><?= 'MODULE '.(count($moduleData)+1) ?></span><br>
          <small class="text-muted">INTEGRATION & ADMIN</small>
        </li>
        <li>
          <a class="m-link {{ $subModule == 'integration' ? 'active' : '' }}" href="{{ '#' }}">
            <img src="{{ asset('uploads/icons/integration.png') }}" alt="Integration" width="20" height="20">
            <span class="ms-2">Integration</span>
          </a>
        </li>
        <li class="{{ in_array('setup', $prefixArray) ? 'collapsed' : '' }}">
          <a class="m-link" 
            data-bs-toggle="collapse" 
            data-bs-target="#menu-setup" 
            href="#" 
            aria-expanded="{{ in_array('setup', $prefixArray) ? 'true' : 'false' }}">
            <img src="{{ asset('uploads/icons/setup.png') }}" alt="Setup" width="20" height="20">
            <span class="ms-2">Setup</span>
            <span class="arrow fa fa-angle-right ms-auto text-end"></span>
          </a>
          <ul class="sub-menu collapse {{ in_array('setup', $prefixArray) ? 'collapsed show' : '' }}" id="menu-setup">
            <li>
              <a class="ms-link {{ 'department' == $setupModule ? 'active' : '' }}" 
                href="{{ route('department') }}">Department</a>
            </li>
            <li>
              <a class="ms-link {{ 'designation' == $setupModule ? 'active' : '' }}" 
                href="{{ route('designation') }}">Designation</a>
            </li>
            <li>
              <a class="ms-link {{ 'leaveType' == $setupModule ? 'active' : '' }}" 
                href="{{ route('leaveType') }}">Leave Type</a>
            </li>
            <li>
              <a class="ms-link {{ 'permission' == $setupModule ? 'active' : '' }}" 
                href="{{ route('permission') }}">Permission</a>
            </li>
            <li>
              <a class="ms-link {{ 'moduleApproval' == $setupModule ? 'active' : '' }}" 
                href="{{ route('moduleApproval') }}">Approval</a>
            </li>
            <li>
              <a class="ms-link {{ 'complexity' == $setupModule ? 'active' : '' }}" 
                href="{{ route('complexity') }}">Complexity</a>
            </li>
            <li>
              <a class="ms-link {{ 'projectPhase' == $setupModule ? 'active' : '' }}" 
                href="{{ route('projectPhase') }}">Project Phases</a>
            </li>
          </ul>
        </li>
        <a class="m-link {{ $subModule == 'dataManagement' ? 'active' : '' }}" href="{{ route('dataManagement') }}">
          <img src="{{ asset('uploads/icons/dataManagement.png') }}" alt="Data Management" width="20" height="20">
          <span class="ms-2">Data Management</span>
        </a>
        {{-- 
        <!-- FOR DEVELOPER ONLY -->  
        <li>
          <a class="m-link {{ $subModule == 'module' ? 'active' : '' }}" href="{{ route('module') }}">
            <img src="{{ asset('uploads/icons/modules.png') }}" alt="Modules" width="20" height="20">
            <span class="ms-2">Modules</span>
          </a>
        </li> --}}
      </ul>
      @endif

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
            <div class="dropdown morphing scale-left notifications">
                <a class="nav-link dropdown-toggle after-none" href="#" role="button"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    @if (count($notifications))
                    <span class='badge bg-warning'
                        id='lblCartCount'
                        style="position: absolute; right: 0; top: 0;">
                        {{ count($notifications) }}
                    </span>
                    @endif

                </a>
                <div id="NotificationsDiv" class="dropdown-menu shadow rounded-4 border-0 p-0 m-0">

                    <div class="card w380">
                        <div class="card-header p-3">
                            <h6 class="card-title mb-0">Notifications</h6>
                        </div>
                        <div class="tab-content card-body custom_scroll px-3 py-2">
                            <div class="tab-pane fade show active">
                                @if (count($notifications) > 0)
                                    <ul class="list-unstyled list mb-0">
                                        @foreach ($notifications as $notification)
                                            <?php $data = json_decode($notification->data); ?>
                                            <li class="py-2 mb-1 border-bottom">
                                                <div class="d-flex btnViewNotification"
                                                    style="cursor: pointer;"
                                                    onclick="viewNotification('{{ $notification->id }}', '{{ $data->Link ?? '#' }}')">
                                                    <img src="{{ $data->Icon ?? '/assets/img/icons/default.png' }}" height="30" width="30">
                                                    <div class="flex-fill ms-3">
                                                        <div class="mb-0">
                                                          <?= $data->Description ?>
                                                        </div>
                                                        <small>{{ activityTime($notification->created_at) }}</small>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach

                                    </ul>
                                @else
                                    <h6 class="color-400 text-center">No notification</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </li>
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
                      <small class="text-muted">{{ Designation::find(Auth::user()->DesignationId)->Name ?? '-' }}</small>
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