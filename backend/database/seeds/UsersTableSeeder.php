<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate existing records
        User::truncate();

        Artisan::call('passport:client --personal -n --env=testing');

        User::create([
            'name' => 'Administrator',
            'email' => 'admin@test.com',
            'password' => Hash::make('adminPass'),
        ])->assignRole('admin');

        User::create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => Hash::make('managerPass'),
        ])->assignRole('manager');

        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('userPass'),
        ])->assignRole('user');
    }
}
