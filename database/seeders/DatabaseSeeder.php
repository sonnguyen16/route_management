<?php

namespace Database\Seeders;

use App\Models\TuyenDuong;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        User::factory(10)->create();
        TuyenDuong::factory(100)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        $this->call([
            DistrictSeeder::class,
            WardSeeder::class,
            DanhMucSeeder::class,
            TaiLieuSeeder::class,
        ]);
    }
}
