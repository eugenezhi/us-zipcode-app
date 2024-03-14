<?php

namespace App\Casts;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Timezone implements CastsAttributes
{
    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return Carbon|mixed
     * @throws Exception
     */
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        if (!$value) {
            return $value;
        }
        return (new Carbon($value))->setTimezone(config('app.timezone'))->toDateTimeString();
    }

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return array|mixed|string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value;
    }
}
