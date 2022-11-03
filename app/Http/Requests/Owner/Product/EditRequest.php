<?php

namespace App\Http\Requests\Owner\Product;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'explain' => 'required',
            'images.*' => 'required',
            'image_name.*' => 'required',
            'pre_image_name.*' => 'nullable',
            'tag_name' => 'nullable|bail|string'
        ];
    }
}
