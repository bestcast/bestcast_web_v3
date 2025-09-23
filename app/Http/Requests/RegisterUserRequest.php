<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class RegisterUserRequest extends FormRequest
{
    
    // protected function failedValidation(Validator $validator)
    // {
    //     $response = [
    //         'status' => 'error',
    //         'message' => '',
    //         'errors' => $validator->errors(),
    //     ];

    //     throw new HttpResponseException(response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    // }

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:5|regex:/^(?!test)(?!demo)(?!use)[\pL\s]+$/u',
            'phone' => 'digits_between:10,10|unique:users,phone',
            //'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required',  Rules\Password::defaults()], //'confirmed',
        ];
    }
}