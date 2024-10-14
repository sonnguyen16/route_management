<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGiamSatRequest extends FormRequest
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
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'tuyen_duong_id.required' => 'Trường tuyến đường không được để trống',
            'tuyen_duong_id.exists' => 'Trường tuyến đường không tồn tại',
            'don_vi_id.required' => 'Trường đơn vị không được để trống',
            'don_vi_id.exists' => 'Trường đơn vị không tồn tại',
        ];
    }
}
