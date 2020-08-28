<?php

namespace Tests\Unit\Requests;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutAuthRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'auth.logout';

    /** @test */
    public function request_should_fail_when_no_token()
    {
        $response = $this->getJson(route($this->_route));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_incorrect_token()
    {
        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Bearer ' . $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_correct_token_is_provided()
    {
        Artisan::call('passport:client --personal -n');

        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        // Create token
        $token = $user->createToken(__('Personal Access Token'));

        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Bearer ' . $token->accessToken
        ]);

        $response->assertStatus(
            Response::HTTP_NO_CONTENT
        );
    }

    /** @test */
    public function request_should_fail_when_incorrect_auth_type_is_provided()
    {
        Artisan::call('passport:client --personal -n');

        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        // Create token
        $token = $user->createToken(__('Personal Access Token'));

        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Basic ' . $token->accessToken
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_token_alredy_revoked()
    {
        Artisan::call('passport:client --personal -n');

        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        // Create token
        $token = $user->createToken(__('Personal Access Token'));

        // Revoke token
        foreach($user->tokens as $tokenItem) {
            $tokenItem->revoke();
        }

        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Bearer ' . $token->accessToken
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }
}
