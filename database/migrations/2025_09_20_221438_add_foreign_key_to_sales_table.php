<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // The foreign key constraint is already added in the create_sales_table migration
        // So, we can skip this migration or just do nothing
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Since the foreign key was added in a different migration, we don't need to drop it here
        // The add_user_id_to_sales_table migration handles the foreign key
    }
};
