<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // campo de função (super_admin, admin, cashier)
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['user', 'admin', 'super_admin', 'cashier', 'estoquista'])
                      ->default('cashier')
                      ->after('password');
            }

            // campo para indicar se usuário está ativo
            if (!Schema::hasColumn('users', 'active')) {
                $table->boolean('active')->default(true)->after('role');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
