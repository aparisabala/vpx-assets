<?php

namespace App\Http\Requests\Admin\Systemsettings\User;

use App\Traits\BaseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class ValidateUpdateSystemUser extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function message(): array
    {
        return [
            'name.required' => Lang::get('common.errors.required'),
        ];
    }
    public function rules($request,$row): array
    {
        $rules = [
            "name" => "required|string|max:253",
            "mobile_number" => "required|string|max:253",
            "email" => "required|string|max:253|email",
        ];
        if ($row->isDirty('mobile_number')) {
            $rules['mobile_number'] = 'unique:globusers,mobile_number';
        }
        if ($row->isDirty('email')) {
            $rules['email'] = 'unique:globusers,email';
        }
        return $rules;
    }
}
