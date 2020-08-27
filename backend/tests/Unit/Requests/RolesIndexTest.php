<?php

namespace Tests\Feature\Api\V1\Requests;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'roles.index';

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
    public function request_should_pass_when_correct_token_is_provided()
    {
        Artisan::call('db:seed');

        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        // Assign admin
        $user->assignRole('admin');

        // Create token
        $token = $user->createToken(__('Personal Access Token'));

        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Bearer ' . $token->accessToken
        ]);

        $response->assertStatus(
            Response::HTTP_OK
        );

        $user->delete();
    }

    /** @test */
    public function request_should_fail_when_provided_token_with_user_permissions()
    {
        Artisan::call('db:seed');

        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        // Assign admin
        $user->assignRole('user');

        // Create token
        $token = $user->createToken(__('Personal Access Token'));

        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Bearer ' . $token->accessToken
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );

        $user->delete();
    }

    /** @test */
    public function request_should_fail_when_provided_token_with_manager_permissions()
    {
        Artisan::call('db:seed');

        $password = $this->faker->password;

        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        // Assign admin
        $user->assignRole('manager');

        // Create token
        $token = $user->createToken(__('Personal Access Token'));

        $response = $this->getJson(route($this->_route), [
            'Authorization' => 'Bearer ' . $token->accessToken
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );

        $user->delete();
    }
}
