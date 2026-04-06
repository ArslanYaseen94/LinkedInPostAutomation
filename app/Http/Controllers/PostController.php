<?php

namespace App\Http\Controllers;

use App\Jobs\PublishLinkedInPost;
use App\Models\Post;
use App\Models\PostTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $query = Post::query()
            ->where('user_id', Auth::id())
            ->orderByDesc('id');

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $posts = $query->paginate(20);

        return view('posts.index-enhanced', compact('posts'));
    }

    public function create(): View
    {
        $templates = PostTemplate::where('user_id', Auth::id())
            ->orderByDesc('usage_count')
            ->limit(6)
            ->get();

        return view('posts.create-enhanced', compact('templates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'text' => ['required', 'string', 'max:3000'],
            'link_url' => ['nullable', 'url', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif', 'max:5120'],
            'image_alt_text' => ['nullable', 'string', 'max:500'],
            'video' => ['nullable', 'mimes:mp4,webm,ogg', 'max:102400'],
            'video_alt_text' => ['nullable', 'string', 'max:500'],
            'scheduled_for' => ['nullable', 'date'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('LPA'), $filename);
            $imagePath = 'LPA/' . $filename;
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('LPA'), $filename);
            $videoPath = 'LPA/' . $filename;
        }

        $post = Post::query()->create([
            'user_id' => Auth::id(),
            'text' => $data['text'],
            'link_url' => $data['link_url'] ?? null,
            'image_path' => $imagePath,
            'image_alt_text' => $data['image_alt_text'] ?? null,
            'video_path' => $videoPath,
            'video_alt_text' => $data['video_alt_text'] ?? null,
            'scheduled_for' => $data['scheduled_for'] ?? null,
            'status' => 'draft',
        ]);

        return redirect()->route('posts.index')->with('status', "Draft created (#{$post->id}).");
    }

    public function edit(Post $post): View
    {
        abort_unless($post->user_id === Auth::id(), 403);
        abort_unless($post->status === 'draft', 403);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === Auth::id(), 403);
        abort_unless($post->status === 'draft', 403);

        $data = $request->validate([
            'text' => ['required', 'string', 'max:3000'],
            'link_url' => ['nullable', 'url', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif', 'max:5120'],
            'image_alt_text' => ['nullable', 'string', 'max:500'],
            'video' => ['nullable', 'mimes:mp4,webm,ogg', 'max:102400'],
            'video_alt_text' => ['nullable', 'string', 'max:500'],
            'scheduled_for' => ['nullable', 'date'],
        ]);

        $imagePath = $post->image_path;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('LPA'), $filename);
            $imagePath = 'LPA/' . $filename;
        }

        $videoPath = $post->video_path;
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('LPA'), $filename);
            $videoPath = 'LPA/' . $filename;
        }

        $post->update([
            'text' => $data['text'],
            'link_url' => $data['link_url'] ?? null,
            'image_path' => $imagePath,
            'image_alt_text' => $data['image_alt_text'] ?? null,
            'video_path' => $videoPath,
            'video_alt_text' => $data['video_alt_text'] ?? null,
            'scheduled_for' => $data['scheduled_for'] ?? null,
        ]);

        return redirect()->route('posts.index')->with('status', "Draft updated (#{$post->id}).");
    }

    public function publish(Post $post): RedirectResponse
    {
        abort_unless($post->user_id === Auth::id(), 403);
        abort_unless($post->status === 'draft', 403);

        $post->forceFill([
            'status' => 'queued',
            'last_error' => null,
        ])->save();

        PublishLinkedInPost::dispatch($post->id);

        return redirect()->route('posts.index')->with('status', "Queued post (#{$post->id}).");
    }
}

