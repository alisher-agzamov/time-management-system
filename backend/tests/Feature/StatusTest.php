<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    /**
     * Check API status
     *
     * @return void
     */
    public function testApiStatus()
    {
        $response = $this->getJson('/api/v1/status');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'OK'
            ]);
    }
}
