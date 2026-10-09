<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\PxCommandServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    //vpx_app_providers
    Yajra\DataTables\DataTablesServiceProvider::class,
    Rakibhstu\Banglanumber\NumberToBanglaServiceProvider::class,
    Intervention\Image\ImageServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
];
