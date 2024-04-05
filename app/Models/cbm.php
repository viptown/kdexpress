<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class cbm extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'price_express',
        'price_regular',
        'supplier_id',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(supplier::class);
    }
}
