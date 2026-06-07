<?php

namespace App\Policies;

use App\Models\Mark;
use App\Models\User;

class MarkPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'teacher';
    }

    public function view(User $user, Mark $mark): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'teacher') {

            return $mark->teacherSubject->teacher_id === $user->id;

        }

        if ($user->role === 'student') {

            return $mark->enrollment->student_id === $user->id;

        }

        return false;
    }

    public function update(User $user, Mark $mark): bool
    {
        return $user->role === 'teacher'
            && $mark->teacherSubject->teacher_id === $user->id;
    }

    public function delete(User $user, Mark $mark): bool
    {
        return $user->role === 'admin';
    }
}