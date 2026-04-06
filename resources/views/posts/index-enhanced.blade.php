@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/>
                        </svg>
                        My Posts
                    </h1>
                    <p class="text-gray-500 mt-1">Draft → Queued → Sent / Failed</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Templates
                    </a>
                    <a href="{{ route('analytics.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analytics
                    </a>
                    <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Post
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="mb-6 flex gap-2 border-b">
            <a href="{{ route('posts.index') }}" class="px-4 py-2 border-b-2 @if(!request('status')) border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 @endif font-medium hover:text-indigo-600">
                All Posts
            </a>
            <a href="{{ route('posts.index', ['status' => 'draft']) }}" class="px-4 py-2 border-b-2 @if(request('status') === 'draft') border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 @endif font-medium hover:text-indigo-600">
                📝 Drafts
            </a>
            <a href="{{ route('posts.index', ['status' => 'queued']) }}" class="px-4 py-2 border-b-2 @if(request('status') === 'queued') border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 @endif font-medium hover:text-indigo-600">
                ⏳ Queued
            </a>
            <a href="{{ route('posts.index', ['status' => 'sent']) }}" class="px-4 py-2 border-b-2 @if(request('status') === 'sent') border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 @endif font-medium hover:text-indigo-600">
                ✅ Sent
            </a>
            <a href="{{ route('posts.index', ['status' => 'failed']) }}" class="px-4 py-2 border-b-2 @if(request('status') === 'failed') border-indigo-600 text-indigo-600 @else border-transparent text-gray-600 @endif font-medium hover:text-indigo-600">
                ❌ Failed
            </a>
        </div>

        <!-- Posts Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-xs uppercase tracking-wider text-gray-600 font-semibold">
                            <th class="px-6 py-4">Post</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Scheduled</th>
                            <th class="px-6 py-4">Published</th>
                            <th class="px-6 py-4">Retries</th>
                            <th class="px-6 py-4">Analytics</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($posts as $post)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <p class="text-sm font-medium text-gray-900 line-clamp-2">{{ $post->text }}</p>
                                        <p class="text-xs text-gray-500 mt-1">#{{ $post->id }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'sent' => 'bg-green-100 text-green-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'queued' => 'bg-yellow-100 text-yellow-800',
                                            'draft' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $colors = $statusColors[$post->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $colors }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $post->scheduled_for ? $post->scheduled_for->format('M d, H:i') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $post->sent_at ? $post->sent_at->format('M d, H:i') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="text-gray-600">{{ $post->retry_count }}/{{ $post->max_retries }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($post->status === 'sent')
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="text-purple-600 font-medium">{{ number_format($post->impressions) }}</span>
                                            <span class="text-gray-400">|</span>
                                            <span class="text-pink-600 font-medium">{{ number_format($post->reactions) }}</span>
                                            <span class="text-gray-400">|</span>
                                            <span class="text-blue-600 font-medium">{{ number_format($post->comments) }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($post->status === 'draft')
                                            <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-medium">
                                                ✏️ Edit
                                            </a>
                                            <form action="{{ route('posts.publish', $post) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition text-xs font-medium">
                                                    ▶️ Publish
                                                </button>
                                            </form>
                                        @elseif($post->canRetry())
                                            <a href="{{ route('posts.retry-history', $post) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition text-xs font-medium">
                                                📋 History
                                            </a>
                                            <form action="{{ route('posts.retry', $post) }}" method="POST" class="inline" onsubmit="return confirm('Retry publishing this post?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-700 rounded hover:bg-orange-200 transition text-xs font-medium">
                                                    🔄 Retry
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('analytics.detail', $post) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition text-xs font-medium">
                                            📊 Details
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 mb-4">No posts yet. Create your first post to get started!</p>
                                    <a href="{{ route('posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Create New Post
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
