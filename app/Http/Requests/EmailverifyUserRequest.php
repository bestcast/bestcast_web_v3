<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class EmailverifyUserRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:255', 'unique:users']
        ];
    }
    public function messages()
    {
        return [
            'phone.unique' => 'exists',
            'phone.required' => 'Please enter a valid phone.',
            'phone.max' => 'Please enter a valid email.'
        ];
    }
    // public function rules()
    // {
    //     return [
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
    //     ];
    // }
    // public function messages()
    // {
    //     return [
    //         'email.unique' => 'exists',
    //         'email.required' => 'Please enter a valid email.',
    //         'email.email' => 'Please enter a valid email.',
    //         'email.max' => 'Please enter a valid email.'
    //     ];
    // }
}