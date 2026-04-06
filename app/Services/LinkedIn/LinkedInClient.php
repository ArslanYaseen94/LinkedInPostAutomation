<?php

namespace App\Services\LinkedIn;

use App\Models\LinkedInAccount;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class LinkedInClient
{
    public function publishWithImage(User $user, string $text, ?string $imagePath = null, ?string $linkUrl = null): array
    {
        $account = LinkedInAccount::query()->where('user_id', $user->id)->first();

        if (! $account || ! is_string($account->access_token) || $account->access_token === '' || ! is_string($account->person_urn) || $account->person_urn === '') {
            return [
                'ok' => false,
                'error' => 'LinkedIn is not connected. Go to Dashboard and connect LinkedIn first.',
            ];
        }

        // If image exists, upload it first
        $mediaDetails = null;
        if ($imagePath) {
            $mediaDetails = $this->uploadImage($account->person_urn, $account->access_token, $imagePath);
            if (! $mediaDetails) {
                return [
                    'ok' => false,
                    'error' => 'Failed to upload image to LinkedIn',
                ];
            }
        }

        $payload = [
            'author' => $account->person_urn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $text,
                    ],
                    'shareMediaCategory' => $mediaDetails ? 'IMAGE' : ($linkUrl ? 'ARTICLE' : 'NONE'),
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        // Add image media if uploaded
        if ($mediaDetails) {
            $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [$mediaDetails];
        }
        // Add link media if provided (and no image)
        elseif ($linkUrl) {
            $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = [[
                'status' => 'READY',
                'originalUrl' => $linkUrl,
            ]];
        }

        $response = Http::withToken($account->access_token)
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->withHeaders([
                'X-Restli-Protocol-Version' => '2.0.0',
            ])
            ->post('https://api.linkedin.com/v2/ugcPosts', $payload);

        if (! $response->successful()) {
            if (in_array($response->status(), [401, 403], true)) {
                return [
                    'ok' => false,
                    'error' => "LinkedIn permission error ({$response->status()}): Your LinkedIn app likely doesn't have posting permission (w_member_social) approved. Response: ".$response->body(),
                ];
            }

            return [
                'ok' => false,
                'error' => "LinkedIn API error ({$response->status()}): ".$response->body(),
            ];
        }

        $createdUrn = $response->header('x-restli-id') ?: $response->header('X-RestLi-Id');

        return [
            'ok' => true,
            'result' => [
                'linkedin_urn' => $createdUrn,
            ],
        ];
    }

    private function uploadImage(string $personUrn, string $accessToken, string $imagePath): ?array
    {
        // Register media upload
        $registerPayload = [
            'registerUploadRequest' => [
                'recipes' => ['urn:li:digitalmediaRecipe:feedshare-image'],
                'owner' => $personUrn,
                'serviceRelationships' => [
                    [
                        'relationshipType' => 'OWNER',
                        'identifier' => 'urn:li:userGeneratedContent',
                    ],
                ],
            ],
        ];

        $registerResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->withHeaders([
                'X-Restli-Protocol-Version' => '2.0.0',
            ])
            ->post('https://api.linkedin.com/v2/assets?action=registerUpload', $registerPayload);

        if (! $registerResponse->successful()) {
            return null;
        }

        $uploadUrl = $registerResponse->json('value.uploadMechanism.com.linkedin.digitalmedia_upload.MediaUploadHttpRequest.uploadUrl');
        $assetUrn = $registerResponse->json('value.asset');

        if (! $uploadUrl || ! $assetUrn) {
            return null;
        }

        // Upload the image
        $fullPath = public_path($imagePath);
        if (! file_exists($fullPath)) {
            return null;
        }

        $uploadResponse = Http::withToken($accessToken)
            ->timeout(60)
            ->put($uploadUrl, file_get_contents($fullPath));

        if (! $uploadResponse->successful()) {
            return null;
        }

        return [
            'status' => 'READY',
            'media' => $assetUrn,
        ];
    }

    public function publishTextForUser(User $user, string $text, ?string $linkUrl = null): array
    {
        return $this->publishWithImage($user, $text, null, $linkUrl);
    }
}

