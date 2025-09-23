<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <div class="w-100 d-flex flex-row justify-content-center align-items-center">
            <img src="{{ url('statics/images/system/p_logo.png') }}" style="width: 220px;height: 50px;">
        </div>
        <a href="javascript:void(0);" class="layout-cus-menu-toggle menu-link text-large ms-auto" id="lg_back">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <hr class="p-0 m-0 mb-2">
    <ul class="menu-inner py-1 ">
        <li class="menu-item">
            <a href="{{ url('admin/dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons fas fa-warehouse"></i>
                <div> Dashboard</div>
            </a>
        </li>
    </ul>
</aside>
