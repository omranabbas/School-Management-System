<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    public function updatePassword(User $authUser, User $targetUser): bool
    {
        if ($authUser->role === 'admin') {
            return true;
        }

        if ($authUser->role !== 'supervisor') {
            return false;
        }

        if (in_array($targetUser->role, ['admin', 'supervisor'])) {
            return false;
        }

        if ($targetUser->role === 'student') {

            return $targetUser->enrollments()
                ->whereHas('section.grade', function ($query) use ($authUser) {
                    $query->where('supervisor_id', $authUser->id);
                })
                ->exists();
        }

        if ($targetUser->role === 'teacher') {

            return $targetUser->teacherSubjects()
                ->whereHas('section.grade', function ($query) use ($authUser) {
                    $query->where('supervisor_id', $authUser->id);
                })
                ->exists();
        }

        return false;
    }
}
