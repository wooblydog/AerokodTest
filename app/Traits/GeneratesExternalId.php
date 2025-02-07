<?php

namespace App\Traits;

trait GeneratesExternalId
{
    protected function generateExternalId($model): int
    {
        $lastRecord = $model::query()
            ->latest('id')
            ->first();

        return is_null($lastRecord) ? 1 : $lastRecord->external_id + 1;
    }
}
