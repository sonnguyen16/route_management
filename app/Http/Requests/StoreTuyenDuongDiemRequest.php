<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTuyenDuongDiemRequest extends FormRequest
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
            'tuyen_duong_id' => 'nullable|Int',
            'noi_dung' => 'nullable',
            'tu_km' => 'nullable',
            'den_km' => 'nullable',
            'chieu_rong' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên không được để trống',
            'tuyen_duong_id.required' => 'Loại không được để trống',
        ];
    }
}
