<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cargo_packing extends Model
{
    use HasFactory;

    protected $fillable = [
        'packing_name',
        'supplier_id',
        'is_visible',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(supplier::class);
    }

    public function OrderStauts(): HasMany
    {
        return $this->hasMany(OrderStatus::class);
    }
}
