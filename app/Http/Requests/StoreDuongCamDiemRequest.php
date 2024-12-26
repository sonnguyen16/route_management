<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDuongCamDiemRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable',
            'duong_cam_id' => 'required|integer',
            'noi_dung' => 'nullable',
            'den_km' => 'nullable', //'required|string',
            'tu_km' => 'nullable',
            'tu_ngay' => 'nullable', //'required|date',
            'den_ngay' => 'nullable', // 'required|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'duong_cam_id.required' => 'Tuyến đường không được để trống',
            'duong_cam_id.integer' => 'Tuyến đường phải là số'
        ];
    }
}
