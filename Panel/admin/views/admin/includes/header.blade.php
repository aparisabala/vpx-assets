@php
    $path = 'uploads/app/'.config('i.service_domain').'/user/'.Auth::user()->user_image;
    $link = url("statics/images/system/user.png");
    if(Auth::user()->user_image != null && file_exists($path)){
        $link = url($path);
    } 
@endphp
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
    <div class="layout-xl-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0" id="lg_open">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <h1 class="m-0 menu-link {{ (lang() == "bn") ? 'fs-28' : 'fs-20'}}"> ADMIN PANEL  </h1>
            </div>
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="text-white me-3">
                Version 10.0
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ $link }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-start" data-bs-popper="none">
                    <li>
                        <a class="dropdown-item" href="{{ url('control/dashboard/profile') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ $link }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->user_name }}</span>
                                    <small class="text-muted">{{ getRole(Auth::user()->role) }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('admin/dashboard/profile') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle"> My Profile  </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('admin/dashboard/password') }}">
                            <i class="bx bx-save me-2"></i>
                            <span class="align-middle"> Change Passsword </span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('admin/logout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle"> Logout </span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
