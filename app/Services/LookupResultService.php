<?php

namespace App\Services;

use App\Casts\Timezone;
use App\Models\LookupResults;

/**
 * Class LookupResultService
 * @package App\Services
 */
readonly class LookupResultService
{
    public function __construct(
        private LookupResults $model
    ) {}

    /**
     * @param $params
     * @return LookupResults
     */
    public function create($params): LookupResults
    {
        $this->model->fill($params);
        $this->model->save();
        $this->model->refresh()->withCasts([
            'created_at' => Timezone::class,
        ])->with('state');

        return $this->model;
    }

    /**
     * @param array $attributes
     * @return LookupResults|null
     */
    public function getLastByAttributes(array $attributes): ?LookupResults
    {
        return $this->model::where($attributes)
            ->whereRaw('created_at >= NOW() - INTERVAL 1 HOUR')
            ->withCasts([
                'created_at' => Timezone::class,
            ])
            ->with('state')
            ->latest('created_at')
            ->first();
    }
}
