<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomCreatePostQuestionRequest extends FormRequest
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
            
            'front_id'=>'required',
            'type'=>'required|max:100',
            'scroll'=>'required',
            'required'=>'required',
            'question'=>'required|min:0|max:255',
            'proportion'=>'required|numeric|min:0|max:4',
            'order'=>'required|min:1',
            'multiple_answers'=>'required',
            'long_text'=>'required',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hidden'=>'required',
            'self_input'=>'required',
            'expanded_footer'=>'required',
            'expanded'=>'required',
        ];
    }
}
