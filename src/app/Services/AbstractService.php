<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

abstract class AbstractService
{
    public function begin(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
