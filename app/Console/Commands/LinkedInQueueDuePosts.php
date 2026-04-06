<?php

namespace App\Console\Commands;

use App\Jobs\PublishLinkedInPost;
use App\Models\Post;
use Illuminate\Console\Command;

class LinkedInQueueDuePosts extends Command
{
    protected $signature = 'linkedin:queue-due-posts';

    protected $description = 'Queue scheduled LinkedIn posts that are due';

    public function handle(): int
    {
        $duePosts = Post::query()
            ->where('status', 'draft')
            ->whereNotNull('user_id')
            ->whereNotNull('scheduled_for')
            ->where('scheduled_for', '<=', now())
            ->orderBy('scheduled_for')
            ->limit(50)
            ->get();

        foreach ($duePosts as $post) {
            $post->forceFill(['status' => 'queued'])->save();
            PublishLinkedInPost::dispatch($post->id);
        }

        $this->info("Queued {$duePosts->count()} post(s).");

        return self::SUCCESS;
    }
}

