<?php

function uploadsDir()
{
    return [
        'uploads',
        'uploads/' . config('i.service_domain'),
        'uploads/' . config('i.service_domain') . '/summernote',
    ];
}

function imagePaths()
{
    return [
        'summernote' => 'uploads/app/' . config('i.service_domain') . '/summernote/',
    ];
}
