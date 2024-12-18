<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDuongCamRequest extends FormRequest
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
            'tuyen_duong_id' => 'required|integer',
            'don_vi_quyet_dinh_id' => 'required|integer',
            'don_vi_thuc_hien_id' => 'required|integer',
            'tu_ngay' => 'nullable', //'required|date',
            'den_ngay' => 'nullable', // 'required|date',
            'ly_do' => 'nullable', //'required|string',
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
            'tuyen_duong_id.integer' => 'Tuyến đường phải là số',
            'don_vi_quyet_dinh_id.required' => 'Đơn vị quyết định không được để trống',
            'don_vi_quyet_dinh_id.integer' => 'Đơn vị quyết định phải là số',
            'don_vi_thuc_hien_id.required' => 'Đơn vị thực hiện không được để trống',
            'don_vi_thuc_hien_id.integer' => 'Đơn vị thực hiện phải là số',
          //  'tu_ngay.required' => 'Từ ngày không được để trống',
         //   'tu_ngay.date' => 'Từ ngày phải là ngày',
          //  'den_ngay.required' => 'Đến ngày không được để trống',
          //  'den_ngay.date' => 'Đến ngày phải là ngày',
         //   'ly_do.required' => 'Lý do không được để trống',
           // 'ly_do.string' => 'Lý do phải là chuỗi',
        ];
    }
}
