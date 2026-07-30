<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeacherSubject;

class TeacherSubjectPolicy
{
   public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function view(User $user, TeacherSubject $teacherSubject): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function update(User $user, TeacherSubject $teacherSubject): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function delete(User $user, TeacherSubject $teacherSubject): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }
}