<?php

// database/migrations/2025_08_26_091801_add_role_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Define role como enum para evitar valores inválidos
            $table->enum('role', ['user', 'admin', 'super_admin', 'cashier', 'estoquista'])
                  ->default('user')
                  ->after('email');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}
