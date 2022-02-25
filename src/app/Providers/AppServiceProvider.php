<?php

namespace App\Providers;

use App\Facades\Components\ResponseFormat;
use App\Services\Authentication\AuthenticationService;
use App\Services\Authentication\AuthenticationServiceInterface;
use App\Services\Authentication\AuthInterface;
use App\Services\authService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('response', function () {
            return new ResponseFormat;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('search', function (array $attributes, ?string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach ($attributes as $attribute) {
                    $query->when(
                        str_contains($attribute, ':'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relation, $relationAttribute] = explode(':', $attribute);
                            $query->orWhereHas($relation, function (Builder $query) use (
                                $relationAttribute,
                                $searchTerm
                            ) {
                                if (str_contains($relationAttribute, ',')) {
                                    [$first, $last] = explode(',', $relationAttribute);
                                    $query->where(DB::raw("concat({$first}, ' ', {$last})"), 'LIKE', '%' . $searchTerm . '%');
                                    return;
                                }
                                $query->where($relationAttribute, 'LIKE', '%' . $searchTerm . '%');
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            if (str_contains($attribute, ',')) {
                                [$first, $last] = explode(',', $attribute);
                                $query->orWhere(DB::raw("concat({$first}, ' ', {$last})"), 'LIKE', '%' . $searchTerm . '%');
                                return;
                            }
                            $query->orWhere($attribute, 'LIKE', '%' . $searchTerm . '%');
                        }
                    );
                }
            });
            return $this;
        });

        Builder::macro('filterBy', function (array $filterData, array $filterFields) {
            $this->where(function (Builder $query) use ($filterData, $filterFields) {
                foreach ($filterData as $key => $value) {
                    if (!empty($filterFields[$key]) && (!empty($value) || is_numeric($value))) {
                        $query->whereIn($filterFields[$key], explode(',', $value));
                    }
                }
            });
            return $this;
        });

        Builder::macro('sortBy', function (array $sortData, array $sortConditions) {
            foreach ($sortData as $key => $value) {
                if (!empty($sortConditions[$key]) && !empty($value)) {
                    if (is_array($sortConditions[$key])) {
                        foreach ($sortConditions[$key] as $field) {
                            $this->orderBy($field, $value);
                        }
                    } else {
                        $this->orderBy($sortConditions[$key], $value);
                    }
                }
            }

            return $this;
        });

        Builder::macro('searchByAndOperator', function (array $searchData, array $searchFields) {
            $this->where(function (Builder $query) use ($searchData, $searchFields) {
                foreach ($searchData as $key => $value) {
                    if (empty($searchFields[$key]) || empty($value)) {
                        continue;
                    }
                    $query = $query->where(function (Builder $query) use ($searchFields, $key, $value) {
                        if (is_array($searchFields[$key])) {
                            foreach ($searchFields[$key] as $field) {
                                $query->orWhere($field, 'LIKE', "%{$value}%");
                            }
                            return;
                        }
                        $query->where($searchFields[$key], 'LIKE', "%{$value}%");
                    });
                }
            });
            return $this;
        });

        Builder::macro('filterByRange', function (array $filterData, array $filterFields) {
            $this->where(function (Builder  $query) use ($filterData, $filterFields) {
                foreach ($filterFields as $column => $requestKeys) {
                    [$start, $end] = $requestKeys;
                    $query->when(
                        str_contains($column, ':'),
                        function (Builder $query) use ($start, $end, $filterData, $column) {
                            [$relation, $relationAttribute] = explode(':', $column);
                            if (array_key_exists($start, $filterData) && $filterData[$start]) {
                                $query = $query->whereHas($relation, function ($relationQuery) use ($start, $filterData, $relationAttribute) {
                                    $relationQuery->whereDate($relationAttribute, '>=', $filterData[$start]);
                                });
                            }
                            if (array_key_exists($end, $filterData) && $filterData[$end]) {
                                $query = $query->whereHas($relation, function ($relationQuery) use ($end, $filterData, $relationAttribute) {
                                    $relationQuery->whereDate($relationAttribute, '<=', $filterData[$end]);
                                });
                            }

                            return $query;
                        },
                        function (Builder $query) use ($start, $end, $filterData, $column) {
                            if (array_key_exists($start, $filterData) && $filterData[$start]) {
                                $query = $query->whereDate($column, '>=', $filterData[$start]);
                            }

                            if (array_key_exists($end, $filterData) && $filterData[$end]) {
                                $query = $query->whereDate($column, '<=', $filterData[$end]);
                            }

                            return $query;
                        }
                    );
                }
            });
            return $this;
        });
    }
}
