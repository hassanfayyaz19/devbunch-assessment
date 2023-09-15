<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserType::insert([
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'Teacher',
            ],
            [
                'name' => 'Student',
            ],
        ]);
    }
}
