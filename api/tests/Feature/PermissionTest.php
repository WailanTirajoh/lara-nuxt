<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::where('email', 'wailantirajoh@gmail.com')->first();
        Sanctum::actingAs($user);
    }

    public function test_get_permission_lists(): void
    {
        $response = $this->getJson(route('api.permissions.index'));
        $response->assertOk();

        $this->assertEquals(Permission::count(), count($response->json()['data']['permissions']));
    }
}
