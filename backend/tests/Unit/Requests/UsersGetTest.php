<?php

namespace Tests\Feature\Api\V1\Requests;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersGetTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'users.get';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $response = $this->getJson(route($this->_route, ['id' => 'me']));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_invalid_token()
    {
        $response = $this->getJson(route($this->_route, ['id' => 'me']), [
            'Authorization' => 'Bearer ' . $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_user_token_is_provided()
    {
        $response = $this->getJson(
            route($this->_route, ['id' => 'me']),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_pass_when_admin_token_is_provided()
    {
        $response = $this->getJson(
            route($this->_route, ['id' => 'me']),
            $this->_authRequest('admin')
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_pass_when_manager_token_is_provided()
    {
        $response = $this->getJson(
            route($this->_route, ['id' => 'me']),
            $this->_authRequest('manager')
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_fail_when_user_gets_another_user()
    {
        $token = $this->_authRequest('user');
        $user = factory(User::class)->create();

        $response = $this->getJson(
            route($this->_route, ['id' => $user->id]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_manager_gets_another_user()
    {
        $token = $this->_authRequest('manager');
        $user = factory(User::class)->create();

        $response = $this->getJson(
            route($this->_route, ['id' => $user->id]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_pass_when_admin_gets_another_user()
    {
        $token = $this->_authRequest('admin');
        $user = factory(User::class)->create();

        $response = $this->getJson(
            route($this->_route, ['id' => $user->id]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_fail_when_manager_gets_non_existing_user()
    {
        $token = $this->_authRequest('manager');

        $response = $this->getJson(
            route($this->_route, ['id' => $this->faker->numberBetween(1000, 9999)]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_admin_gets_non_existing_user()
    {
        $token = $this->_authRequest('admin');

        $response = $this->getJson(
            route($this->_route, ['id' => $this->faker->numberBetween(1000, 9999)]),
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }
}
