<?php

namespace App\Http\Controllers;

use App\Models\LinkedInAppCredential;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LinkedInCredentialController extends Controller
{
    public function edit(): View
    {
        $credential = LinkedInAppCredential::query()->where('user_id', Auth::id())->first();
        
        // Auto-generate redirect URI from current app URL
        $redirectUri = $credential?->redirect_uri ?? (config('app.url') . '/linkedin/callback');

        return view('linkedin.credentials', compact('credential', 'redirectUri'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // Allow blank to keep existing saved values.
            'client_id' => ['nullable', 'string', 'max:255'],
            'client_secret' => ['nullable', 'string', 'max:255'],
            'redirect_uri' => ['required', 'url', 'max:2000'],
        ]);

        $existing = LinkedInAppCredential::query()->where('user_id', Auth::id())->first();

        $clientId = isset($data['client_id']) && $data['client_id'] !== '' ? $data['client_id'] : ($existing?->client_id);
        $clientSecret = isset($data['client_secret']) && $data['client_secret'] !== '' ? $data['client_secret'] : ($existing?->client_secret);

        if (! is_string($clientId) || trim($clientId) === '' || ! is_string($clientSecret) || trim($clientSecret) === '') {
            return Redirect::route('linkedin.credentials.edit')
                ->with('error', 'Client ID and Client Secret are required (enter them at least once).');
        }

        LinkedInAppCredential::query()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $data['redirect_uri'],
            ],
        );

        return Redirect::route('linkedin.credentials.edit')->with('status', 'LinkedIn app credentials saved.');
    }
}

