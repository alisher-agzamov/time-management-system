<?php

namespace Tests\Feature\Api\V1\Requests;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'tasks.index';

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
    public function request_should_pass_when_user_token_is_provided()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_from' => '2020-08-01',
                'date_to'   => '2020-08-20'
            ]),
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
            route($this->_route, [
                'date_from' => '2020-08-01',
                'date_to'   => '2020-08-20'
            ]),
            $this->_authRequest('admin')
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_fail_when_manager_token_is_provided()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_from' => '2020-08-01',
                'date_to'   => '2020-08-20'
            ]),
            $this->_authRequest('manager')
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_date_from_is_not_provided()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_to'   => '2020-08-01'
            ]),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date_from']);
    }

    /** @test */
    public function request_should_fail_when_date_to_is_not_provided()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_from' => '2020-08-20'
            ]),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date_to']);
    }

    /** @test */
    public function request_should_fail_when_date_from_is_integer()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_from' => $this->faker->randomDigit,
                'date_to'   => '2020-08-20'
            ]),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date_from']);
    }

    /** @test */
    public function request_should_fail_when_date_from_is_string()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_from' => $this->faker->randomLetter,
                'date_to'   => '2020-08-20'
            ]),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date_from']);
    }

    /** @test */
    public function request_should_fail_when_date_to_is_integer()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_to'   => $this->faker->randomDigit,
                'date_from' => '2020-08-20'
            ]),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date_to']);
    }

    /** @test */
    public function request_should_fail_when_date_to_is_string()
    {
        $response = $this->getJson(route($this->_route, [
                'date_to' => $this->faker->randomLetter,
                'date_from' => '2020-08-20'
            ]),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date_to']);
    }

    /** @test */
    public function request_should_pass_when_admin_provided_correct_user_id()
    {
        $user = $this->_createUser();

        $response = $this->getJson(
            route($this->_route, [
                'date_from' => '2020-08-01',
                'date_to'   => '2020-08-20',
                'user_id'   => $user->id
            ]),
            $this->_authRequest('admin')
        );

        $response->assertStatus(
            Response::HTTP_OK
        );
    }

    /** @test */
    public function request_should_fail_when_admin_provided_incorrect_user_id()
    {
        $response = $this->getJson(
            route($this->_route, [
                'date_from' => '2020-08-01',
                'date_to'   => '2020-08-20',
                'user_id'   => $this->faker->numberBetween(1000, 9999)
            ]),
            $this->_authRequest('admin')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function request_should_pass_when_user_provided_user_id_but_does_not_have_permissions()
    {
        $user = $this->_createUser();

        $response = $this->getJson(
            route($this->_route, [
                'date_from' => '2020-08-01',
                'date_to'   => '2020-08-20',
                'user_id'   => $user->id
            ]),
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_manager_provided_user_id_but_does_not_have_permissions()
    {
        $user = $this->_createUser();

        $response = $this->getJson(
            route($this->_route, [
                'date_from' => '2020-08-01',
                'date_to'   => '2020-08-20',
                'user_id'   => $user->id
            ]),
            $this->_authRequest('manager')
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }
}
