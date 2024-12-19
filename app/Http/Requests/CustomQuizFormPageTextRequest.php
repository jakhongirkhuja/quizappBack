<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomQuizFormPageTextRequest extends FormRequest
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
            'title'=>'nullable|max:220',
            'title_uz'=>'nullable|max:220',
            'title_secondary'=>'nullable|max:220',
            'title_secondary_uz'=>'nullable|max:220',
            'button_text'=>'nullable|max:220',
            'button_text_uz'=>'nullable|max:220',
            'phone'=>'nullable|min:0',
            'email'=>'nullable|min:0',
            'name'=>'nullable|min:0',
            'phone_required'=>'nullable|min:0',
            'email_required'=>'nullable|min:0',
            'name_required'=>'nullable|min:0',
        ];
    }
}
