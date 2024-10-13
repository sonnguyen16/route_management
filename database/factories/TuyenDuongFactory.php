<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TuyenDuong;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TuyenDuong>
 */
class TuyenDuongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = TuyenDuong::class;

    public function definition(): array
    {
        return [
            'ten' => fake()->name(),
            'loai' => fake()->randomElement([1, 2, 3, 4, 5]),
            'ma_phan_cap' => fake()->randomElement([1, 2, 3, 4, 5]),
            'diem_dau_huyen_id' => fake()->randomElement([1, 2, 3, 4, 5]),
            'diem_dau_xa_id' => fake()->randomElement([1, 2, 3, 4, 5]),
            'diem_cuoi_huyen_id' => fake()->randomElement([1, 2, 3, 4, 5]),
            'diem_cuoi_xa_id' => fake()->randomElement([1, 2, 3, 4, 5]),
            'chieu_dai' => fake()->randomFloat(2, 0, 100),
            'chieu_rong' => fake()->randomFloat(2, 0, 100),
            'dien_tich' => fake()->randomFloat(2, 0, 100),
            'loai_tuan_tra' => fake()->randomElement([1, 2, 3, 4, 5]),
            'don_vi_quan_ly' => fake()->name(),
            'xi_nghiep' => fake()->name(),
            'huyen_id' => fake()->randomElement([1, 2, 3, 4, 5]),
        ];
    }
}
