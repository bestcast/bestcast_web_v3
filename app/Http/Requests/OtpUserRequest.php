<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\EmailOrPhone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class OtpUserRequest extends FormRequest
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
            'email' => ['required',new EmailOrPhone]
        ];
    }
    
    public function messages()
    {
        return [
            'email' => 'Please enter a valid email address or mobile number.'
        ];
    }
}