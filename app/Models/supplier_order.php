<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class supplier_order extends Model
{
    use HasFactory;

    protected $fillable = [
        'post',
        'consignee',
        'tel',
        'address',
        'address_details',
        'item',
        'qty',
        'computed_size',
        'main_price',
        'gross_weight',
        'memo',
        'multy_bl',
        'supplier_bl',
        'paytype',
        'origin',
        'ipaddr',
        'user_id',
        'supplier_id',
        'bl_status_id',
        'cargo_packing_id',
        'cargo_location_id',
        'departure_date',
        'arrival_date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }

    public function BlStatus(): BelongsTo
    {
        return $this->belongsTo(BlStatus::class);
    }

    public function CargoPacking(): BelongsTo
    {
        return $this->belongsTo(cargo_packing::class);
    }

    public function CargoLocation(): BelongsTo
    {
        return $this->belongsTo(cargo_location::class);
    }
}
