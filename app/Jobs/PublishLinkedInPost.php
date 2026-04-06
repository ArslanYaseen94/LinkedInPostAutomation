<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use App\Services\LinkedIn\LinkedInClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishLinkedInPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $postId)
    {
    }

    public function handle(LinkedInClient $client): void
    {
        /** @var Post $post */
        $post = Post::query()->findOrFail($this->postId);
        $user = User::query()->findOrFail($post->user_id);

        if ($post->status === 'sent') {
            return;
        }

        $post->forceFill([
            'status' => 'queued',
            'last_error' => null,
        ])->save();

        $response = $client->publishWithImage($user, $post->text, $post->image_path, $post->link_url);

        if (($response['ok'] ?? false) === true) {
            $post->forceFill([
                'status' => 'sent',
                'sent_at' => now(),
                'linkedin_urn' => $response['result']['linkedin_urn'] ?? null,
                'last_error' => null,
            ])->save();

            return;
        }

        $post->forceFill([
            'status' => 'failed',
            'last_error' => (string) ($response['error'] ?? 'Unknown error'),
        ])->save();
    }
}

