<!-- Navigation Sidebar Component -->
<div class="w-64 bg-gradient-to-b from-indigo-600 to-indigo-800 text-white rounded-lg p-6 mb-6">
    <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M8 4v16h12V4H8zm1 2h2v2H9V6zm4 0h2v2h-2V6zm4 0h2v2h-2V6zm-8 4h2v2H9v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2zm-8 4h2v2H9v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2zm-8 4h2v2H9v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
        </svg>
        LinkedIn Automation
    </h3>

    <nav class="space-y-3">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo-700 transition @if(Route::currentRouteName() === 'dashboard') bg-indigo-700 @endif">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m2 0l7-4 7 4M5 10v10a1 1 0 001 1h12a1 1 0 001-1V10M9 21h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('posts.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo-700 transition @if(Route::currentRouteName() === 'posts.index') bg-indigo-700 @endif">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span>My Posts</span>
        </a>

        <a href="{{ route('analytics.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo-700 transition @if(Route::currentRouteName() === 'analytics.dashboard') bg-indigo-700 @endif">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>📊 Analytics</span>
        </a>

        <a href="{{ route('templates.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo-700 transition @if(Route::currentRouteName() === 'templates.index') bg-indigo-700 @endif">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span>📋 Templates</span>
        </a>

        <div class="border-t border-indigo-500 my-3"></div>

        <a href="{{ route('posts.create') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg bg-green-500 hover:bg-green-600 transition font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>✏️ New Post</span>
        </a>
    </nav>

    <div class="border-t border-indigo-500 mt-6 pt-6">
        <a href="{{ route('linkedin.credentials.edit') }}" 
           class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Settings</span>
        </a>
    </div>
</div>
