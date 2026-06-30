<?php

namespace App\Policies;

use App\Models\TeacherAbsence;
use App\Models\User;

class TeacherAbsencePolicy
{
    public function view(User $user, TeacherAbsence $teacherAbsence): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'supervisor') {
            return true;
        }

        return $user->role === 'teacher'
            && $teacherAbsence->teacher_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'teacher';
    }

    public function update(User $user, TeacherAbsence $teacherAbsence): bool
    {
        return $user->role === 'teacher'
            && $teacherAbsence->teacher_id === $user->id
            && $teacherAbsence->status === 'pending';
    }

    public function delete(User $user, TeacherAbsence $teacherAbsence): bool
    {
        return $user->role === 'teacher'
            && $teacherAbsence->teacher_id === $user->id
            && $teacherAbsence->status === 'pending';
    }
    
    public function approve(User $user, TeacherAbsence $absence): bool
    {
        return $user->role === 'supervisor';
    }
}