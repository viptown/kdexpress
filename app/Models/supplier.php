<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'tel',
        'email',
        'api_supplier_id',
        'user_id',
    ];

    public function cbms(): HasMany
    {
        return $this->hasMany(cbm::class);
    }

    public function weights(): HasMany
    {
        return $this->hasMany(weight::class);
    }

    public function cargo_packings(): HasMany
    {
        return $this->hasMany(cargo_packing::class);
    }

    public function supplier_orders(): HasMany
    {
        return $this->hasMany(supplier_order::class);
    }

    // public function kd_orders(): HasOne
    // {
    //     return $this->hasOne(supplier_orders::class);
    // }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
