<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private $role;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::where('email', 'wailantirajoh@gmail.com')->first();
        Sanctum::actingAs($user);

        $this->role = Role::create([
            'name' => 'Role Test',
        ]);
    }

    public function test_get_role_lists(): void
    {
        $response = $this->getJson(route('api.roles.index'));

        $this->assertEquals(5, count($response->json()['data']['roles']));
    }

    public function test_get_role(): void
    {
        $response = $this->getJson(route('api.roles.show', $this->role->id))
            ->assertOk()
            ->json();

        $this->assertEquals($response['data']['role']['name'], $this->role->name);
    }

    public function test_store_new_role(): void
    {
        $roleName = 'Test created';
        $response = $this->postJson(route('api.roles.store'), [
            'name' => $roleName,
        ])
            ->assertCreated()
            ->json();

        $this->assertEquals($roleName, $response['data']['role']['name']);

        $this->assertDatabaseHas('roles', [
            'name' => $roleName
        ]);
    }

    public function test_store_new_role_with_empty_name(): void
    {
        $response = $this->postJson(route('api.roles.store'), [
            'name' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
            ->json();

        $this->assertContains('The name field is required.', $response['errors']['name']);
    }

    public function test_delete_role_by_id(): void
    {
        $this->deleteJson(
            route('api.roles.destroy', $this->role->id)
        )
            ->assertNoContent();

        $this->assertDatabaseMissing('roles', [
            'id' => $this->role->id
        ]);
    }

    public function test_update_role_by_id(): void
    {
        $this->putJson(route('api.roles.update', $this->role->id), [
            'name' => "Updated role name"
        ])
            ->assertOk()
            ->json();

        $this->assertDatabaseHas('roles', [
            'id' => $this->role->id,
            'name' => 'Updated role name'
        ]);
    }
}
