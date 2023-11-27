<?php

namespace App\Visitable;

use App\Models\Visit;
use App\Visitable\Concerns\FiltersByPopularityTimeframe;
use Illuminate\Database\Eloquent\Builder;

trait Visitable
{
    use FiltersByPopularityTimeframe;

    public function visit()
    {
        return new PendingVisit($this);
    }

    public function scopeWithTotalVisitCount(Builder $query)
    {
        $query->withCount('visits as visit_count_total');
    }

    public function visits()
    {
        return $this->morphMany(Visit::class, 'visitable');
    }
}
