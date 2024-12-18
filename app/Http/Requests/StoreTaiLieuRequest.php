<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaiLieuRequest extends FormRequest
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
            'tuyen_duong_id' => 'nullable|exists:tuyen_duong,id',
            'cong_van_id' => 'nullable|exists:cong_van,id',
            'toa_do_id' => 'nullable|exists:toa_do_khac,id',
            'danh_muc' => 'nullable',
            'file' => 'required',
            'type' => 'nullable',
        ];
    }
}
