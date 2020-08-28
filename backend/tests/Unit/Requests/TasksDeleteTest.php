<?php

namespace Tests\Unit\Requests;

use App\Task;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'tasks.delete';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $task = factory(Task::class)->create();

        $response = $this->deleteJson(route($this->_route, ['task' => $task]));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_invalid_token()
    {
        $task = factory(Task::class)->create();

        $response = $this->deleteJson(route($this->_route, ['task' => $task]), [], [
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

        $response = $this->deleteJson(
            route($this->_route, ['task' => $task]), [],
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

        $response = $this->deleteJson(
            route($this->_route, ['task' => $task]), [],
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

        $response = $this->deleteJson(
            route($this->_route, ['task' => $task]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_user_deletes_another_user_task()
    {
        $token = $this->_authRequest('user');
        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->deleteJson(
            route($this->_route, ['task' => $task]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_manager_deletes_another_user_task()
    {
        $token = $this->_authRequest('manager');
        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->deleteJson(
            route($this->_route, ['task' => $task]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_admin_deletes_another_user_task()
    {
        $token = $this->_authRequest('admin');
        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->deleteJson(
            route($this->_route, ['task' => $task]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }
}
