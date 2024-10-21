<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToaDoKhacSeeder extends Seeder
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        $randomCoordinates = DB::table('toado')
            ->inRandomOrder()
            ->limit(100)
            ->get(['lat', 'lng']);

        $index = 1;

        foreach ($randomCoordinates as $coordinate) {
            $loai = rand(1, 2);
            $ten = $loai === 1 ? 'Km' : 'Cầu';
            DB::table('toa_do_khac')->insert([
                'ten' => $ten . ' ' . $index,
                'loai' => $loai,
                'lat' => $coordinate->lat,
                'lng' => $coordinate->lng,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $index++;
        }
    }
};
