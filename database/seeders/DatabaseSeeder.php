<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            YearSeeder::class,
            CourseSeeder::class
        ]);

        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin admin',
                'password' => Hash::make('12345678'),
            ]
        );

        $user->assignRole('admin');
    }
}
