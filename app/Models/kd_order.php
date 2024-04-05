<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kd_order extends Model
{
    use HasFactory;

    protected $fillable = [
        'arrivalbiz',
        'shipper',
        'main_price',
        'memo',
        'kd_bl',
        'supplier_bl',
        'multy_bl',
        'bl_status',
        'arrival_date',
        'dispatch_date',
        'ipaddr',
        'supplier_id',
        'supplier_orders_id',
        'cargo_packing_id',
        'cargo_location_id',
        'user_id',
    ];
}
