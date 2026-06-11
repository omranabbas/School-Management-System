<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Schedule;

class SchedulePolicy
{
    public function view(User $user, Schedule $schedule): bool
    {
        return in_array($user->role, [
            'admin',
            'supervisor',
            'teacher',
            'student',
        ]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            'admin',
            'supervisor',
        ]);
    }

    public function update(User $user, Schedule $schedule): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role !== 'supervisor') {
            return false;
        }

        return optional(
            $schedule->teacherSubject
                ?->section
                ?->grade
        )->supervisor_id === $user->id;
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role !== 'supervisor') {
            return false;
        }

        return optional(
            $schedule->teacherSubject
                ?->section
                ?->grade
        )->supervisor_id === $user->id;
    }
}