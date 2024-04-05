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
        Schema::create('supplier_orders', function (Blueprint $table) {
            $table->id();
            $table->string('biz_shipper');
            $table->string('biz_arrival');
            $table->string('post');
            $table->string('consignee');
            $table->string('tel');
            $table->string('address');
            $table->string('address_details')->nullable();
            $table->string('item');
            $table->unsignedInteger('qty');
            $table->unsignedInteger('computed_size');
            $table->unsignedInteger('main_price');
            $table->unsignedInteger('last_price');
            $table->float('gross_weight', 5, 1);
            $table->float('last_weight', 5, 1);
            $table->longText('memo')->nullable();
            $table->string('multy_bl');
            $table->string('supplier_bl')->unique();
            $table->string('kd_bl');
            $table->string('paytype');
            $table->string('origin');
            $table->ipAddress('ipaddr');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('bl_status_id')->constrained('bl_statuses');
            $table->foreignId('cargo_packing_id')->constrained('cargo_packings');
            $table->foreignId('cargo_location_id')->constrained('cargo_locations');
            $table->date('departure_date');
            $table->date('arrival_date');
            $table->string('origin');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_orders');
    }
};
