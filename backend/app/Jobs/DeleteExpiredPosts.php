<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class DeleteExpiredPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $expiredPosts = Post::where('created_at', '<=', Carbon::now()->subHours(24))->get();

        foreach ($expiredPosts as $post) {
            $post->delete();
        }

        \Log::info('Deleted ' . $expiredPosts->count() . ' expired posts');
    }
}

