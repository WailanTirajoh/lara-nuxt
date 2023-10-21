<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    private $thread;
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
        $this->thread = Thread::factory()->create([
            'body' => 'Development',
            'channel_id' => $this->channel->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_get_thread_lists(): void
    {
        $response = $this->getJson(route('api.channels.threads.index', [
            'channel' => $this->channel,
        ]));

        $this->assertEquals(1, count($response->json()['data']['threads']));
        $this->assertEquals('Development', $response->json()['data']['threads'][0]['body']);
    }

    public function test_store_new_thread(): void
    {
        $thread = Thread::factory()->make();
        $response = $this->postJson(route('api.channels.threads.store', [
            'channel' => $this->channel,
        ]), [
            'body' => $thread->body,
            'user_id' => $thread->user_id
        ])
            ->assertCreated()
            ->json();

        $this->assertEquals($thread->body, $response['data']['thread']['body']);

        $this->assertDatabaseHas('threads', [
            'body' => $thread->body
        ]);
    }

    public function test_store_new_thread_with_empty_body(): void
    {
        $response = $this->postJson(route('api.channels.threads.store', [
            'channel' => $this->channel,
        ]), [
            'body' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('body')
            ->json();

        $this->assertContains('The body field is required.', $response['errors']['body']);
    }

    public function test_delete_thread_by_id(): void
    {
        $this->deleteJson(
            route('api.channels.threads.destroy', [
                'channel' => $this->channel,
                'thread' => $this->thread->id,
            ])
        )
            ->assertNoContent();

        $this->assertDatabaseMissing('threads', [
            'id' => $this->thread->id
        ]);
    }

    public function test_update_thread_by_id(): void
    {
        $this->putJson(route('api.channels.threads.update', [
            'channel' => $this->channel,
            'thread' => $this->thread->id,
        ]), [
            'body' => "Updated thread body",
        ])
            ->assertOk();

        $this->assertDatabaseHas('threads', [
            'id' => $this->thread->id,
            'body' => 'Updated thread body'
        ]);
    }
}
