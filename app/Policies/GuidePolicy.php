<?php

namespace App\Policies;

use App\Models\Guide;
use App\Models\User;

class GuidePolicy
{
    public function update(User $user, Guide $guide): bool
    {
        return $user->id === $guide->user_id;
    }

    public function delete(User $user, Guide $guide): bool
    {
        return $user->id === $guide->user_id;
    }
}
