<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attendance;

class AttendancePolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'supervisor';
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'student') {

            return $attendance->enrollment->student_id === $user->id;

        }

        return false;
    }
    public function update(User $user, Attendance $attendance): bool
    {
        return $user->role === 'supervisor';
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->role === 'supervisor';
    }
}