@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">📋 Post Templates</h2>
                    <a href="{{ route('templates.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        + New Template
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($templates as $template)
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-6 rounded-lg border border-indigo-200 hover:shadow-lg transition">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $template->name }}</h3>
                            @if($template->description)
                                <p class="text-sm text-gray-600 mb-3">{{ $template->description }}</p>
                            @endif
                            <div class="bg-white p-3 rounded mb-4 text-sm text-gray-700 line-clamp-3">
                                {{ $template->content }}
                            </div>
                            <div class="text-xs text-gray-500 mb-4">
                                Used {{ $template->usage_count }} times
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('templates.use', $template) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded text-sm">
                                        Use Template
                                    </button>
                                </form>
                                <a href="{{ route('templates.edit', $template) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded text-sm text-center">
                                    Edit
                                </a>
                                <form action="{{ route('templates.destroy', $template) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete template?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 mb-4">No templates yet. Create one to save time on recurring posts!</p>
                            <a href="{{ route('templates.create') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded">
                                Create First Template
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $templates->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
