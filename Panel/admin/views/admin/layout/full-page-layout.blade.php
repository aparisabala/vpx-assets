@php
$r = \Route::getFacadeRoot()->current()->uri();
$c = "nav-md";
@endphp
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr">
<head>
    @include('admin.includes.headerResource',['tabTitle' => $tabTitle ?? "Site Title"])
    <style>
        body {
            background: lightblue url("{{ url('statics/images/system/login_bg.jpeg') }}") no-repeat left top 100%/100%;
        }
        .login_base {
            background-color: rgba(0,0,0,.1);
            border: 1px solid orange;
        }
        @media (max-width: 575.98px) {
            body {
                background: lightblue url("{{ url('statics/images/system/login_bg.jpeg') }}") no-repeat fixed left top;
            }
            .login_base {
                background-color: rgba(0,0,0,.6);
            }
        }
    </style>
</head>
<body>
    <div class="container-xxl">
        @yield('page')
    </div>
    @include('admin.includes.footerResource',["react"=>$react ?? []])
</body>
</html>