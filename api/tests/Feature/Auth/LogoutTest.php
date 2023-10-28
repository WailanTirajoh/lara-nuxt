<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_a_user_can_logout_after_login(): void
    {
        $user = User::factory()->create();

        $responseLogin = $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertOk();

        $responseLoginJson = $responseLogin->json();

        $this->assertArrayHasKey('data', $responseLoginJson);
        $this->assertArrayHasKey('access_token', $responseLoginJson['data']);

        $accessToken = $responseLoginJson['data']['access_token'];

        Sanctum::actingAs($user);

        $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->deleteJson(route('api.logout'))
            ->assertNoContent();

        $this->refreshApplication();
        $this->refreshDatabase();

        $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->getJson(route('api.posts.index'))
            ->assertUnauthorized();
    }
}
