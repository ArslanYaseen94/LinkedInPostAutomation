<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkedInFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_credentials_page_requires_auth(): void
    {
        $this->get('/linkedin/credentials')->assertRedirect('/login');
    }

    public function test_connect_redirects_to_credentials_when_missing_app_creds(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/linkedin/connect')
            ->assertRedirect('/linkedin/credentials');
    }

    public function test_callback_with_error_query_does_not_throw(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/linkedin/callback?error=invalid_scope&error_description=bad')
            ->assertRedirect('/linkedin/credentials');
    }
}

