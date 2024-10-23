<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCapPhepRequest extends FormRequest
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
            'tuyen_duong_id' => 'required|exists:tuyen_duong,id',
            'don_vi_id' => 'required|exists:don_vi,id',
            'so_cap_phep' => 'required|string',
            'ngay_cap_phep' => 'required|date',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'tuyen_duong_id.required' => 'Tuyến đường không được để trống',
            'tuyen_duong_id.exists' => 'Tuyến đường không tồn tại',
            'don_vi_id.required' => 'Đơn vị không được để trống',
            'don_vi_id.exists' => 'Đơn vị không tồn tại',
            'so_cap_phep.required' => 'Số cấp phép không được để trống',
            'so_cap_phep.string' => 'Số cấp phép phải là chuỗi',
            'ngay_cap_phep.required' => 'Ngày cấp phép không được để trống',
            'ngay_cap_phep.date' => 'Ngày cấp phép phải là ngày',
        ];
    }
}
