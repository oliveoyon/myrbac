<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

class CaseInterviewDatePolicy
{
    public function cutoffDay(): int
    {
        return min(28, max(1, (int) config('a2j.case_entry.interview_date_cutoff_day', 5)));
    }

    public function canOverride(?Authenticatable $user): bool
    {
        return $user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Admin', 'Super Admin']);
    }

    public function validate($interviewDate, ?Authenticatable $user = null): array
    {
        if ($this->canOverride($user)) {
            return ['allowed' => true, 'message' => null];
        }

        if (blank($interviewDate)) {
            return [
                'allowed' => false,
                'message' => 'Date of Interview is required.',
            ];
        }

        try {
            $date = Carbon::parse($interviewDate)->startOfDay();
        } catch (\Throwable $e) {
            return [
                'allowed' => false,
                'message' => 'Date of Interview must be a valid date.',
            ];
        }

        $today = Carbon::now(config('app.timezone'))->startOfDay();

        if ($date->gt($today)) {
            return [
                'allowed' => false,
                'message' => 'Date of Interview cannot be later than today.',
            ];
        }

        $deadline = $date->copy()
            ->startOfMonth()
            ->addMonthNoOverflow()
            ->day($this->cutoffDay())
            ->endOfDay();

        if ($today->gt($deadline)) {
            return [
                'allowed' => false,
                'message' => sprintf(
                    '%s interview data could be entered until %s.',
                    $date->format('F Y'),
                    $deadline->format('j F Y')
                ),
            ];
        }

        return ['allowed' => true, 'message' => null];
    }

    public function frontendConfig(?Authenticatable $user = null): array
    {
        return [
            'enabled' => ! $this->canOverride($user),
            'cutoffDay' => $this->cutoffDay(),
            'today' => Carbon::now(config('app.timezone'))->toDateString(),
        ];
    }
}
