<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDenGiaoThongRequest extends FormRequest
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
            'nut_giao' => 'required|string|max:255',
            'so_pha_den' => 'required|string|max:255',
            'thoi_gian_pha_den' => 'required|string|max:255',
            'thoi_gian_hoat_dong' => 'required|string|max:255',
            'lat' => 'required|string|max:255',
            'lng' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'nut_giao.required' => 'Nút giao không được để trống',            
            'so_pha_den.required' => 'Số pha đèn không được để trống',
            'thoi_gian_pha_den.required' => 'Thời gian pha đèn không để trống loại',
            'thoi_gian_hoat_dong.required' => 'Thời gian hoạt động không để trống loại',
            'lat.required' => 'Tọa độ không để trống loại',
            'lng.required' => 'Tọa độ hoạt động không để trống loại',
        ];
    }
}
