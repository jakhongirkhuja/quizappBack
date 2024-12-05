<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizzRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'domainType' => 'required|boolean',
            'publish' => 'required|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'next_question_text' => 'nullable|string',
            'next_to_form' => 'nullable|string',
        ];
    }
}
