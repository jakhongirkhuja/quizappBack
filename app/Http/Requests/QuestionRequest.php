<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'quizz_id' => 'required|exists:quizzs,id',
            'type' => 'required|string',
            'question' => 'required|string',
            'expanded' => 'boolean',
            'hidden' => 'boolean',
            'order' => 'integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
            'expanded_footer' => 'boolean',
            'multiple_answers' => 'boolean',
            'required' => 'boolean',
            'long_text' => 'boolean',
            'proportion' => 'integer|in:1,2,3',
            'scroll' => 'boolean',
        ];
    }
}
