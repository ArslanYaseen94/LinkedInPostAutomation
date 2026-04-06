<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostAnalyticsController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $posts = Post::where('user_id', $user->id)
            ->where('status', 'sent')
            ->orderBy('sent_at', 'desc')
            ->get();

        $stats = [
            'total_posts' => $posts->count(),
            'total_impressions' => $posts->sum('impressions'),
            'total_reactions' => $posts->sum('reactions'),
            'total_comments' => $posts->sum('comments'),
            'total_clicks' => $posts->sum('clicks'),
            'average_engagement_rate' => $posts->count() > 0 
                ? $posts->average(function ($post) { return $post->getEngagementRate(); })
                : 0,
            'top_post' => $posts->sortByDesc(function ($post) { 
                return $post->impressions + $post->reactions + $post->comments;
            })->first(),
        ];

        return view('posts.analytics', compact('posts', 'stats'));
    }

    public function postDetail(Post $post)
    {
        $this->authorize('view', $post);

        $auditLogs = $post->auditLogs()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('posts.analytics-detail', compact('post', 'auditLogs'));
    }
}
