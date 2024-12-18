<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomCreatePostAnswerRequest extends FormRequest
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
            'question_id'=>'required',
            'custom_answer'=>'required',
            'front_id'=>'required',
            'order'=>'required|min:1',
            'selected'=>'required',
            'text'=>'required|min:0|max:255',
            'secondary_text'=>'nullable|min:0|max:255',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rank'=>'nullable',
            'rank_text_max'=>'nullable|max:200',
            'rank_text_min'=>'nullable|max:200',
            'time_select'=>'nullable',
            'type_type'=>'nullable',
        ];
    }
}
