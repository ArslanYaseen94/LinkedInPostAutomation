<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ✨ Create New Template
            </h2>
            <a href="{{ route('templates.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">
                    @if(isset($template))
                        Edit Template
                    @else
                        Create New Template
                    @endif
                </h2>

                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="@if(isset($template)) {{ route('templates.update', $template) }} @else {{ route('templates.store') }} @endif" method="POST">
                    @csrf
                    @if(isset($template))
                        @method('PUT')
                    @endif

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $template->name ?? '') }}" 
                               placeholder="e.g., Weekly Wins, Product Launch" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                        <input type="text" id="description" name="description" value="{{ old('description', $template->description ?? '') }}" 
                               placeholder="What is this template for?" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Template Content
                            <span class="text-xs text-gray-500">(max 3000 chars)</span>
                        </label>
                        <textarea id="content" name="content" rows="10" 
                                  placeholder="Enter your post template here. You can use this as a starting point and edit it when using."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 font-mono text-sm"
                                  required>{{ old('content', $template->content ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-2" id="charCount">0 / 3000</p>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded">
                            @if(isset($template))
                                Update Template
                            @else
                                Create Template
                            @endif
                        </button>
                        <a href="{{ route('templates.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('content').addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length + ' / 3000';
    });
    
    // Initialize on load
    document.getElementById('charCount').textContent = document.getElementById('content').value.length + ' / 3000';
</script>
</x-app-layout>
