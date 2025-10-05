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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('cnpj')->nullable()->after('name');
            $table->string('inscricao_estadual')->nullable()->after('cnpj');
            $table->string('inscricao_municipal')->nullable()->after('inscricao_estadual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['cnpj', 'inscricao_estadual', 'inscricao_municipal']);
        });
    }
};
