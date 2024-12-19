<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomQuizInstallButtonRequest extends FormRequest
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
            'next_question_text'=>'nullable|string|max:14',
            'next_question_text_uz'=>'nullable|string|max:14',
            'next_to_form'=>'nullable|string|max:13',
            'next_to_form_uz'=>'nullable|string|max:13',
        ];
    }
}
