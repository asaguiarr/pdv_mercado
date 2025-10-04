<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryHistoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_person_id');
            $table->unsignedBigInteger('order_id');
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('delivery_person_id')->references('id')->on('delivery_people')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_history');
    }
}
