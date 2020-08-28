<?php

namespace Tests\Unit\Requests;

use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'users.update';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $response = $this->putJson(route($this->_route, ['id' => 'me']));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_invalid_token()
    {
        $response = $this->putJson(route($this->_route, ['id' => 'me']), [], [
            'Authorization' => 'Bearer ' . $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_user_token_is_provided()
    {
        $response = $this->putJson(
            route($this->_route, ['id' => 'me']),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
            ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_pass_when_admin_token_is_provided()
    {
        $response = $this->putJson(
            route($this->_route, ['id' => 'me']),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
            ],
            $this->_authRequest('admin')
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_pass_when_manager_token_is_provided()
    {
        $response = $this->putJson(
            route($this->_route, ['id' => 'me']),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
            ],
            $this->_authRequest('manager')
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_fail_when_user_updates_another_user()
    {
        $token = $this->_authRequest('user');
        $user = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_manager_updates_another_user()
    {
        $token = $this->_authRequest('manager');
        $user = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $user->email
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_pass_when_admin_updates_another_user()
    {
        $token = $this->_authRequest('admin');
        $user = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $user->email
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_fail_when_manager_updates_non_existing_user()
    {
        $token = $this->_authRequest('manager');

        $response = $this->putJson(
            route($this->_route, ['id' => $this->faker->numberBetween(1000, 9999)]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $this->faker->email
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_admin_updates_non_existing_user()
    {
        $token = $this->_authRequest('admin');

        $response = $this->putJson(
            route($this->_route, ['id' => $this->faker->numberBetween(1000, 9999)]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $this->faker->email
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_name_is_not_provided()
    {
        $response = $this->putJson(
            route($this->_route, ['id' => 'me']),
            [
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
            ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function request_should_fail_when_preferred_working_hour_per_day_is_not_provided()
    {
        $response = $this->putJson(
            route($this->_route, ['id' => 'me']),
            [
                'name'  => $this->faker->randomLetter
            ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_preferred_working_hour_per_day_is_less()
    {
        $response = $this->putJson(
            route($this->_route, ['id' => 'me']),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day' => 0
            ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_preferred_working_hour_per_day_is_high()
    {
        $response = $this->putJson(
            route($this->_route, ['id' => 'me']),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day' => 1440
            ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_existing_email_is_provided()
    {
        $token = $this->_authRequest('admin');
        $user = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $userTwo->email
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function request_should_pass_when_a_new_email_is_provided()
    {
        $token = $this->_authRequest('admin');
        $user = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $this->faker->email
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_pass_when_admin_updates_another_user_role()
    {
        $token = $this->_authRequest('admin');
        $user = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $user->email,
                'role'  => 'manager'
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_fail_when_manager_updates_another_user_role()
    {
        $token = $this->_authRequest('manager');
        $user = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $user->email,
                'role'  => 'manager'
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_user_updates_another_user_role()
    {
        $token = $this->_authRequest('user');
        $user = factory(User::class)->create();

        $response = $this->putJson(
            route($this->_route, ['id' => $user->id]),
            [
                'name'  => $this->faker->randomLetter,
                'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
                'email' => $user->email,
                'role'  => 'manager'
            ],
            $token
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }
}
