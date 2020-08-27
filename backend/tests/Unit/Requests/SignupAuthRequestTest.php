<?php

namespace Tests\Feature\Api\V1\Requests;

use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SignupAuthRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'auth.signup';

    /** @test */
    public function request_should_fail_when_nothing_is_provided()
    {
        $response = $this->postJson(route($this->_route));

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['name', 'email', 'password', 'preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_no_name_is_provided()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'email' => $this->faker->email,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function request_should_fail_when_no_email_is_provided()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function request_should_fail_when_no_password_is_provided()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function request_should_fail_when_no_confirmation_password_is_provided()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function request_should_fail_when_no_passwords_not_match_is_provided()
    {
        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $this->faker->password,
            'password_confirmation'  => $this->faker->password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function request_should_fail_when_no_preferred_working_hour_per_day_is_provided()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $password,
            'password_confirmation'  => $password
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_preferred_working_hour_per_day_out_of_range_less()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => 0
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_preferred_working_hour_per_day_is_not_integer()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_preferred_working_hour_per_day_out_of_range_more()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => 1440
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['preferred_working_hour_per_day']);
    }

    /** @test */
    public function request_should_fail_when_role_not_exists()
    {
        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439),
            'role'  => $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['role']);
    }

    /** @test */
    public function request_should_pass_when_data_is_provided()
    {
        // Role should be exists
        Artisan::call('db:seed');

        $password = $this->faker->password;

        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $this->faker->email,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function request_should_fail_when_email_already_exists()
    {
        $password = $this->faker->password;

        $user = factory(\App\User::class)->create([
            'email' => $this->faker->email,
            'password'  => Hash::make($password)
        ]);


        $response = $this->postJson(route($this->_route), [
            'name' => $this->faker->randomLetter,
            'email' => $user->email,
            'password'  => $password,
            'password_confirmation'  => $password,
            'preferred_working_hour_per_day'    => $this->faker->numberBetween(1, 1439)
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['email']);
    }
}
