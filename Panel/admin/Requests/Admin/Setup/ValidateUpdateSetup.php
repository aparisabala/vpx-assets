<?php

namespace App\Http\Requests\Admin\Setup;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class ValidateUpdateSetup extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function message() : array
    {
        return [
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules($request,$user): array
    {
        $rules = [
            "name" => "required|string|max:253",
            "mobile_number" => "required|string|max:253",
            "email" => "required|string|max:253|email",
            "new_password" => "required|min:8",
            "confim_password" => "required|min:8|same:new_password"
        ];
        if ($user->isDirty('mobile_number')) {
            $rules['mobile_number'] = 'unique:globusers,mobile_number';
        }
        if ($user->isDirty('email')) {
            $rules['email'] = 'unique:globusers,email';
        }
        if($request->img_uploaded == "no") {
            $rules['user_image'] = "required";
        }
        return $rules;

    }
}
