<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCauRequest extends FormRequest
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
            'ly_trinh' => 'nullable',
            'chieu_dai' => 'nullable',
            'chieu_rong' => 'nullable',
            'loai_ket_cau_nhip_id' => 'nullable',
            'nam_khai_thac' => 'nullable',
            'tai_trong' => 'nullable',
            'trong_tai_khai_thac' => 'nullable',
            'kiem_dinh' => 'nullable',
            'tuyen_duong_id' => 'nullable',
            'loai_cau_id' => 'nullable',
            'vi_tri' => 'nullable',
            'lat' => 'nullable',
            'lng' => 'nullable',
        ];
    }
    public function messages(): array
    {
        return [
            'ten.required' => 'Tên không được để trống',
           /*  'ly_trinh.required' => 'Lý trình không được để trống',
            'loai_ket_cau_nhip_id.required' => 'Loại kết cấu không để trống loại',
            'nam_khai_thac.required' => 'Năm khai thác không để trống loại',
            'tai_trong.required' => 'Tải trọng không để trống loại',
            'kiem_dinh.required' => 'Kiểm định không để trống loại',
            'nam_khai_thac.required' => 'Năm khai thác không để trống loại',
            'lat.required' => 'Tọa độ không để trống loại',
            'lng.required' => 'Tọa độ hoạt động không để trống loại', */
        ];
    }
}
