<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kd_orders', function (Blueprint $table) {
            $table->id();
            $table->string('arrivalbiz');
            $table->string('shipper');
            $table->unsignedInteger('main_price');
            $table->longText('memo');
            $table->string('kd_bl');
            $table->string('supplier_bl')->unique();
            $table->string('multy_bl');
            $table->unsignedTinyInteger('bl_status');
            $table->date('arrival_date');
            $table->date('dispatch_date');
            $table->ipAddress('ipaddr');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('supplier_orders_id')->constrained('supplier_orders');
            $table->foreignId('cargo_packing_id')->constrained('cargo_packings');
            $table->foreignId('cargo_location_id')->constrained('cargo_locations');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kd_orders');
    }
};
