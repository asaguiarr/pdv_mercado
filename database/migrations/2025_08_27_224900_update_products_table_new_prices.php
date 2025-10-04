<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // remover a coluna antiga price se existir
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }

            // adicionar as novas se não existirem
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->after('name');
            }
            if (!Schema::hasColumn('products', 'profit_margin')) {
                $table->decimal('profit_margin', 5, 2)->after('cost_price');
            }
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->after('profit_margin');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
            if (Schema::hasColumn('products', 'profit_margin')) {
                $table->dropColumn('profit_margin');
            }
            if (Schema::hasColumn('products', 'sale_price')) {
                $table->dropColumn('sale_price');
            }
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 10, 2);
            }
        });
    }
};
