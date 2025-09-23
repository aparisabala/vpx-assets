<?php

namespace App\Http\Requests\Admin\Login;

use App\Traits\BaseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class ValidateAdminLogin extends FormRequest
{
    use BaseTrait;
    public function __construct()
    {
        $this->LoadModels(['Globuser']);
        $this->u = "";
        $this->attempt_to = "";
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            "mobile_number" => [
                'required',
                function ($attr, $value, $fail) use ($request) {
                    if (filter_var($request->mobile_number, FILTER_VALIDATE_EMAIL)) {
                        $this->u =  $this->Globuser->getQuery(['type' => 'first','query' => ['where' => [ [['email', '=', $request->mobile_number]]]]]);
                        $this->attempt_to = 'email';
                        if (empty($this->u)) {
                            $fail('No user found');
                        }
                    } else {
                        if (is_numeric($request->mobile_number)) {
                            if (preg_match("/^01[13-9]\d{8}$/", $request->mobile_number)) {
                                $this->u =  $this->Globuser->getQuery(['type' => 'first','query' => ['where' => [[['mobile_number', '=', $request->mobile_number]]]]]);
                                $this->attempt_to = 'mobile_number';
                                if (empty($this->u)) {
                                    $fail('No user found');
                                }
                            } else {
                                $fail('Number not valid');
                            }
                        } else {
                            $fail('Must be a valid email or mobile number');
                        }
                    }
                },
            ],
            "password" => 'required|min:8',
        ];
    }
    
    public function messages()
    {
        return [
            'password.required' => Lang::get('common.errors.required'),
            'password.min' => Lang::get('common.errors.min',["digits"=> 8])
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

    protected function passedValidation(): void
    {
        $this->merge([
            'u' => $this->u,
            'attempt_to' => $this->attempt_to
        ]);
    }
}
