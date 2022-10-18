<?php

namespace App\Models\Scopes;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait AllowedSort
{
    public function parseSortDirection()
    {
        return strpos(request()->query('sort_by'), '-') === 0 ? 'desc' : 'asc';
    }

    public function parseSortColumn()
    {
        return ltrim(request()->query('sort_by'), '-');
    }

    public function scopeAllowedSorts(Builder $builder, $columns = [], $defaultColumn = null)
    {
        $column = $this->parseSortColumn();
        if (in_array($column, $columns)) {
            return $builder->orderBy($column, $this->parseSortDirection());
        }
        return $builder;
    }
}
