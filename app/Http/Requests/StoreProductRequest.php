<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:products,name',
            'section_id' => 'required|exists:sections,section_id',
            'description' => 'nullable',
        ];
    }


    public function messages()

    {
        return [
            'name.required' => ' اسم المنتج مطلوب',
            'name.unique' => 'اسم المنتج موجود مسبقا',
            'section_id.required' => ' اسم القسم مطلوب',
            'section_id.exists' => 'هذا القسم غير موجود',
        ];
    }
}
