<?php

namespace App\Services;

use App\Models\CaseSummary;
use App\Models\District;
use Illuminate\Support\Str;

class CaseSummaryCentralIdService
{
    public function nextForDistrict(District $district): string
    {
        $prefix = Str::upper(Str::substr(Str::ascii($district->name), 0, 3)) . '-CS-';

        $lastNumber = CaseSummary::withTrashed()
            ->where('district_id', $district->id)
            ->lockForUpdate()
            ->pluck('central_id')
            ->reduce(function (int $highest, string $centralId) use ($prefix): int {
                if (! str_starts_with(Str::upper($centralId), $prefix)) {
                    return $highest;
                }

                preg_match('/(\d+)$/', $centralId, $matches);

                return isset($matches[1]) ? max($highest, (int) $matches[1]) : $highest;
            }, 0);

        return $prefix . ($lastNumber + 1);
    }
}
