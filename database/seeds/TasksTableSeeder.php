<?php

use App\Task;
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

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 5; $i++) {
            Task::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'date' => $faker->date(),
                'duration' => $faker->numberBetween(1, 100),
                'user_id' => 1,
            ]);
        }
    }
}
