<!DOCTYPE html>
<html lang="en" class="dark-style layout-menu-fixed" dir="ltr">

<head>
    @include('admin.includes.header-resource', ['tabTitle' => $tabTitle ?? 'Site Title'])
</head>

<body>
    <!-- Modal -->
    <div class="modal fade editmodal edimodalGlob" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-18 font-bold" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('admin.includes.main-nav')
            <div class="layout-page">
                @include('admin.includes.header')
                @yield('page_menu')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="page">
                            <div class="x_panel tile">
                                <div class="x_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @yield('page')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('admin.includes.footer')
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('admin.includes.footer-resource', ['react' => $react ?? []])
</body>
</html>
