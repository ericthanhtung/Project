<?php

namespace app\Facades\Components;

use ArrayObject;
use Illuminate\Http\JsonResponse;

class ResponseFormat
{
    public function success(array $data = [], array $metadata = [])
    {
        return response()->json([
            'code' => JsonResponse::HTTP_OK,
            'data' => empty($data) ? new ArrayObject : array_merge($data, [
                'metadata' => empty($metadata) ? new ArrayObject : $metadata,
            ]),
        ]);
    }

    public function failure(string $message = '', int $status = 500, array $optional = []): JsonResponse
    {
        return response()->json(array_merge([
            'code' => $status,
            'message' => $message,
        ], $optional), $status);
    }
}
