<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                LinkedIn App Credentials
            </h2>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                    <!-- Redirect URI Setup Instructions -->
                    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-4 text-blue-900">
                        <h3 class="font-semibold mb-3 flex items-center gap-2">
                            <span class="text-lg">📋</span>
                            <span>LinkedIn Setup Instructions</span>
                        </h3>
                        
                        <ol class="list-decimal list-inside space-y-2 text-sm mb-4 text-blue-800">
                            <li>Go to <a href="https://www.linkedin.com/developers/apps" target="_blank" class="underline font-semibold hover:text-blue-700">LinkedIn Developer Portal</a></li>
                            <li>Select your app → Click <strong>"Auth"</strong> tab</li>
                            <li>Scroll to <strong>"Authorized redirect URLs for your app"</strong></li>
                            <li>Click <strong>"Add redirect URL"</strong></li>
                            <li>Copy the URL below and paste it in LinkedIn:</li>
                        </ol>

                        <!-- Copy-only Redirect URI Field -->
                        <div class="flex gap-2 items-stretch bg-white p-3 rounded">
                            <input type="text" id="redirect_uri_copy" value="{{ $redirectUri }}" readonly
                                   class="flex-1 px-3 py-2 rounded-md border border-gray-300 bg-gray-50 text-gray-900 font-mono text-sm focus:outline-none" />
                            <button type="button" onclick="copyToClipboard()" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-md transition">
                                📋 Copy
                            </button>
                        </div>
                        
                        <p class="mt-3 text-xs text-blue-700">
                            ✓ Your app will automatically redirect here after LinkedIn authentication
                        </p>
                    </div>

                    <form method="POST" action="{{ route('linkedin.credentials.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')
  <input type="text" name="fake_user" style="display:none;" aria-hidden="true" />
    <input type="password" name="fake_pass" style="display:none;" aria-hidden="true" />

                        <div>
                            <x-input-label for="client_id" value="Client ID" />
                            <x-text-input id="client_id" name="client_id" type="text" class="mt-1 block w-full"
                                          value="{{ old('client_id') }}" placeholder="{{ $credential?->exists ? 'Saved (leave blank to keep)' : '' }}" autocomplete="off" />
                            <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500">Paste from LinkedIn Developer Portal → My Apps → Auth.</p>
                        </div>

                        <div>
                            <x-input-label for="client_secret" value="Client Secret" />
                            <x-text-input id="client_secret" name="client_secret" type="password" class="mt-1 block w-full"
                                          value="{{ old('client_secret') }}" placeholder="{{ $credential?->exists ? 'Saved (leave blank to keep)' : '' }}"  autocomplete="off"/>
                            <x-input-error :messages="$errors->get('client_secret')" class="mt-2" />
                        </div>

                        <!-- Hidden Redirect URI (auto-filled from current app URL) -->
                        <input type="hidden" id="redirect_uri" name="redirect_uri" value="{{ $redirectUri }}" />

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('linkedin.connect') }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition" style="display:none;">
                                Connect (Basic)
                            </a>
                            <a href="{{ route('linkedin.connect', ['posting' => 1]) }}"
                               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                                Connect (Posting)
                            </a>
                            <x-primary-button>Save</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function copyToClipboard() {
    const input = document.getElementById('redirect_uri_copy');
    input.select();
    document.execCommand('copy');
    
    // Show success feedback
    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = '✓ Copied!';
    btn.classList.add('bg-green-600', 'hover:bg-green-700');
    btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    
    setTimeout(() => {
        btn.textContent = originalText;
        btn.classList.remove('bg-green-600', 'hover:bg-green-700');
        btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }, 2000);
}
</script>

