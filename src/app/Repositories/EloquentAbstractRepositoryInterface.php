<?php

namespace App\Repositories;

use App\Models\AbstractModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface EloquentAbstractRepositoryInterface
{
    public function create(array $values): AbstractModel;

    public function update(array $values): bool;

    public function updateOrCreate(array $attributes, array $values = []): AbstractModel;

    public function delete();

    public function all(array $columns = ['*']): Collection;

    public function first(array $columns = ['*']): ?AbstractModel;

    public function find(int $id, array $columns = ['*']): ?AbstractModel;

    public function paginate(int $limit, array $columns = ['*']): LengthAwarePaginator;

    public function findWhere(array $where): EloquentAbstractRepositoryInterface;

    public function orFindWhere(array $where): EloquentAbstractRepositoryInterface;

    public function findWhereIn(string $column, array $values): EloquentAbstractRepositoryInterface;

    public function findWhereNotIn(string $field, array $values): EloquentAbstractRepositoryInterface;

    public function filter(array $filterData, array $filterFields): EloquentAbstractRepositoryInterface;

    public function search(array $searchData, array $searchFields): EloquentAbstractRepositoryInterface;

    public function searchByAndOperator(array $searchData, array $searchFields): EloquentAbstractRepositoryInterface;

    public function sort(array $sortData, array $sortConditions): EloquentAbstractRepositoryInterface;

    public function chunk(int $size, callable $callback): bool;

    public function with(array $relations): EloquentAbstractRepositoryInterface;

    public function withCount(array $relations): EloquentAbstractRepositoryInterface;
}
