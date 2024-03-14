<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LookupResults extends Model
{
    protected $fillable = [
        'city',
        'state_id',
        'result'
    ];

    protected $casts = [
        'city' => 'string',
        'state_id' => 'integer',
        'result' => 'array',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * @return HasOne
     */
    public function state(): HasOne
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

}
