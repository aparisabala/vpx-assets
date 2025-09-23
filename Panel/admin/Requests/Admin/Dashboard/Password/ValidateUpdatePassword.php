<?php

namespace App\Http\Requests\Admin\Dashboard\Password;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Hash;
class ValidateUpdatePassword extends FormRequest
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
    public function rules($user): array
    {
        return [
            "old_password" => [
                "required",
                "min:8",
                function($attr,$value,$fail) use($user){
                    if (!Hash::check($value, $user->password)) {
                        $fail('Incorrect old password, try again');
                    }
                }
            ],
            "password" => "required|min:8",
            "confirm_password" => "required|min:8|same:password",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'errors'  => $validator->errors(),
        ]);
        throw (new ValidationException($validator, $response))->errorBag($this->errorBag);
    }
}
