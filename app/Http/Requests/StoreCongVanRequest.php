<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCongVanRequest extends FormRequest
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
            'ten' => 'required|string',
            'loai' => 'required|integer',
            'don_vi_id' => 'required|integer',
            'ngay_gui' => 'required|date',
            'ngay_nhan' => 'required|date',
            'nguoi_xu_ly_id' => 'required|exists:users,id',
            'trang_thai' => 'required|integer',
            'ghi_chu' => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'ten.required' => 'Tên công văn không được để trống',
            'loai.required' => 'Loại công văn không được để trống',
            'loai.integer' => 'Loại công văn phải là số',
            'don_vi_id.integer' => 'Đơn vị không hợp lệ',
            'don_vi_id.required' => 'Đơn vị không được để trống',
            'ngay_gui.required' => 'Ngày gửi không được để trống',
            'ngay_gui.date' => 'Ngày gửi không hợp lệ',
            'ngay_nhan.required' => 'Ngày nhận không được để trống',
            'ngay_nhan.date' => 'Ngày nhận không hợp lệ',
            'nguoi_xu_ly_id.required' => 'Người xử lý không được để trống',
            'nguoi_xu_ly_id.exists' => 'Người xử lý không tồn tại',
            'trang_thai.required' => 'Trạng thái không được để trống',
            'trang_thai.integer' => 'Trạng thái không hợp lệ',
            'ghi_chu.string' => 'Ghi chú không hợp lệ',
        ];
    }
}
