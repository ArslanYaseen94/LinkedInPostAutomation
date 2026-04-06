<?php

namespace App\Http\Controllers;

use App\Jobs\PublishLinkedInPost;
use App\Models\Post;
use Illuminate\Http\Request;

class PostRetryController extends Controller
{
    public function retry(Post $post)
    {
        $this->authorize('update', $post);

        if (!$post->canRetry()) {
            return back()->with('error', 'This post cannot be retried. Max retries exceeded or invalid status.');
        }

        // Reset to queued and retry
        $post->logStatusChange('queued', 'Manual retry initiated');
        $post->update(['last_error' => null]);

        // Dispatch the job again
        PublishLinkedInPost::dispatch($post->id);

        return back()->with('success', 'Post retry has been queued.');
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $auditLogs = $post->auditLogs()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('posts.retry-history', compact('post', 'auditLogs'));
    }
}
