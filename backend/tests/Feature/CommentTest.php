<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        return ['Authorization' => 'Bearer ' . $token, 'user' => $user];
    }

    public function test_user_can_add_comment_to_post()
    {
        $auth = $this->authenticate();
        $post = Post::factory()->create();

        $response = $this->withHeaders($auth)->postJson("/api/posts/{$post->id}/comments", [
            'body' => 'This is a test comment',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'comment' => ['id', 'body', 'user_id', 'post_id'],
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'This is a test comment',
            'post_id' => $post->id,
            'user_id' => $auth['user']->id,
        ]);
    }

    public function test_user_can_update_own_comment()
    {
        $auth = $this->authenticate();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $auth['user']->id,
        ]);

        $response = $this->withHeaders($auth)->putJson("/api/comments/{$comment->id}", [
            'body' => 'Updated comment',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'body' => 'Updated comment',
        ]);
    }

    public function test_user_cannot_update_other_user_comment()
    {
        $auth = $this->authenticate();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->withHeaders($auth)->putJson("/api/comments/{$comment->id}", [
            'body' => 'Updated comment',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_comment()
    {
        $auth = $this->authenticate();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $auth['user']->id,
        ]);

        $response = $this->withHeaders($auth)->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }
}

