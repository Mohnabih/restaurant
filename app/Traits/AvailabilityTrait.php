<?php

namespace App\Traits;

use App\Enums\StatusEnum;

trait AvailabilityTrait
{
    /**
     * Scope the query to include only the available records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', StatusEnum::AVAILABLE);
    }

    /**
     * Scope the query to include only the unavailable records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnavailable($query)
    {
        return $query->where('status', StatusEnum::UNAVAILABLE);
    }
}
