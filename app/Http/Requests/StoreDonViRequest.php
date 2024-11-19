<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonViRequest extends FormRequest
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
            'ten' => ['required', 'string', 'max:255'],
            'ma_so_thue' => 'nullable|string',
            'dien_thoai' => 'nullable|string',
            'fax' => 'nullable|string',
            'dia_chi' => 'nullable|string',
            'website' => 'nullable|string',
            'lien_he' => 'nullable|string',
            'email' => 'nullable|string',
            'loai' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên đơn vị không được để trống',
            'ten.string' => 'Tên đơn vị phải là chuỗi',
            'ten.max' => 'Tên đơn vị không được vượt quá 255 ký tự',
            'loai.required' => 'Loại đơn vị không được để trống',
            'loai.integer' => 'Loại đơn vị phải là số nguyên',
        ];
    }
}
