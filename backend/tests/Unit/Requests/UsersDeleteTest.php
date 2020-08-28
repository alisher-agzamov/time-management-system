<?php

namespace Tests\Unit\Requests;

use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersDeleteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'users.delete';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $user = factory(User::class)->create();

        $response = $this->deleteJson(route($this->_route, ['user' => $user]));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_invalid_token()
    {
        $user = factory(User::class)->create();

        $response = $this->deleteJson(route($this->_route, ['user' => $user]), [], [
            'Authorization' => 'Bearer ' . $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_user_token_is_provided()
    {
        $token = $this->_authRequest('user');
        $user = factory(User::class)->create();

        $response = $this->deleteJson(
            route($this->_route, ['user' => $user]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_admin_token_is_provided()
    {
        $token = $this->_authRequest('admin');
        $user = factory(User::class)->create();

        $response = $this->deleteJson(
            route($this->_route, ['user' => $user]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_pass_when_manager_token_is_provided()
    {
        $token = $this->_authRequest('manager');
        $user = factory(User::class)->create();

        $response = $this->deleteJson(
            route($this->_route, ['user' => $user]), [],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }
}
