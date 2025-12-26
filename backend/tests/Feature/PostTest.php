<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return ['Authorization' => 'Bearer ' . $token, 'user' => $user];
    }

    public function test_user_can_create_post()
    {
        $auth = $this->authenticate();

        $response = $this->withHeaders($auth)->postJson('/api/posts', [
            'title' => 'Test Post',
            'body' => 'This is a test post body',
            'tags' => ['laravel', 'php'],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'post' => ['id', 'title', 'body', 'author', 'tags'],
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'author_id' => $auth['user']->id,
        ]);
    }

    public function test_user_cannot_create_post_without_tags()
    {
        $auth = $this->authenticate();

        $response = $this->withHeaders($auth)->postJson('/api/posts', [
            'title' => 'Test Post',
            'body' => 'This is a test post body',
            'tags' => [],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['tags']);
    }

    public function test_user_can_view_all_posts()
    {
        $auth = $this->authenticate();
        Post::factory()->count(3)->create();

        $response = $this->withHeaders($auth)->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_update_own_post()
    {
        $auth = $this->authenticate();
        $post = Post::factory()->create(['author_id' => $auth['user']->id]);

        $response = $this->withHeaders($auth)->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title',
            'body' => 'Updated body',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_user_cannot_update_other_user_post()
    {
        $auth = $this->authenticate();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->withHeaders($auth)->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_post()
    {
        $auth = $this->authenticate();
        $post = Post::factory()->create(['author_id' => $auth['user']->id]);

        $response = $this->withHeaders($auth)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_other_user_post()
    {
        $auth = $this->authenticate();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['author_id' => $otherUser->id]);

        $response = $this->withHeaders($auth)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(403);
    }
}

