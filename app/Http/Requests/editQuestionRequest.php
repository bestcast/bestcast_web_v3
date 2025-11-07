<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_id' => 'required',
            'question_name' => 'required',
            'description' => 'nullable|string',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string|max:255',
            'correct_option' => 'required|in:0,1,2,3',
            'checkBoolean' => ''
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'question_name.required' => 'Please enter question',
            'options.required' => 'Please add options, to click add button',
            'options.*.required' => 'Please enter the options',
            'correct_option.required' => 'Please enter option',
        ];
    }
}
