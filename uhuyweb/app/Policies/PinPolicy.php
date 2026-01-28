<?php

namespace App\Policies;

use App\Models\Pin;
use App\Models\User;

class PinPolicy
{
    public function update(User $user, Pin $pin): bool
    {
        return $user->id === $pin->user_id;
    }

    public function delete(User $user, Pin $pin): bool
    {
        return $user->id === $pin->user_id;
    }
}
