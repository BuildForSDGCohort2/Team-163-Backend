<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ModelTraits
{
    /**
     * Generate a new UUID.
     *
     * @param string $model
     * @param string $column
     *
     * @return Response
     */
    public function generateUniqueData($model, $column, $uuid = null)
    {
        if (is_null($uuid)) {
            $uuid = Str::uuid();
        }

        if ($this->recordExists($model, $uuid, $column)) {
            return $this->generateUniqueData($model, $column);
        }

        return $uuid;
    }

    /**
     * Check the database model if a record exists.
     *
     * @param string $model
     * @param string $data
     * @param string $column
     *
     * @return Boolean
     */
    public function recordExists($model, $data, $column)
    {
        $model = '\App\\'.ucfirst($model);

        return $model::where($column, $data)->exists();
    }

    /**
     * Retrieve a resource dynamically     *.
     *
     * @param string $model
     * @param string $data
     * @param string $column
     */
    public function getRecordFromTable($model, $data, $column)
    {
        $model = '\App\\'.ucfirst($model);

        return $model::where($column, $data)->firstOrFail();
    }
}
