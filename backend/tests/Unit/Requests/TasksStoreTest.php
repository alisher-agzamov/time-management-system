<?php

namespace Tests\Feature\Api\V1\Requests;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksStoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'tasks.store';

    /** @test */
    public function request_should_fail_when_token_is_not_provided()
    {
        $response = $this->postJson(route($this->_route));

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_provided_invalid_token()
    {
        $response = $this->postJson(route($this->_route), [], [
            'Authorization' => 'Bearer ' . $this->faker->randomLetter
        ]);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_user_token_is_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
                'title' => $this->faker->sentence,
                'description' => $this->faker->paragraph,
                'date' => $this->faker->date(),
                'duration' => $this->faker->numberBetween(1, 100)
            ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_CREATED
        );
    }

    /** @test */
    public function request_should_pass_when_admin_token_is_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $this->_authRequest('admin')
        );

        $response->assertStatus(
            Response::HTTP_CREATED
        );
    }

    /** @test */
    public function request_should_fail_when_manager_token_is_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $this->_authRequest('manager')
        );

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_title_is_not_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function request_should_fail_when_empty_title_is_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => '',
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function request_should_fail_when_big_title_is_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->paragraph(100),
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function request_should_fail_when_description_is_not_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['description']);
    }

    /** @test */
    public function request_should_fail_when_date_is_not_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(1, 100)
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['date']);
    }

    /** @test */
    public function request_should_fail_when_duration_is_not_provided()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date()
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['duration']);
    }

    /** @test */
    public function request_should_fail_when_duration_is_less()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => 0
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['duration']);
    }

    /** @test */
    public function request_should_fail_when_duration_is_high()
    {
        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => 1440
        ],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['duration']);
    }

    /** @test */
    public function request_should_fail_when_data_is_not_provided()
    {
        $response = $this->postJson(
            route($this->_route), [],
            $this->_authRequest('user')
        );

        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonValidationErrors(['title', 'description', 'date', 'duration']);
    }

    /** @test */
    public function request_should_fail_when_user_provided_user_id()
    {
        $token = $this->_authRequest('user');

        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100),
            'user_id'   => factory(User::class)->create()->id
        ], $token);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_fail_when_manager_provided_user_id()
    {
        $token = $this->_authRequest('manager');

        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100),
            'user_id'   => factory(User::class)->create()->id
        ], $token);

        $response->assertStatus(
            Response::HTTP_FORBIDDEN
        );
    }

    /** @test */
    public function request_should_pass_when_admin_provided_user_id()
    {
        $token = $this->_authRequest('admin');

        $response = $this->postJson(
            route($this->_route), [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(1, 100),
            'user_id'   => factory(User::class)->create()->id
        ], $token);

        $response->assertStatus(
            Response::HTTP_CREATED
        );
    }
}
