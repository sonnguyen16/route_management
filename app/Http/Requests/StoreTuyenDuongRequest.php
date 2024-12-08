<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTuyenDuongRequest extends FormRequest
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
            'loai_tuyen_duong_id' => 'nullable|Int',
            'phan_cap_id' => 'nullable|Int',
            'diem_dau_huyen_id' => 'nullable|Int',
            'diem_dau_xa_id' => 'nullable|Int',
            'diem_dau_lat' => 'nullable',
            'diem_dau_lng' => 'nullable',
            'diem_cuoi_huyen_id' => 'nullable|Int',
            'diem_cuoi_xa_id' => 'nullable|Int',
            'diem_cuoi_lat' => 'nullable',
            'diem_cuoi_lng' => 'nullable',
            'chieu_dai' => 'nullable',
            'chieu_rong' => 'nullable',
            'don_vi_id' => 'nullable|Int',
            'lo_gioi' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên không được để trống',
            'loai_tuyen_duong_id.required' => 'Loại không được để trống', /*
            'phan_cap_id.required' => 'Mã phân cấp không được để trống',
            'diem_dau_huyen_id.required' => 'Điểm đầu huyện không được để trống',
            'diem_cuoi_huyen_id.required' => 'Điểm cuối huyện không được để trống',
            'diem_dau_xa_id.required' => 'Điểm đầu xã không được để trống',
            'diem_cuoi_xa_id.required' => 'Điểm cuối xã không được để trống',
            'diem_dau_lat.required' => 'Điểm đầu lat không được để trống',
            'diem_dau_lng.required' => 'Điểm đầu lng không được để trống',
            'diem_cuoi_lat.required' => 'Điểm cuối lat không được để trống',
            'diem_cuoi_lng.required' => 'Điểm cuối lng không được để trống',
            'chieu_dai.required' => 'Chiều dài không được để trống',
            'chieu_rong.required' => 'Chiều rộng không được để trống',
            'don_vi_id.required' => 'Đơn vị quản lý không được để trống',
            'lo_gioi.required' => 'Lộ giới không được để trống', */
        ];
    }
}
