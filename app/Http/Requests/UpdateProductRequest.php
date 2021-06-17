<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_name'  =>   [
                'required',
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    $query->where('section_id', $this->input('section_id'));
                })->ignore($this->input('id'), 'id'),
            ],
            'product_id' => 'required|exists:products,id',
            'description' => 'nullable',
        ];
    }


    public function messages()

    {
        return [
            'product_name.required' => ' اسم المنتج مطلوب',
            'product_name.unique' => 'اسم المنتج موجود مسبقا',
        ];
    }
}
