<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
            'name'  =>   [
                'required',
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    $query->where('section_id', $this->input('section_id'));
                }),
            ],

            'section_id' => 'required|exists:sections,id',

            'description' => 'nullable',
        ];
    }



    public function messages()

    {
        return [
            'name.required' => ' اسم المنتج مطلوب',
            'name.unique' => ' اسم المنتج موجود  مسبقا لنفس القسم ',
            'section_id.required' => ' اسم القسم مطلوب',
            'section_id.exists' => 'هذا القسم غير موجود',
        ];
    }
}
