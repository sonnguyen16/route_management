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
            'tu_km' => 'required|numeric',
            'den_km' => 'required|numeric',
            'toc_do' => 'required|numeric',
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
            'tu_km.required' => 'Từ km không được để trống',
            'tu_km.numeric' => 'Từ km phải là số',
            'den_km.required' => 'Đến km không được để trống',
            'den_km.numeric' => 'Đến km phải là số',
            'toc_do.required' => 'Tốc độ không được để trống',
            'toc_do.numeric' => 'Tốc độ phải là số',
        ];
    }
}
