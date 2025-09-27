 <header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{url('/')}}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{config('i.logo')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{config('i.logo')}}" alt="" height="20">
                    </span>
                </a>

                <a href="{{url('/')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{config('i.logo')}}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{config('i.logo')}}" alt="" height="20">
                    </span>
                </a>
            </div>
            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>
        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ getRowImage(Auth::user(),'80X80') }}" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium font-size-15">{{Auth::user()->name}}</span>
                    <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{url('admin/logout')}}"><i class="uil uil-sign-out-alt font-size-18 align-middle me-1 text-muted"></i> <span class="align-middle">Sign out</span></a>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="uil-cog"></i>
                </button>
            </div>
        </div>
    </div>
</header>
