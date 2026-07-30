<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Subject $subject): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function update(User $user, Subject $subject): bool
    {
        
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'supervisor'
            && $subject->grade->supervisor_id === $user->id;
    }

    public function delete(User $user, Subject $subject): bool
    {
           if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'supervisor'
            && $subject->grade->supervisor_id === $user->id;
    }
}