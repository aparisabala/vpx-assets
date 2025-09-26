<?php

function uploadsDir()
{
    return [
        'uploads',
        'uploads/' . config('i.service_domain'),
        'uploads/' . config('i.service_domain') . '/summernote',
        "uploads/app/" . config('i.service_domain') . "/dyn",
        "uploads/app/" . config('i.service_domain') . "/dyn/images",
    ];
}

function imagePaths()
{
    return [
        'summernote' => 'uploads/app/' . config('i.service_domain') . '/summernote/',
        "dyn_image" => "uploads/app/" . config('i.service_domain') . "/dyn/images/",
    ];
}

function getRowImage($row,$ext='80X80'){
    $path = imagePaths()['dyn_image'].'/'.$row?->image.'_'.$ext.'.'.$row?->extension;
    $img = ($row?->image == "" || !file_exists($path)) ? url('images/system/img.jpg') : url($path);
    return $img;
}
