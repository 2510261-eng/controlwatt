<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;

class DevicePolicy
{
    public function view(User $user, Device $device): bool
    {
        return $this->manage($user, $device);
    }

    public function update(User $user, Device $device): bool
    {
        return $this->manage($user, $device);
    }

    public function delete(User $user, Device $device): bool
    {
        return $this->manage($user, $device);
    }

    public function manage(User $user, Device $device): bool
    {
        if ($device->home === null) {
            return false;
        }

        return $device->home->user_id === $user->id
            || $device->home->members()->where('users.id', $user->id)->exists();
    }
}
