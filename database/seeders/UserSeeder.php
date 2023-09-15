<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => "Admin",
                'email' => 'admin@devbunch.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'user_type_id' => UserType::whereName(UserTypeEnum::ADMIN)->first()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => "Teacher",
                'email' => 'teacher@devbunch.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'user_type_id' => UserType::whereName(UserTypeEnum::TEACHER)->first()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => "Student",
                'email' => 'student@devbunch.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('12345678'),
                'user_type_id' => UserType::whereName(UserTypeEnum::STUDENT)->first()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
