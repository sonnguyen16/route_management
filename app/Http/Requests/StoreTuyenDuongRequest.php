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
            'loai' => 'required',
            'ma_phan_cap' => 'required',
            'diem_dau_huyen_id' => 'required',
            'diem_dau_xa_id' => 'required',
            'diem_cuoi_huyen_id' => 'required',
            'diem_cuoi_xa_id' => 'required',
            'chieu_dai' => 'required',
            'chieu_rong' => 'required',
            'dien_tich' => 'required',
            'loai_tuan_tra' => 'required',
            'don_vi_quan_ly' => 'required|string|max:255',
            'xi_nghiep' => 'required|string|max:255',
            'huyen_id' => 'required',
            'tai_lieu' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'ten.required' => 'Tên không được để trống',
            'loai.required' => 'Loại không được để trống',
            'ma_phan_cap.required' => 'Mã phân cấp không được để trống',
            'diem_dau_huyen_id.required' => 'Điểm đầu huyện không được để trống',
            'diem_cuoi_huyen_id.required' => 'Điểm cuối huyện không được để trống',
            'chieu_dai.required' => 'Chiều dài không được để trống',
            'chieu_rong.required' => 'Chiều rộng không được để trống',
            'dien_tich.required' => 'Diện tích không được để trống',
            'loai_tuan_tra.required' => 'Loại tuần tra không được để trống',
            'don_vi_quan_ly.required' => 'Đơn vị quản lý không được để trống',
            'xi_nghiep.required' => 'Xí nghiệp không được để trống',
            'huyen_id.required' => 'Huyện không được để trống',
        ];
    }
}
