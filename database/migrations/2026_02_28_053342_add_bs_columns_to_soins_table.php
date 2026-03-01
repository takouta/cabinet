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
        Schema::table('soins', function (Blueprint $table) {
            $table->string('dent')->nullable()->after('acte_code');
            $table->string('cotation')->nullable()->after('dent');
            $table->enum('type_soin', ['acte', 'prothese'])->default('acte')->after('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soins', function (Blueprint $table) {
            $table->dropColumn(['dent', 'cotation', 'type_soin']);
        });
    }
};
