<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCapPhepDiemRequest extends FormRequest
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
            'cap_phep_id' => 'required|exists:cap_phep,id',
            'tu_km' => 'nullable',
            'den_km' => 'nullable',
            'ten' => 'nullable',
            'tu_ngay' => 'nullable|date',
            'den_ngay' => 'nullable|date',
            'noi_dung' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'cap_phep_id.required' => 'cấp phép sửa không được để trống',
            'cap_phep_id.exists' => 'cấp phép không tồn tại',
        ];
    }
}
