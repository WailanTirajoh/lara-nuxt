<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    private $channel;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::where('email', 'wailantirajoh@gmail.com')->first();
        Sanctum::actingAs($user);

        $this->channel = Channel::factory()->create([
            'name' => 'Development',
            'created_by' => $user->id,
        ]);
        $this->channel->users()->attach($user->id);
    }

    public function test_get_channel_lists(): void
    {
        $response = $this->getJson(route('api.channels.index'));

        $this->assertEquals(1, count($response->json()['data']['channels']));
        $this->assertEquals('Development', $response->json()['data']['channels'][0]['name']);
    }

    public function test_get_channel(): void
    {
        $response = $this->getJson(route('api.channels.show', $this->channel->id))
            ->assertOk()
            ->json();

        $this->assertEquals($response['data']['channel']['name'], $this->channel->name);
    }

    public function test_store_new_channel(): void
    {
        $channel = Channel::factory()->make();
        $response = $this->postJson(route('api.channels.store'), [
            'name' => $channel->name,
            'created_by' => $channel->created_by,
        ])
            ->assertCreated()
            ->json();

        $this->assertEquals($channel->name, $response['data']['channel']['name']);

        $this->assertDatabaseHas('channels', [
            'name' => $channel->name,
        ]);
    }

    public function test_store_new_channel_with_empty_name(): void
    {
        $response = $this->postJson(route('api.channels.store'), [
            'name' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
            ->json();

        $this->assertContains('The name field is required.', $response['errors']['name']);
    }

    public function test_delete_channel_by_id(): void
    {
        $this->deleteJson(
            route('api.channels.destroy', $this->channel->id)
        )
            ->assertNoContent();

        $this->assertDatabaseMissing('channels', [
            'id' => $this->channel->id,
        ]);
    }

    public function test_update_channel_by_id(): void
    {
        $this->putJson(route('api.channels.update', $this->channel->id), [
            'name' => 'Updated channel name',
            'users' => [],
        ])
            ->assertOk();

        $this->assertDatabaseHas('channels', [
            'id' => $this->channel->id,
            'name' => 'Updated channel name',
        ]);
        $this->assertDatabaseEmpty('channel_user');
    }
}
