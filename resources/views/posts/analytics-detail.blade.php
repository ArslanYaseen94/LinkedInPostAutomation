@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <a href="{{ route('analytics.dashboard') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Analytics</a>
                </div>

                <h2 class="text-2xl font-bold mb-6">Post Details & History</h2>

                <!-- Post Content -->
                <div class="bg-gray-50 p-6 rounded-lg mb-8 border border-gray-200">
                    <h3 class="font-semibold text-lg mb-3">Post Content</h3>
                    <p class="text-gray-700 mb-4">{{ $post->text }}</p>
                    @if($post->link_url)
                        <p class="text-sm text-gray-600"><strong>Link:</strong> <a href="{{ $post->link_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $post->link_url }}</a></p>
                    @endif
                </div>

                <!-- Performance Metrics -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-xs text-gray-600 mb-1">Impressions</div>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($post->impressions) }}</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <div class="text-xs text-gray-600 mb-1">Reactions</div>
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($post->reactions) }}</div>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg border border-pink-200">
                        <div class="text-xs text-gray-600 mb-1">Comments</div>
                        <div class="text-2xl font-bold text-pink-600">{{ number_format($post->comments) }}</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="text-xs text-gray-600 mb-1">Clicks</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ number_format($post->clicks) }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="text-xs text-gray-600 mb-1">Engagement %</div>
                        <div class="text-2xl font-bold text-green-600">{{ number_format($post->getEngagementRate(), 2) }}%</div>
                    </div>
                </div>

                <!-- Status & Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Status</div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium @if($post->status === 'sent') bg-green-100 text-green-800 @elseif($post->status === 'failed') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($post->status) }}
                        </span>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Published At</div>
                        <div class="font-medium">{{ $post->sent_at ? $post->sent_at->format('M d, Y H:i') : 'Not published' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">LinkedIn URN</div>
                        <div class="font-mono text-sm">{{ $post->linkedin_urn ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Retry Count</div>
                        <div class="font-medium">{{ $post->retry_count }} / {{ $post->max_retries }}</div>
                    </div>
                </div>

                @if($post->last_error)
                    <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-8">
                        <div class="text-sm font-semibold text-red-800 mb-1">Last Error</div>
                        <div class="text-sm text-red-700">{{ $post->last_error }}</div>
                    </div>
                @endif

                <!-- Audit Log Timeline -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">📋 Status History</h3>
                    <div class="space-y-4">
                        @forelse($auditLogs as $log)
                            <div class="border-l-4 @if($log->new_status === 'sent') border-green-500 @elseif($log->new_status === 'failed') border-red-500 @else border-blue-500 @endif pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium">
                                            {{ ucfirst($log->previous_status) }}
                                            <span class="text-gray-500 mx-2">→</span>
                                            <span class="@if($log->new_status === 'sent') text-green-600 @elseif($log->new_status === 'failed') text-red-600 @else text-blue-600 @endif font-semibold">{{ ucfirst($log->new_status) }}</span>
                                        </p>
                                        @if($log->reason)
                                            <p class="text-sm text-gray-600 mt-1">{{ $log->reason }}</p>
                                        @endif
                                        @if($log->error_details)
                                            <p class="text-sm text-red-600 mt-1 font-mono">Error: {{ $log->error_details }}</p>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 whitespace-nowrap ml-4">
                                        {{ $log->created_at->format('M d, H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No status changes recorded.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Retry Button -->
                @if($post->canRetry())
                    <div class="mt-8 pt-6 border-t">
                        <form action="{{ route('posts.retry', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                🔄 Retry Publishing
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
