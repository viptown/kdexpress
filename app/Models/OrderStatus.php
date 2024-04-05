<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_order_id',
        'user_id',
        'cargo_location_id',
        'cargo_packing_id',
        'bl_status_id',
        'memo'
    ];

    public function supplier_order(): BelongsTo
    {
        return $this->belongsTo(supplier_order::class, 'supplier_order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cargo_location(): BelongsTo
    {
        return $this->belongsTo(cargo_location::class, 'cargo_location_id');
    }

    public function cargo_packing(): BelongsTo
    {
        return $this->belongsTo(cargo_packing::class, 'cargo_packing_id');
    }

    public function bl_status(): BelongsTo
    {
        return $this->belongsTo(BlStatus::class, 'bl_status_id');
    }
}
