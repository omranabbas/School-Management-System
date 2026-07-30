<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function view(User $user, Subject $subject): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function update(User $user, Subject $subject): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function delete(User $user, Subject $subject): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }
}