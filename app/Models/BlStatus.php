<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name',
    ];

    // public function supplier_order(): HasMany
    // {
    //     return $this->hasMany(supplier_order::class);
    // }
}
