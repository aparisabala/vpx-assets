<?php

namespace App\Traits\PxTraits\Dependancy;

use Image;
use File;

trait MultipleImageVersioning
{

    public function imageVersioning($data = [])
    {
        $image = $data['image'];
        $path = $data['path'];
        $image_link = $data['image_link'];
        $extension = $data['extension'];
        $sizes = $data['sizes'] ?? [];
        $onlyAppend = $data['onlyAppend'] ??  [];
        $appendSize = $data['appendSize'] ??  false;
        $imageSizes = $this->getImageVersionSize();
        if($appendSize) {
            $imageSizes = [
                ...$imageSizes,
                ...$sizes,
            ];
        }
        if(count($onlyAppend) > 0) {
            $imageSizes = $onlyAppend;
        }
        foreach ($imageSizes as $key => $value) {
            $this->imageVersioningWithSize($image, $path, $value['width'], $value['height'], $image_link, $extension, (isset($value['com']) ? $value['com'] : .9));
        }
    }

    public function imageVersioningWithSize($image, $path, $width, $height, $image_link, $extension,$com)
    {
        $img = Image::make($image)->encode($extension,$com);
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image_url_name = $image_link . '_' . $width . 'X' . $height . '.' . $extension;
        $img->save($path . $image_url_name);
    }

    public function deleteImageVersions($data = [])
    {
        $path = $data['path'];
        $image_link = $data['image_link'];
        $extension = $data['extension'];
        $sizes = $data['sizes'] ?? [];
        $sizes = [...$this->getImageVersionSize(),...$sizes];
        foreach ($sizes as $size) {
            $imageFile = $path . $image_link . '_' . $size['width'] . 'X' . $size['height'] . '.' . $extension;
            if (file_exists($imageFile)) {
                File::delete($imageFile);
            }
        }
    }

    private function getImageVersionSize()
    {
        $sizes = [
            ["width" => 16, "height" => 16],
            ["width" => 32, "height" => 32],
            ["width" => 100, "height" => 100],
            ["width" => 150, "height" => 150],
            ["width" => 250, "height" => 100],
            ["width" => 250, "height" => 250,"com" => .7],
            ["width" => 1200, "height" => 630,"com" => .5],
            ["width" => 1280, "height" => 720,"com" => .4],
            ["width" => 1600, "height" => 500,"com" => .3],
        ];
        return $sizes;
    }
}
