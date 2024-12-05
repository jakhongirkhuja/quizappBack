<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerRequest extends FormRequest
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
            'question_id' => 'required|exists:questions,id',
            'custom_answer' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Only images
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // Images or PDF
            'text' => 'nullable|string',
            'secondary_text' => 'nullable|string',
            'order' => 'integer|min:1',
            'selected' => 'boolean',
            'time_select' => 'nullable|date',
            'rank' => 'nullable|integer',
            'rank_text_min' => 'nullable|string',
            'rank_text_max' => 'nullable|string',
        ];
    }
}
