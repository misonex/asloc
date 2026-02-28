<?php

namespace App\Domain\Associations;

use App\Models\Association;
use App\Models\Suite;
use Illuminate\Support\Facades\DB;

class ShareQuotaCalculator
{
    public function recalculate(Association $association): void
    {
        $totalArea = Suite::query()
            ->where('association_id', $association->id)
            ->where('is_active', true)
            ->sum('area');

        if ($totalArea <= 0) {
            return;
        }

        $suites = Suite::query()
            ->where('association_id', $association->id)
            ->where('is_active', true)
            ->get();

        foreach ($suites as $suite) {
            $quota = $suite->area / $totalArea;

            $suite->update([
                'share_quota' => round($quota, 6),
            ]);
        }
    }
}