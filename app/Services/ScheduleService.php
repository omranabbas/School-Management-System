<?php

namespace App\Services;

use App\Models\ScheduleOverride;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleService
{
    public function applyOverrides(Collection $schedules, string $date): Collection
    {
        $overrides = ScheduleOverride::with('replacementTeacher')
            ->whereDate('date', $date)
            ->get()
            ->keyBy('schedule_id');

        return $schedules->map(function ($schedule) use ($overrides) {

            $override = $overrides->get($schedule->id);

            if (! $override) {

                $schedule->override_status = 'scheduled';
                $schedule->replacement_teacher = null;
                $schedule->display_teacher = $schedule->teacherSubject->teacher;

                return $schedule;
            }

            $schedule->override_status = $override->status;
            $schedule->replacement_teacher = $override->replacementTeacher;

            if (
                $override->status === 'replacement'
                && $override->replacementTeacher
            ) {

                $schedule->display_teacher = $override->replacementTeacher;

            } else {

                $schedule->display_teacher = $schedule->teacherSubject->teacher;

            }

            return $schedule;
        });
    }

    public function dayFromDate(string $date): string
    {
        return strtolower(
            Carbon::parse($date)->format('l')
        );
    }
}