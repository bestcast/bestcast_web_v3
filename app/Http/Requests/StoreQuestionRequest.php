<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
            'question_name' => 'required',
            'description' => 'nullable|string',
            'options' => 'required|array|min:2|max:4',
            'options.*' => 'required|string|max:255',
            'correct_option' => 'required|in:0,1,2,3',
            'checkBoolean' => '',
            'show_time_hour' => 'required|integer|min:0',
            'show_time_min' => 'required|integer|min:0',
            'show_time_sec' => 'required|integer|min:0|max:59',
            'language' => 'required|in:english,tamil'
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
            'show_time_min.required' => 'Please enter quiz time in minutes',
            'show_time_sec.required' => 'Please enter quiz time in seconds',
            'language.required' => 'Please choose a language'
        ];
    }
}
