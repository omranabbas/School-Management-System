<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeacherSubject;

class TeacherSubjectPolicy
{
   public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TeacherSubject $teacherSubject): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    public function update(User $user, TeacherSubject $teacher_subject): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'supervisor'
            && $teacher_subject->subject->grade->supervisor_id === $user->id;
            
    }

    public function delete(User $user, TeacherSubject $teacher_subject): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'supervisor'
            && $teacher_subject->subject->grade->supervisor_id === $user->id;
    }
}