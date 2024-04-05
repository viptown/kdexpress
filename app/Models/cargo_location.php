<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cargo_location extends Model
{
    use HasFactory;
    protected $fillable = [
        'location_name'
    ];

    public function no_supplier(): HasMany
    {
        return $this->hasMany(no_supplier::class);
    }

    public function OrderStatus(): HasMany
    {
        return $this->hasMany(OrderStatus::class);
    }
}
