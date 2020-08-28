<?php

namespace Tests\Unit\Requests;

use App\Task;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'tasks.update';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $task = factory(Task::class)->create();

        $response = $this->putJson(route($this->_route, ['task' => $task]));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_invalid_token()
    {
        $task = factory(Task::class)->create();

        $response = $this->putJson(route($this->_route, ['task' => $task]), [], [
            'Authorization' => 'Bearer ' . $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_user_token_is_provided()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date(),
                'duration' => $this->faker->numberBetween(1, 100)
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_pass_when_admin_token_is_provided()
    {
        $token = $this->_authRequest('admin');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date(),
                'duration' => $this->faker->numberBetween(1, 100)
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_fail_when_manager_token_is_provided()
    {
        $token = $this->_authRequest('manager');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date(),
                'duration' => $this->faker->numberBetween(1, 100)
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_title_is_not_provided()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date(),
                'duration' => $this->faker->numberBetween(1, 100)
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function request_should_fail_when_description_is_not_provided()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'date' => $this->faker->date(),
                'duration' => $this->faker->numberBetween(1, 100)
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['description']);
    }

    /** @test */
    public function request_should_fail_when_date_is_not_provided()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                //'date' => $this->faker->date(),
                'duration' => $this->faker->numberBetween(1, 100)
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date']);
    }

    /** @test */
    public function request_should_fail_when_duration_is_not_provided()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date()
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['duration']);
    }

    /** @test */
    public function request_should_fail_when_duration_is_less()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date(),
                'duration' => 0
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['duration']);
    }

    /** @test */
    public function request_should_fail_when_duration_is_high()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date(),
                'duration' => 1440
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['duration']);
    }

    /** @test */
    public function request_should_fail_when_data_is_not_provided()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => $this->_user->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['title', 'description', 'date', 'duration']);
    }

    /** @test */
    public function request_should_fail_when_user_provided_another_user_task()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_manager_provided_another_user_task()
    {
        $token = $this->_authRequest('manager');
        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_admin_provided_another_user_task()
    {
        $token = $this->_authRequest('admin');
        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->putJson(
            route($this->_route, ['task' => $task]), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }
}
