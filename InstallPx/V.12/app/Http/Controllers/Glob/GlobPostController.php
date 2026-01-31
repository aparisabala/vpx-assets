<?php

namespace App\Http\Controllers\Glob;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;

class GlobPostController extends Controller
{
    use BaseTrait;
    public function __construct() {}
    public function postUploadSumernote(Request $request)
    {

        $this->creatDir(imagePaths()['summernote']);
        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                if ($_FILES['file']['size'] > 300000) {
                    echo "size";
                } else {
                    $type = $_FILES['file']['type'];
                    if (($type != 'image/jpeg') && ($type != 'image/JPEG') && ($type != 'image/png') && ($type != 'image/PNG') && ($type != 'image/gif')) {
                        echo "type";
                    } else {
                        $name     = md5(rand(100000, 200000));
                        $ext      = explode('.', $_FILES['file']['name']);
                        $filename = $name . '.' . $ext[1];
                        $path     = imagePaths()['summernote'];
                        $this->creatDir(imagePaths()['summernote']);
                        if (!is_dir($path)) {
                            mkdir($path);
                        }
                        $destination = $path . $filename;
                        $location    = $_FILES["file"]["tmp_name"];
                        move_uploaded_file($location, $destination);
                        echo $filename;
                    }
                }
            } else {
                echo 'error';
            }
        }
    }

    public function postDeletesummernote(Request $request)
    {
        $url = imagePaths()['summernote'] . $request->img;
        if (file($url)) {
            unlink($url);
        } else {
            echo "error";
        }
    }
}
