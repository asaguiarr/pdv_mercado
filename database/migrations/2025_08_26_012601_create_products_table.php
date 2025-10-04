<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Código interno / SKU (único, mas opcional)
            $table->string('code')->unique()->nullable();

            // Nome do produto
            $table->string('name');

            // Preços
            $table->decimal('cost_price', 10, 2);           // preço de custo
            $table->decimal('profit_margin', 5, 2)->default(0); // margem de lucro (%)
            $table->decimal('sale_price', 10, 2);           // preço de venda (calculado e salvo)

            // Estoque (aceitando fracionado, ex: 1.5kg)
            $table->decimal('stock', 10, 2)->default(0);

            // Status do produto (1 = ativo, 0 = inativo)
            $table->boolean('active')->default(true);

            // Descrição opcional
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
