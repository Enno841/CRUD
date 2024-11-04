<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cities extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'region_id',
        'name'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
