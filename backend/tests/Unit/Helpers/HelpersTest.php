<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Helper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class HelpersTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function request_should_pass_when_noting_is_provided()
    {
        $this->assertSame('', Helper::renderDuration(0));
    }

    /** @test */
    public function request_should_pass_when_1_minute_is_provided()
    {
        $this->assertSame('1m', Helper::renderDuration(1));
    }

    /** @test */
    public function request_should_pass_when_10_minute_is_provided()
    {
        $this->assertSame('10m', Helper::renderDuration(10));
    }

    /** @test */
    public function request_should_pass_when_59_minute_is_provided()
    {
        $this->assertSame('59m', Helper::renderDuration(59));
    }

    /** @test */
    public function request_should_pass_when_1_hour_is_provided()
    {
        $this->assertSame('1h', Helper::renderDuration(60));
    }

    /** @test */
    public function request_should_pass_when_1_hour_and_11_minutes_are_provided()
    {
        $this->assertSame('1h 11m', Helper::renderDuration(71));
    }
}
