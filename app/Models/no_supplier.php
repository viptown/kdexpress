<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class no_supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_bl',
        'supplier_id',
        'user_id',
        'cargo_location_id',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(supplier::class);
    }

    public function cargo_location(): BelongsTo
    {
        return $this->belongsTo(cargo_location::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }
}
