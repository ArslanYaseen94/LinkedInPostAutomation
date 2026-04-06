@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">✏️ Create New Post</h1>
            <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Posts</a>
        </div>

        <!-- Quick Start with Templates -->
        <div class="mb-8 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg p-6">
            <h3 class="font-semibold text-gray-900 mb-4">📋 Quick Start with Templates</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @if($templates->count() > 0)
                    @foreach($templates->take(3) as $template)
                        <div class="bg-white p-4 rounded-lg border border-purple-200 hover:shadow-md transition group">
                            <p class="font-medium text-sm text-gray-900 mb-2">{{ $template->name }}</p>
                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $template->content }}</p>
                            <button type="button" onclick="loadTemplate(`{{ addslashes($template->content) }}`)" class="w-full px-3 py-1 bg-purple-600 text-white rounded text-xs hover:bg-purple-700 transition">
                                Use Template
                            </button>
                        </div>
                    @endforeach
                @endif
                <a href="{{ route('templates.index') }}" class="bg-white p-4 rounded-lg border-2 border-dashed border-purple-300 hover:bg-purple-50 transition flex items-center justify-center">
                    <span class="text-center">
                        <p class="text-2xl mb-1">+</p>
                        <p class="text-sm font-medium text-gray-700">View All Templates</p>
                    </span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('posts.store') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <!-- Post Content -->
                    <div>
                        <label for="text" class="block text-sm font-semibold text-gray-900 mb-2">
                            Post Content <span class="text-red-500">*</span>
                        </label>
                        <textarea id="text" name="text" rows="8" required placeholder="What do you want to share with your LinkedIn network?"
                                  class="@if($errors->has('text')) border-red-500 @else border-gray-300 @endif w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition resize-none">{{ old('text') }}</textarea>
                        <div class="mt-2 flex justify-between text-xs text-gray-500">
                            <span id="charCount">0 characters</span>
                            <span>Max: 3000 characters</span>
                        </div>
                        @if($errors->has('text'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('text') }}</p>
                        @endif
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-900 mb-2">Image (Optional)</label>
                        <div id="imageDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 transition cursor-pointer bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-600 font-medium">Drag image here or click to select</p>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (max 5MB)</p>
                            <input id="image" type="file" name="image" accept="image/*" class="hidden"/>
                        </div>
                        <div id="imagePreview" class="mt-4 hidden">
                            <img id="previewImg" src="" alt="Preview" class="max-h-48 rounded-lg">
                        </div>
                        @if($errors->has('image'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('image') }}</p>
                        @endif
                    </div>

                    <!-- Image Alt Text -->
                    <div>
                        <label for="image_alt_text" class="block text-sm font-semibold text-gray-900 mb-2">Image Alt Text</label>
                        <input id="image_alt_text" type="text" name="image_alt_text" placeholder="Describe the image for accessibility..."
                               value="{{ old('image_alt_text') }}"
                               class="@if($errors->has('image_alt_text')) border-red-500 @else border-gray-300 @endif w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"/>
                        @if($errors->has('image_alt_text'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('image_alt_text') }}</p>
                        @endif
                    </div>

                    <!-- URL -->
                    <div>
                        <label for="link_url" class="block text-sm font-semibold text-gray-900 mb-2">Link URL (Optional)</label>
                        <input id="link_url" type="url" name="link_url" placeholder="https://example.com"
                               value="{{ old('link_url') }}"
                               class="@if($errors->has('link_url')) border-red-500 @else border-gray-300 @endif w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"/>
                        @if($errors->has('link_url'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('link_url') }}</p>
                        @endif
                    </div>

                    <!-- Schedule -->
                    <div>
                        <label for="scheduled_for" class="block text-sm font-semibold text-gray-900 mb-2">Schedule For (Optional)</label>
                        <input id="scheduled_for" type="datetime-local" name="scheduled_for"
                               value="{{ old('scheduled_for') }}"
                               class="@if($errors->has('scheduled_for')) border-red-500 @else border-gray-300 @endif w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"/>
                        <p class="mt-2 text-xs text-gray-500">Post will be queued and published at the scheduled time</p>
                        @if($errors->has('scheduled_for'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->first('scheduled_for') }}</p>
                        @endif
                    </div>

                    <!-- Save Options -->
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-4">Choose how to save your post:</p>
                        <div class="flex gap-4 flex-wrap">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save as Draft
                            </button>

                            <button type="button" onclick="saveAsTemplate()" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Save as Template
                            </button>

                            <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Template Save Modal -->
<div id="templateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Save as Template</h3>
        <form id="templateForm" method="POST" action="{{ route('templates.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" id="templateContent" name="content">
            <div>
                <label for="templateName" class="block text-sm font-medium text-gray-700 mb-1">Template Name</label>
                <input type="text" id="templateName" name="name" placeholder="e.g., Weekly Update" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"/>
            </div>
            <div>
                <label for="templateDescription" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                <input type="text" id="templateDescription" name="description" placeholder="What is this template for?"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"/>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeTemplateModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Save Template
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Character counter
    document.getElementById('text').addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length + ' characters';
    });

    // Image preview
    document.getElementById('imageDropZone').addEventListener('click', function() {
        document.getElementById('image').click();
    });

    document.getElementById('imageDropZone').addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-indigo-500', 'bg-indigo-50');
    });

    document.getElementById('imageDropZone').addEventListener('dragleave', function() {
        this.classList.remove('border-indigo-500', 'bg-indigo-50');
    });

    document.getElementById('imageDropZone').addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-indigo-500', 'bg-indigo-50');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('image').files = files;
            previewImage();
        }
    });

    document.getElementById('image').addEventListener('change', previewImage);

    function previewImage() {
        const file = document.getElementById('image').files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                const img = document.getElementById('previewImg');
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    // Load template
    function loadTemplate(content) {
        document.getElementById('text').value = content;
        document.getElementById('charCount').textContent = content.length + ' characters';
        document.getElementById('text').focus();
    }

    // Save template
    function saveAsTemplate() {
        const content = document.getElementById('text').value;
        if (!content.trim()) {
            alert('Please write some content first');
            return;
        }
        document.getElementById('templateContent').value = content;
        document.getElementById('templateModal').classList.remove('hidden');
    }

    function closeTemplateModal() {
        document.getElementById('templateModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('templateModal').addEventListener('click', function(e) {
        if (e.target === this) closeTemplateModal();
    });

    // Initialize char count
    document.getElementById('charCount').textContent = document.getElementById('text').value.length + ' characters';
</script>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
