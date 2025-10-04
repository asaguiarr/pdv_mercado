<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->enum('payment_status', ['paid', 'pending', 'payment_on_delivery'])->default('pending');
            $table->enum('delivery_type', ['pickup', 'delivery'])->default('pickup');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
