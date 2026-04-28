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
        Schema::create('vendor_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->nullable();
            $table->integer('active_plan')->nullable();
            $table->date('current_plan_purchase_date')->nullable();
            $table->date('current_plan_expiry_date')->nullable();
            $table->integer('posted_listing')->nullable();
            $table->integer('badge_id')->nullable();
            $table->tinyInteger('auto_renew_current_plan')->default(0)->comment('1 => Active, 0 => Inactive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_infos');
    }
};
