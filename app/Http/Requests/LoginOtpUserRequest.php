<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailOrPhone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class LoginOtpUserRequest extends FormRequest
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
            'email' => ['required',new EmailOrPhone],
            'otp' => ['required', 'numeric', 'min:1000', 'max:9999']
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => 'OTP number is required.',
            'otp.numeric' => 'OTP field must be a number.',
            'otp.min' => 'Please enter a valid OTP!',
            'otp.max' => 'Please enter a valid OTP.',
        ];
    }
}