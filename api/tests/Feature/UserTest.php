<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::where('email', 'wailantirajoh@gmail.com')->first();
        Sanctum::actingAs($user);

        $this->user = User::factory()->create([
            'name' => 'User Test',
        ]);

        // For image upload
        config()->set('filesystems.disks.media', [
            'driver' => 'local',
            'root' => __DIR__.'/../../temp', // choose any path...
        ]);
        config()->set('medialibrary.default_filesystem', 'media');
    }

    public function test_get_user_lists(): void
    {
        $response = $this->getJson(route('api.users.index'));

        $this->assertEquals(5, count($response->json()['data']['users']));
    }

    public function test_get_user(): void
    {
        $response = $this->getJson(route('api.users.show', $this->user->id))
            ->assertOk()
            ->json();

        $this->assertEquals($response['data']['user']['name'], $this->user->name);
    }

    public function test_store_new_user(): void
    {
        $photo = \Illuminate\Http\Testing\File::image('photo.jpg');
        $user = User::factory()->make();
        $response = $this->postJson(route('api.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'created_by' => $user->created_by,
            'image' => $photo,
        ])
            ->assertCreated()
            ->json();

        $photos = User::find($response['data']['user']['id'])->getMedia('images');
        $this->assertCount(1, $photos);
        $this->assertFileExists($photos->first()->getPath());

        $this->assertEquals($user->name, $response['data']['user']['name']);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
        ]);
    }

    public function test_store_new_user_with_empty_name(): void
    {
        $response = $this->postJson(route('api.users.store'), [
            'name' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
            ->json();

        $this->assertContains('The name field is required.', $response['errors']['name']);
    }

    public function test_delete_user_by_id(): void
    {
        $this->deleteJson(
            route('api.users.destroy', $this->user->id)
        )
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
    }

    public function test_update_user_by_id(): void
    {
        $photo = \Illuminate\Http\Testing\File::image('photo.jpg');
        $response = $this->putJson(route('api.users.update', $this->user->id), [
            'name' => 'Updated user name',
            'email' => $this->user->email,
            'users' => [],
            'image' => $photo,
        ])
            ->assertOk()
            ->json();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated user name',
        ]);

        $photos = User::find($response['data']['user']['id'])->getMedia('images');
        $this->assertCount(1, $photos);
        $this->assertFileExists($photos->first()->getPath());
    }
}
