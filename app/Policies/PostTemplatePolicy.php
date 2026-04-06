<?php

namespace App\Policies;

use App\Models\PostTemplate;
use App\Models\User;

class PostTemplatePolicy
{
    public function view(User $user, PostTemplate $template): bool
    {
        return $user->id === $template->user_id;
    }

    public function update(User $user, PostTemplate $template): bool
    {
        return $user->id === $template->user_id;
    }

    public function delete(User $user, PostTemplate $template): bool
    {
        return $user->id === $template->user_id;
    }
}
