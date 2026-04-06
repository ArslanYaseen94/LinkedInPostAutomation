<?php

namespace App\Http\Controllers;

use App\Models\LinkedInAppCredential;
use App\Models\LinkedInAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class LinkedInAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $app = LinkedInAppCredential::query()->where('user_id', Auth::id())->first();

        if (! $app) {
            return Redirect::route('linkedin.credentials.edit')
                ->with('status', 'Add your LinkedIn app Client ID/Secret first.');
        }

        config()->set('services.linkedin.client_id', $app->client_id);
        config()->set('services.linkedin.client_secret', $app->client_secret);
        config()->set('services.linkedin.redirect', $app->redirect_uri);

        // Request only the posting scope needed for Share on LinkedIn
        $scopes = ['w_member_social'];

        return Socialite::driver('linkedin')
            // Request w_member_social scope for posting to LinkedIn
            ->scopes($scopes)
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            $error = (string) $request->query('error');
            $description = (string) $request->query('error_description', '');

            $message = trim("LinkedIn connect failed: {$error}. {$description}");

            return Redirect::route('linkedin.credentials.edit')->with('error', $message);
        }

        $app = LinkedInAppCredential::query()->where('user_id', Auth::id())->first();

        if (! $app) {
            return Redirect::route('linkedin.credentials.edit')
                ->with('status', 'Add your LinkedIn app Client ID/Secret first.');
        }

        config()->set('services.linkedin.client_id', $app->client_id);
        config()->set('services.linkedin.client_secret', $app->client_secret);
        config()->set('services.linkedin.redirect', $app->redirect_uri);

        try {
            $socialiteUser = Socialite::driver('linkedin')->user();
        } catch (InvalidStateException $e) {
            return Redirect::route('linkedin.credentials.edit')
                ->with('error', 'LinkedIn connect failed: invalid state. Please click "Connect LinkedIn" again.');
        } catch (\Throwable $e) {
            return Redirect::route('linkedin.credentials.edit')
                ->with('error', 'LinkedIn connect failed: '.$e->getMessage());
        }

        $memberId = $socialiteUser->getId();
        $personUrn = $memberId ? "urn:li:person:{$memberId}" : null;

        $account = LinkedInAccount::query()->firstOrNew(['user_id' => Auth::id()]);

        $account->forceFill([
            'user_id' => Auth::id(),
            'linkedin_member_id' => $memberId,
            'person_urn' => $personUrn,
            'access_token' => $socialiteUser->token,
            'refresh_token' => $socialiteUser->refreshToken ?? null,
            'expires_at' => isset($socialiteUser->expiresIn) ? now()->addSeconds((int) $socialiteUser->expiresIn) : null,
            'scopes' => is_array($socialiteUser->approvedScopes ?? null) ? implode(' ', $socialiteUser->approvedScopes) : null,
        ])->save();

        return Redirect::route('dashboard')->with('status', 'LinkedIn connected successfully.');
    }
}

