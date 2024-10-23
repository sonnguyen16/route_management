<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuaChuaRequest extends FormRequest
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
            'ngay_khoi_cong' => 'required|date',
            'ngay_hoan_thanh' => 'required|date',
            'tu_km' => 'required|numeric',
            'den_km' => 'required|numeric',
            'ngay_duyet' => 'required|date',
            'nguoi_duyet_id' => 'required|exists:users,id',
            'loai_sua_chua' => 'required|numeric',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array{
        return [
            'tuyen_duong_id.required' => 'Tuyến đường không được để trống',
            'tuyen_duong_id.exists' => 'Tuyến đường không tồn tại',
            'don_vi_id.required' => 'Đơn vị không được để trống',
            'don_vi_id.exists' => 'Đơn vị không tồn tại',
            'ngay_khoi_cong.required' => 'Ngày khởi công không được để trống',
            'ngay_khoi_cong.date' => 'Ngày khởi công không đúng định dạng',
            'ngay_hoan_thanh.required' => 'Ngày hoàn thành không được để trống',
            'ngay_hoan_thanh.date' => 'Ngày hoàn thành không đúng định dạng',
            'tu_km.required' => 'Từ km không được để trống',
            'tu_km.numeric' => 'Từ km phải là số',
            'den_km.required' => 'Đến km không được để trống',
            'den_km.numeric' => 'Đến km phải là số',
            'ngay_duyet.required' => 'Ngày duyệt không được để trống',
            'ngay_duyet.date' => 'Ngày duyệt không đúng định dạng',
            'nguoi_duyet_id.required' => 'Người duyệt không được để trống',
            'nguoi_duyet_id.exists' => 'Người duyệt không tồn tại',
            'loai_sua_chua.required' => 'Loại sửa chữa không được để trống',
            'loai_sua_chua.numeric' => 'Loại sửa chữa phải là số',
        ];
    }
}
