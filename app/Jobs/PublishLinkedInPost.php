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

    public int $tries = 3;
    public int $maxExceptions = 3;

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

        $post->logStatusChange('queued', 'Job processing started');

        $response = $client->publishWithImage($user, $post->text, $post->image_path, $post->link_url);

        if (($response['ok'] ?? false) === true) {
            $post->forceFill([
                'linkedin_urn' => $response['result']['linkedin_urn'] ?? null,
                'last_error' => null,
            ])->save();

            $post->logStatusChange('sent', 'Successfully published to LinkedIn', null);
            $post->update(['sent_at' => now()]);

            return;
        }

        $errorMessage = (string) ($response['error'] ?? 'Unknown error');
        $post->incrementRetryCount();

        if ($post->canRetry()) {
            $post->logStatusChange('failed', 'Publishing failed - will retry', $errorMessage);
            $post->update(['last_error' => $errorMessage]);
            
            // Retry the job
            $this->release(delay: 60); // Wait 60 seconds before retry
        } else {
            $post->logStatusChange('failed', 'Publishing failed - max retries exceeded', $errorMessage);
            $post->update(['last_error' => $errorMessage]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        $post = Post::query()->find($this->postId);
        if ($post) {
            $post->logStatusChange('failed', 'Job exception', $exception->getMessage());
            $post->update(['last_error' => $exception->getMessage()]);
        }
    }
}

