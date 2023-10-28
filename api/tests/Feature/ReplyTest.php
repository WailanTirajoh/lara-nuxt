<?php

namespace Tests\Feature;

use App\Events\ThreadReplied;
use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReplyTest extends TestCase
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

    public function test_get_thread_reply_lists(): void
    {
        $response = $this->getJson(route('api.threads.replies.index', [
            'thread' => $this->thread,
        ]));

        $this->assertEquals(0, count($response->json()['data']['replies']));
    }

    public function test_store_new_thread_reply(): void
    {
        Event::fake();

        $reply = Reply::factory()->make();
        $response = $this->postJson(route('api.threads.replies.store', [
            'thread' => $this->thread,
        ]), [
            'body' => $reply->body,
            'user_id' => $reply->user_id,
        ])
            ->assertCreated()
            ->json();

        Event::assertDispatched(ThreadReplied::class);

        $this->assertEquals($reply->body, $response['data']['reply']['body']);

        $this->assertDatabaseHas('replies', [
            'body' => $reply->body,
        ]);
    }

    public function test_store_new_thread_reply_with_empty_body(): void
    {
        $response = $this->postJson(route('api.threads.replies.store', [
            'thread' => $this->thread,
        ]), [
            'body' => '',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('body')
            ->json();

        $this->assertContains('The body field is required.', $response['errors']['body']);
    }
}
