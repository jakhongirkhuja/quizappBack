<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomQuizMetasRequest extends FormRequest
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
            'meta_image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_favicon'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'=>'required',
            'meta_title'=>'required',
            'meta_description'=>'required',
        ];
    }
}
