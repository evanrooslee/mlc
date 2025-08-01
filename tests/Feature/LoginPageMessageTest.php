<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginPageMessageTest extends TestCase
{
    public function test_login_page_displays_session_message()
    {
        // Visit login page with a session message
        $response = $this->withSession(['message' => 'Test message'])
            ->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Test message');
    }

    public function test_login_page_without_message()
    {
        // Visit login page without session message
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertDontSee('Test message');
    }
}
