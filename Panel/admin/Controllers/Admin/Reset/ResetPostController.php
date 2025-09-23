<?php

namespace App\Http\Controllers\Admin\Reset;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use View;
use Hash;
use Mail;
class ResetPostController extends Controller
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadMiddleware('');
        $this->LoadModels(['Globuser']);
    }

    public function sendcode(Request $request)
    {
        $errors = $this->inlineValidate($request, ['rules' => ['email' => 'required']]);
        if (count($errors) > 0) {
            return $this->response(['type' => 'validation', 'errors' => $errors]);
        }
        $user = $this->Globuser->getQuery(['type' => 'first', 'query' => ['where' => [[['email', '=', $request->email]]]]]);
        if (empty($user)) {
            return $this->response(['type' => 'validation', 'errors' => ['email' => ['User not found with this number, try again']]]);
        }
        $code = mt_rand(0000000, 1111111);
        try {
            $user->reset_code = $code;
            $user->sent_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            Mail::send('admin.pages.reset.mail.sendResetCode', ['code'=>$code], function ($message) use ($request, $user) {
                $message->subject('Password Reset');
                $message->from(config('i.service_email'), config('i.service_name'));
                $message->to([$user->email], "Password Reset");
            });
            $data['extraData'] = [
                "inflate" => "Code sent sucesfully",
            ];
            $data['view'] = View::make('admin.pages.reset.includes.verfy_code', compact('user'))->render();
            return $this->response(['type' => "success", 'data' => $data]);
        } catch (\Exception $e) {
            return $this->response(['type' => 'noUpdate', 'title' => 'Something went wrong, try again ', 'content' => $e->getMessage()]);
        }
    }

    public function verifyCode(Request $request)
    {
        $errors = $this->inlineValidate($request, ['rules' => ['code' => 'required|numeric']]);
        if (count($errors) > 0) {
            return $this->response(['type' => 'validation', 'errors' => $errors]);
        }
        $user = $this->Globuser->getQuery(['type' => 'first', 'query' => ['where' => [[['uuid', '=', $request->user_uuid]]]]]);
        if (empty($user)) {
            return $this->response(['type' => 'validation', 'errors' => ['code' => ['User not found']]]);
        }
        if ($request->code != $user->reset_code) {
            return $this->response(['type' => 'noUpdate', 'title' => 'This is not the code we sent to you, try again']);
        }
        $user->reset_code = null;
        $user->sent_at = null;
        $user->save();
        $data['extraData'] = [
            "inflate" => "Verified successfully",
        ];
        $data['view'] = View::make('admin.pages.reset.includes.set_password', compact('user'))->render();
        return $this->response(['type' => "success", 'data' => $data]);
    }

    public function chnagePass(Request $request)
    {
        $errors = $this->inlineValidate($request, [
            'rules' => [
                'password' => 'required|min:8',
                'confirm_password' => 'required|min:8|same:password'
            ]
        ]);
        if (count($errors) > 0) {
            return $this->response(['type' => 'validation', 'errors' => $errors]);
        }
        $user = $this->Globuser->getQuery(['type' => 'first', 'query' => ['where' => [[['uuid', '=', $request->user_uuid]]]]]);
        if (empty($user)) {
            return $this->response(['type' => 'validation', 'errors' => ['code' => ['User not found']]]);
        }
        $user->password = Hash::make($request->confirm_password);
        $user->save();
        $data['extraData'] = [
            "inflate" => "Passward chnaged successfully, you must login to continue",
            "redirect" => "admin/login?pass_changed=true"
        ];
        return $this->response(['type' => "success", 'data' => $data]);
    }
}
