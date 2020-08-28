<?php

namespace Tests\Unit\Requests;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginAuthRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'auth.login';

    /** @test */
    public function request_should_fail_when_no_email_and_password_is_provided()
    {
        $response = $this->postJson(route($this->_route));

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['email', 'password']);
    }

    /** @test */
    public function request_should_fail_when_no_email_is_provided()
    {
        $response = $this->postJson(route($this->_route), [
            'password' => $this->faker->password
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors('email');
    }

    /** @test */
    public function request_should_fail_when_no_password_is_provided()
    {
        $response = $this->postJson(route($this->_route), [
            'email' => $this->faker->email
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors('password');
    }

    /** @test */
    public function request_should_fail_when_email_is_invalid()
    {
        $response = $this->postJson(route($this->_route), [
            'email' => $this->faker->randomLetter,
            'password' => $this->faker->password
        ]);

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors('email');
    }

    /** @test */
    public function request_should_pass_when_full_data_is_provided()
    {
        //Artisan::call('db:seed');
        Artisan::call('passport:client --personal -n');

        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        $this->postJson(route($this->_route), [
            'email' => $user->email,
            'password' => $password
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonMissingValidationErrors([
                'email',
                'password'
            ]);
    }

    /** @test */
    public function request_should_fail_when_incorrect_email()
    {
        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        $this->postJson(route($this->_route), [
            'email' => $this->faker->email,
            'password' => $password
            ])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function request_should_fail_when_incorrect_password()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make($this->faker->password),
        ]);

        $this->postJson(route($this->_route), [
            'email' => $user->email,
            'password' => $this->faker->password
            ])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
