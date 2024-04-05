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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_order_id')->constrained('supplier_orders');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('bl_status_id')->constrained('bl_status');
            $table->foreignId('cargo_location_id')->constrained('cargo_locations');
            $table->foreignId('cargo_packing_id')->constrained('cargo_packings');
            $table->text('memo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
