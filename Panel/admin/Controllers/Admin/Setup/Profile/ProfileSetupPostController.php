<?php

namespace App\Http\Controllers\Admin\Setup\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setup\ValidateUpdateSetup;
use App\Traits\BaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use File;
use Webpatser\Uuid\Uuid;
use Image;
use Lang;

class ProfileSetupPostController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('');
        $this->LoadModels(['Globuser']);
    }

    public function updateSetupProfile(Request $request)
    {
        $user = $this->checkExists($request, "Globuser|uuid|uuid", ['*']);
        if (empty($user)) {
            return $this->response(['type' => 'noUpdate', 'title' => 'User not found, try again']);
        }
        $validator = Validator::make($request->all(), (new ValidateUpdateSetup())->rules($request, $user));
        if ($validator->fails()) {
            return $this->response(['type' => 'validation', 'errors' => $validator->errors()]);
        }
        try {
            $user->name = $request->name;
            $user->mobile_number = $request->mobile_number;
            $user->email = $request->email;
            $path = 'uploads/app/' . config('i.service_domain') . '/user/';
            $this->creatDir($path);
            $image = $request->file('user_image');
            if ($request->img_uploaded == "no" || !empty($image)) {
                $delPath = $path . $user->user_image;
                if (File::exists($delPath)) {
                    File::delete($delPath);
                }
                if (!empty($image)) {
                    $image_link = (string) Uuid::generate(4);
                    $extension = $image->getClientOriginalExtension();
                    $img = Image::make($image)->encode('jpg', 10);
                    $img->resize(600, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($path . $image_link . '.' . $extension);
                    $user->user_image = $image_link. '.' . $extension;
                }
            }
            $user->password = Hash::make($request->confim_password);
            $user->setup_done = "yes";
            $user->save();
            $data['extraData'] = [
                "redirect" => 'admin/dashboard',
                "inflate" => 'Updated succesfully'
            ];
            return $this->response(['type' => 'success', "data" => $data]);
        } catch (\Exception $e) {
            $this->saveError($this->getSystemError(['name' => 'update_agent_error']), $e);
            return $this->response(["type" => "wrong", "lang" => "server_wrong"]);
        }
    }
}
