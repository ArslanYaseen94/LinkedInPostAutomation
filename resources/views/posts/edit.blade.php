<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit draft #{{ $post->id }}
            </h2>
            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('posts.update', $post) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="text" value="Text" />
                            <textarea id="text" name="text" rows="7" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('text', $post->text) }}</textarea>
                            <x-input-error :messages="$errors->get('text')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image" value="Image (optional)" />
                            @if($post->image_path)
                                <div class="mt-2 mb-3">
                                    <div class="text-sm text-gray-600 mb-2">Current image:</div>
                                    <img src="{{ asset($post->image_path) }}" alt="{{ $post->image_alt_text }}" class="max-w-xs rounded-md border border-gray-300">
                                </div>
                            @endif
                            <input id="image" type="file" name="image" accept="image/*" 
                                   class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500">Supported: JPG, PNG, GIF (max 5MB)</p>
                        </div>

                        <div>
                            <x-input-label for="image_alt_text" value="Image Alt Text" />
                            <x-text-input id="image_alt_text" name="image_alt_text" type="text" class="mt-1 block w-full"
                                          value="{{ old('image_alt_text', $post->image_alt_text) }}" placeholder="Describe the image..." />
                            <x-input-error :messages="$errors->get('image_alt_text')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="video" value="Video (optional)" />
                            @if($post->video_path)
                                <div class="mt-2 mb-3">
                                    <div class="text-sm text-gray-600 mb-2">Current video:</div>
                                    <video src="{{ asset($post->video_path) }}" controls class="max-w-xs rounded-md border border-gray-300"></video>
                                </div>
                            @endif
                            <input id="video" type="file" name="video" accept="video/*" 
                                   class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            <x-input-error :messages="$errors->get('video')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500">Supported: MP4, WebM, Ogg (max 100MB)</p>
                        </div>

                        <div>
                            <x-input-label for="video_alt_text" value="Video Alt Text" />
                            <x-text-input id="video_alt_text" name="video_alt_text" type="text" class="mt-1 block w-full"
                                          value="{{ old('video_alt_text', $post->video_alt_text) }}" placeholder="Describe the video..." />
                            <x-input-error :messages="$errors->get('video_alt_text')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="link_url" value="Link URL (optional)" />
                            <x-text-input id="link_url" name="link_url" type="url" class="mt-1 block w-full"
                                          value="{{ old('link_url', $post->link_url) }}" placeholder="https://..." />
                            <x-input-error :messages="$errors->get('link_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="scheduled_for" value="Schedule for (optional)" />
                            <x-text-input id="scheduled_for" name="scheduled_for" type="datetime-local" class="mt-1 block w-full"
                                          value="{{ old('scheduled_for', optional($post->scheduled_for)->format('Y-m-d\TH:i')) }}" />
                            <x-input-error :messages="$errors->get('scheduled_for')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500">
                                If set, scheduler will queue it when due.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('posts.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                Cancel
                            </a>
                            <x-primary-button>Update draft</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
