<?php

namespace Tests\Unit\Requests;

use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'users.index';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $response = $this->getJson(route($this->_route));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_invalid_token()
    {
        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Bearer ' . $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_user_token_is_provided()
    {
        $response = $this->getJson(
            route($this->_route),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_admin_token_is_provided()
    {
        $response = $this->getJson(
            route($this->_route),
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
            route($this->_route),
            $this->_authRequest('manager')
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }
}
