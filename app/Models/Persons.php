<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persons extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'region_id',
        'city_id',
        'first_name',
        'last_name',
        'middle_name',
        'address',
        'zip_code',
        'date_of_birth',
        'age'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(Cities::class);
    }
}
