<?php

use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate existing records
        Task::truncate();

        $faker = \Faker\Factory::create();

        foreach (User::getAllUsers() as $user) {

            if($user['role'] == 'manager') {
                continue;
            }

            for ($i = 0; $i < mt_rand(5, 20); $i++) {
                Task::create([
                    'title' => $faker->sentence,
                    'description' => $faker->paragraph,
                    'date' => $faker->dateTimeBetween('-10 days'),
                    'duration' => $faker->numberBetween(1, 100),
                    'user_id' => $user['id'],
                ]);
            }
        }
    }
}
