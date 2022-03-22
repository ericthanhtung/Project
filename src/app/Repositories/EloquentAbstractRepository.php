<?php

namespace App\Repositories;

use App\Models\AbstractModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentAbstractRepository implements EloquentAbstractRepositoryInterface
{
    protected $model;

    public function __construct(AbstractModel $model)
    {
        $this->model = $model;
    }

    public function create(array $values): AbstractModel
    {
        return $this->model->create($values);
    }

    public function update(array $values): bool
    {
        return $this->model->update($values);
    }

    public function updateOrCreate(array $attributes, array $values = []): AbstractModel
    {
        return $this->model->updateOrCreate($values);
    }

    public function delete()
    {
        return $this->model->delete();
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->get($columns);
    }

    public function first(array $columns = ['*']): ?AbstractModel
    {
        return $this->model->first($columns);
    }

    public function find(int $id, array $columns = ['*']): ?AbstractModel
    {
        return $this->model->find($id, $columns);
    }

    public function paginate(int $limit, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($limit, $columns);
    }

    public function findWhere(array $where): EloquentAbstractRepositoryInterface
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                [$field, $condition, $val] = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
        return $this;
    }

    public function orFindWhere(array $where): EloquentAbstractRepositoryInterface
    {
        $this->model = $this->model->orWhere(function (Builder $query) use ($where) {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                [$field, $condition, $val] = $value;
                $query = $query->where($field, $condition, $val);
            } else {
                $query = $query->where($field, '=', $value);
            }
        }
    });
        return $this;
    }

    public function findWhereIn(string $column, array $values): EloquentAbstractRepositoryInterface
    {
        $this->model = $this->model->whereIn($column, $values);
        return $this;
    }

    public function findWhereNotIn(string $field, array $values): EloquentAbstractRepositoryInterface
    {
        $this->model = $this->model->whereNotIn($field, $values);
        return $this;
    }

    public function filter(array $filterData, array $filterFields): EloquentAbstractRepositoryInterface
    {
        foreach ($filterData as $key => $value) {
            if (!empty($filterFields[$key]) && (!empty($value) || is_numeric($value))) {
                $this->model = $this->model->whereIn($filterFields[$key], explode(',', $value));
            }
        }
        return $this;
    }

    public function search(array $searchData, array $searchFields): EloquentAbstractRepositoryInterface
    {
        $this->model = $this->model->where(function (Builder $query) use ($searchData, $searchFields) {
            $first = true;
            foreach ($searchData as $key => $value) {
                if (empty($searchFields[$key]) || empty($value)) {
                    continue;
                }

                if ($first) {
                    $query->where(function (Builder $query) use ($searchFields, $key, $value) {
                        if (is_array($searchFields[$key])) {
                            foreach ($searchFields[$key] as $field) {
                                $query->orWhere($field, 'LIKE', "%{$value}%");
                            }
                            return;
                        }
                        $query->where($searchFields[$key], 'LIKE', "%{$value}%");
                    });
                    $first = false;
                } else {
                    $query = $query->orWhere(function ($query) use ($searchFields, $key, $value) {
                        if (is_array($searchFields[$key])) {
                            foreach ($searchFields[$key] as $field) {
                                $query->orWhere($field, 'LIKE', "%{$value}%");
                            }
                            return;
                        }
                        $query->where($searchFields[$key], 'LIKE', "%{$value}%");
                    });
                }
            }
        });
        return $this;
    }

    public function searchByAndOperator(array $searchData, array $searchFields): EloquentAbstractRepositoryInterface
    {
        $this->model = $this->model->where(function (Builder $query) use ($searchData, $searchFields) {
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
    }

    public function sort(array $sortData, array $sortConditions): EloquentAbstractRepositoryInterface
    {
        foreach ($sortData as $key => $value) {
            if (!empty($sortConditions[$key]) && !empty($value)) {
                if (is_array($sortConditions[$key])) {
                    foreach ($sortConditions[$key] as $field) {
                        $this->model = $this->model->orderBy($field, $value);
                    }
                } else {
                    $this->model = $this->model->orderBy($sortConditions[$key], $value);
                }
            }
        }
        return $this;
    }

    public function chunk(int $size, callable $callback): bool
    {
        return $this->model->chunk($size, $callback);
    }

    public function with(array $relations): EloquentAbstractRepositoryInterface
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    public function withCount(array $relations): EloquentAbstractRepositoryInterface
    {
        $this->model = $this->model->withCount($relations);
        return $this;
    }
}
