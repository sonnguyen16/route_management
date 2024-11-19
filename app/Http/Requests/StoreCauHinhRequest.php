<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCauHinhRequest extends FormRequest
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
            'id' => 'nullable',
            'ten' => 'required|string|max:255',
            'loai' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên không được để trống',            
            'ma_so_thue.required' => 'Mã số thuế không được để trống',
            'loai.required' => 'Không để trống loại',
        ];
    }
}
