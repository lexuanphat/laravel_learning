<?php

namespace App\Models\Scopes;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait AllowedFilterSearch
{
    public function scopeAllowedFilters(Builder $builder, ...$keys)
    {
        foreach ($keys as $key) {
            $value = request()->query($key);

            if ($value) {
                $builder->where($key, $value);
            }
        }

        return $builder;
    }

    public function scopeAllowedSearch(Builder $builder, ...$keys)
    {
        $search = request()->query('search');

        if ($search) {
            foreach ($keys as $index => $value) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $builder->{$method}($value, 'LIKE', '%' . $search . '%');
            }
        }
        return $builder;
    }

    public function scopeAllowedTrash(Builder $builder)
    {
        if (request()->query('trash')) {
            $builder->onlyTrashed();
        }
        return $builder;
    }
}
