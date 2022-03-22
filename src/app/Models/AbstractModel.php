<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class AbstractModel
 * @package App\Models
 * @mixin Builder
 *
 * @method Builder search(array $attributes, ?string $searchTerm)
 * @method Builder sortBy(array $sortData, array $sortConditions)
 * @method Builder filterBy(array $filterData, array $filterFields)
 * @method Builder searchByAndOperator(array $searchData, array $searchFields)
 */
abstract class AbstractModel extends Authenticatable
{
    //
}
