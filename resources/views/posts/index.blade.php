<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    LinkedIn Post Automation
                </h2>
                <div class="text-sm text-gray-500">Draft → queued → sent / failed</div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('linkedin.credentials.edit') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                    LinkedIn Credentials
                </a>
                <a href="{{ route('linkedin.connect') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition" style="display: none;">
                    Connect (Basic)
                    
                </a>
                <a href="{{ route('linkedin.connect', ['posting' => 1]) }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                    Connect (Posting)
                </a>
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    Create draft
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-xs uppercase tracking-wider text-gray-500 border-b">
                            <tr>
                                <th class="py-3 pr-3">ID</th>
                                <th class="py-3 pr-3">Status</th>
                                <th class="py-3 pr-3">Scheduled</th>
                                <th class="py-3 pr-3">Sent</th>
                                <th class="py-3 pr-3">Text</th>
                                <th class="py-3 pr-3">Link</th>
                                <th class="py-3 pr-3">LinkedIn URN</th>
                                <th class="py-3 pr-3">Error</th>
                                <th class="py-3 pr-3">Action</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y">
                            @forelse($posts as $post)
                                <tr class="align-top">
                                    <td class="py-3 pr-3 font-mono text-xs text-gray-700">#{{ $post->id }}</td>
                                    <td class="py-3 pr-3">
                                        @php
                                            $badge = match($post->status) {
                                                'sent' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                                'failed' => 'bg-rose-50 text-rose-700 ring-rose-200',
                                                'queued' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                                default => 'bg-gray-50 text-gray-700 ring-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                                            {{ $post->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-3 text-gray-500 whitespace-nowrap">{{ optional($post->scheduled_for)->toDateTimeString() }}</td>
                                    <td class="py-3 pr-3 text-gray-500 whitespace-nowrap">{{ optional($post->sent_at)->toDateTimeString() }}</td>
                                    <td class="py-3 pr-3 max-w-[520px] whitespace-pre-wrap">{{ $post->text }}</td>
                                    <td class="py-3 pr-3 text-gray-600 break-all">{{ $post->link_url }}</td>
                                    <td class="py-3 pr-3 font-mono text-xs text-gray-600 break-all">{{ $post->linkedin_urn }}</td>
                                    <td class="py-3 pr-3 max-w-[380px] whitespace-pre-wrap text-rose-700">{{ $post->last_error }}</td>
                                   <td class="py-3 pr-3">
    @if($post->status === 'draft')
        <div class="flex items-center gap-2">
          <a href="{{ route('posts.edit', $post) }}"
   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg shadow-sm transition"
   style="background-color:#2563eb; color:#ffffff;">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 113.536 3.536L12.536 14.536a2 2 0 01-.878.513l-4 1a1 1 0 01-1.213-1.213l1-4a2 2 0 01.513-.878L14.232 6.232z"/>
    </svg>
    Edit
</a>

            <form method="POST" action="{{ route('posts.publish', $post) }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg shadow-sm transition"
                        style="background-color:#16a34a; color:#ffffff;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#ffffff;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Publish
                </button>
            </form>
        </div>
    @else
        <span style="color:#6b7280;">No Actions</span>
    @endif
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-6 text-gray-500">No posts yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

               
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

