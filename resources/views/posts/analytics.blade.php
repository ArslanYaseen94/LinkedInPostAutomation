@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">📊 Post Analytics Dashboard</h2>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
                        <div class="text-sm text-gray-600 mb-2">Total Posts Published</div>
                        <div class="text-3xl font-bold text-blue-600">{{ $stats['total_posts'] }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200">
                        <div class="text-sm text-gray-600 mb-2">Total Impressions</div>
                        <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_impressions']) }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-pink-50 to-pink-100 p-6 rounded-lg border border-pink-200">
                        <div class="text-sm text-gray-600 mb-2">Total Reactions</div>
                        <div class="text-3xl font-bold text-pink-600">{{ number_format($stats['total_reactions']) }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200">
                        <div class="text-sm text-gray-600 mb-2">Total Comments</div>
                        <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_comments']) }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-lg border border-yellow-200">
                        <div class="text-sm text-gray-600 mb-2">Total Clicks</div>
                        <div class="text-3xl font-bold text-yellow-600">{{ number_format($stats['total_clicks']) }}</div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-lg border border-orange-200">
                        <div class="text-sm text-gray-600 mb-2">Avg Engagement Rate</div>
                        <div class="text-3xl font-bold text-orange-600">{{ number_format($stats['average_engagement_rate'], 2) }}%</div>
                    </div>
                </div>

                <!-- Top Post -->
                @if($stats['top_post'])
                <div class="mb-8 bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-lg border border-indigo-200">
                    <h3 class="text-lg font-semibold mb-3">🏆 Your Best Performing Post</h3>
                    <p class="text-gray-700 mb-3 line-clamp-2">{{ $stats['top_post']->text }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Impressions:</span>
                            <span class="font-semibold">{{ number_format($stats['top_post']->impressions) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Reactions:</span>
                            <span class="font-semibold">{{ number_format($stats['top_post']->reactions) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Comments:</span>
                            <span class="font-semibold">{{ number_format($stats['top_post']->comments) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Engagement Rate:</span>
                            <span class="font-semibold">{{ number_format($stats['top_post']->getEngagementRate(), 2) }}%</span>
                        </div>
                    </div>
                    <a href="{{ route('analytics.detail', $stats['top_post']) }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-900 font-medium">
                        View Details →
                    </a>
                </div>
                @endif

                <!-- Posts Table -->
                <div class="overflow-x-auto">
                    <h3 class="text-lg font-semibold mb-4">📈 All Posts Performance</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Impressions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reactions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comments</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Clicks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Engagement %</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($posts as $post)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 line-clamp-1">{{ Str::limit($post->text, 50) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium">{{ number_format($post->impressions) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium">{{ number_format($post->reactions) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium">{{ number_format($post->comments) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium">{{ number_format($post->clicks) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ number_format($post->getEngagementRate(), 2) }}%
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('analytics.detail', $post) }}" class="text-indigo-600 hover:text-indigo-900">Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No published posts yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
