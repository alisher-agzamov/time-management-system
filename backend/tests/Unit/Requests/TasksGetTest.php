<?php

namespace Tests\Feature\Api\V1\Requests;

use App\Task;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksGetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'tasks.get';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $task = factory(Task::class)->create();

        $response = $this->getJson(route($this->_route, ['task' => $task]));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_token_is_provided()
    {
        $authRequest = $this->_authRequest('user');

        $task = factory(Task::class)->create([
            'user_id'   => $this->_user->id
        ]);

        $response = $this->getJson(
            route($this->_route, ['task' => $task]),
            $authRequest
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_fail_when_user_opens_someone_else_ticket()
    {
        $token = $this->_authRequest('user');

        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->getJson(
            route($this->_route, ['task' => $task]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_manager_opens_someone_else_task()
    {
        $token = $this->_authRequest('manager');

        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->getJson(
            route($this->_route, ['task' => $task]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_admin_opens_someone_else_task()
    {
        $token = $this->_authRequest('admin');

        $task = factory(Task::class)->create([
            'user_id' => factory(User::class)->create()->id
        ]);

        $response = $this->getJson(
            route($this->_route, ['task' => $task]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }
}
