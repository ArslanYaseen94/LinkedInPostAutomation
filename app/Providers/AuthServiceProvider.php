<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\PostTemplate;
use App\Policies\PostPolicy;
use App\Policies\PostTemplatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
        PostTemplate::class => PostTemplatePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
