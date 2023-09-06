<?php

namespace Database\Seeders;

use App\Models\Marks;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MarksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markes = [];
        $cDate = now();
        for ($i = 1; $i <= 1000; $i++) {
            $date = now()->subDays($i);
            for ($si = 1; $si <= 5; $si++) {
                $markes[] = [
                    'user_id' => $si,
                    'mark' => rand(1, 100),
                    'examination_date' => $date,
                    'created_at' => $cDate,
                    'updated_at' => $cDate,
                ];
            }
        }
        Marks::insert($markes);
    }
}
