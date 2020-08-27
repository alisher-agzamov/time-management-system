<?php

namespace Tests\Feature\Api\V1\Requests;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @var string
     */
    private $_route = 'status.index';

    /** @test */
    public function request_should_pass_when_api_is_ready()
    {
        $response = $this->getJson(route($this->_route));

        $response->assertStatus(
            Response::HTTP_OK
        );

        $response->assertJson(['status' => 'OK', 'result' => []]);
    }
}
