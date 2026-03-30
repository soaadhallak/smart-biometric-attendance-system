<?php

namespace App\Traits;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait CourseFilterQuery
{
    public static function getQuery(): QueryBuilder
    {
        return QueryBuilder::for(Course::class)
            ->allowedFilters([
                AllowedFilter::scope('day'),
                AllowedFilter::scope('search'),
            ])
            ->defaultSort('name');
    }

    public function scopeDay(Builder $query, $day = null): Builder
    {
        $start = now()->startOfWeek(Carbon::SUNDAY);
        $end = now()->endOfWeek(Carbon::SATURDAY);

        return $query->whereHas('lectures', function ($q) use ($day, $start, $end) {
            $q->whereBetween('lecture_date', [$start, $end]);
            
            if ($day && $day !== 'all') {
                $dayNumber = Carbon::parse($day)->dayOfWeek + 1;
                $q->whereRaw('DAYOFWEEK(lecture_date) = ?', [$dayNumber]);
            }
        });
    }

    public function scopeSearch(Builder $query, $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where('name', 'like', "%$term%");
    }
}
