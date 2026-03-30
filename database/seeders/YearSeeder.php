<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\Year;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = [
            'First Year',
            'Second Year',
        ];

        foreach ($years as $year) {
            Year::updateOrCreate(
                ['name' => $year]
            );
        }
    }
}
