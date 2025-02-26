<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGioiHanTocDoRequest extends FormRequest
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
            'noi_dung' => 'nullable',
            'tu_ngay' => 'nullable',
            'den_ngay' => 'nullable',
            'tu_km' => 'nullable',
            'den_km' => 'nullable',
            'don_vi_id' =>'nullable',
            'gioi_han_toc_do_id' => 'nullable',
            'don_vi_thuc_hien_id' => 'nullable',
            'route_geometry' => 'nullable',
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
        ];
    }
}
