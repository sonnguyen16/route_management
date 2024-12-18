<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuaChuaDiemRequest extends FormRequest
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
            'sua_chua_id' => 'required|exists:sua_chua,id',
            'tu_km' => '',
            'den_km' => 'nullable',
            'loai_sua_chua_id' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'sua_chua_id.required' => 'hạng mục sửa không được để trống',
            'sua_chua_id.exists' => 'Sửa chữa không tồn tại',
        ];
    }
}
