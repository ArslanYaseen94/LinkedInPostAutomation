@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Posts</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Retry History for Post #{{ $post->id }}</h2>

                <!-- Post Summary -->
                <div class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="font-semibold text-gray-900 mb-2">Post Content</h3>
                    <p class="text-gray-700 line-clamp-3">{{ $post->text }}</p>
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($post->status) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Retries:</span>
                            <span class="font-semibold text-gray-900">{{ $post->retry_count }}/{{ $post->max_retries }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Can Retry:</span>
                            <span class="font-semibold @if($post->canRetry()) text-green-600 @else text-red-600 @endif">
                                @if($post->canRetry()) Yes @else No @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <span class="font-semibold text-gray-900">{{ $post->created_at->format('M d, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <h3 class="text-lg font-semibold text-gray-900 mb-6">📋 Complete Status Timeline</h3>
                <div class="space-y-6">
                    @forelse($auditLogs as $log)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center @if($log->new_status === 'sent') bg-green-100 @elseif($log->new_status === 'failed') bg-red-100 @elseif($log->new_status === 'queued') bg-yellow-100 @else bg-blue-100 @endif">
                                    @if($log->new_status === 'sent')
                                        <span class="text-lg">✅</span>
                                    @elseif($log->new_status === 'failed')
                                        <span class="text-lg">❌</span>
                                    @elseif($log->new_status === 'queued')
                                        <span class="text-lg">⏳</span>
                                    @else
                                        <span class="text-lg">📝</span>
                                    @endif
                                </div>
                                @if(!$loop->last)
                                    <div class="w-1 h-12 bg-gray-300 mt-2"></div>
                                @endif
                            </div>

                            <div class="flex-1 pb-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">
                                            {{ ucfirst($log->previous_status) }} → {{ ucfirst($log->new_status) }}
                                        </h4>
                                        @if($log->reason)
                                            <p class="text-sm text-gray-600 mt-1">{{ $log->reason }}</p>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 whitespace-nowrap ml-4">
                                        {{ $log->created_at->format('M d, Y H:i:s') }}
                                    </div>
                                </div>

                                @if($log->error_details)
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-xs font-mono text-red-700">
                                            <strong>Error:</strong> {{ $log->error_details }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500">No status history available</p>
                        </div>
                    @endforelse
                </div>

                <!-- Retry Action -->
                @if($post->canRetry())
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-4">Retry Publishing</h3>
                        <p class="text-gray-600 mb-4">
                            This post can be retried. It has failed {{ $post->retry_count }} of {{ $post->max_retries }} attempts.
                        </p>
                        <form action="{{ route('posts.retry', $post) }}" method="POST" onsubmit="return confirm('Retry publishing this post?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.007a9 9 0 015.97 2.179l1.414-1.414A10.98 10.98 0 005.007 9H9V4M20 20v-5h-.007a9 9 0 00-5.97-2.179l-1.414 1.414A10.98 10.98 0 0018.993 15H15v5m5-5v5h.007"/>
                                </svg>
                                Retry Publishing Now
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-8 pt-8 border-t border-gray-200 bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                        <p class="text-yellow-800">
                            <strong>⚠️ Cannot Retry:</strong> This post has reached the maximum number of retry attempts ({{ $post->max_retries }}) or is not in a failed state.
                        </p>
                    </div>
                @endif

                <!-- Return Link -->
                <div class="mt-8">
                    <a href="{{ route('analytics.detail', $post) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                        View Full Analytics →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
