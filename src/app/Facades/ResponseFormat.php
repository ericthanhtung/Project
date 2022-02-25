<?php

namespace App\Facades;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Facade;

/**
 * Class ResponseFormat
 * @package App\Facades
 * @method static JsonResponse success(array $data = [], array $metadata = [])
 * @method static JsonResponse failure(string $message = "", int $status = 500, array $optional = [])
 */
class ResponseFormat extends Facade
{
protected static function getFacadeAccessor(): string
{
    return 'response';
}
}
