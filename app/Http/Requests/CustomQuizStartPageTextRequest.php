<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomQuizStartPageTextRequest extends FormRequest
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
            'slogan_text'=>'nullable|max:220',
            'slogan_text_uz'=>'nullable|max:220',
            'title'=>'nullable|max:220',
            'title_uz'=>'nullable|max:220',
            'title_secondary'=>'nullable|max:220',
            'title_secondary_uz'=>'nullable|max:220',
            'button_text'=>'nullable|max:220',
            'button_text_uz'=>'nullable|max:220',
            'phoneNumber'=>'nullable|max:220',
            'phoneNumber_uz'=>'nullable|max:220',
            'companyName_text'=>'nullable|max:220',
            'companyName_text_uz'=>'nullable|max:220',
            'design_type'=>'nullable|min:0',
            'design_alignment'=>'nullable|min:0',
        ];
    }
}
