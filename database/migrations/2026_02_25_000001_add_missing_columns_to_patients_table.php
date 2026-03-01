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
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('patients', 'lieu_naissance')) {
                $table->string('lieu_naissance')->nullable()->after('date_naissance');
            }
            if (!Schema::hasColumn('patients', 'sexe')) {
                $table->string('sexe', 10)->nullable()->after('lieu_naissance');
            }
            if (!Schema::hasColumn('patients', 'numero_securite_sociale')) {
                $table->string('numero_securite_sociale')->nullable()->after('sexe');
            }
            if (!Schema::hasColumn('patients', 'mutuelle')) {
                $table->string('mutuelle')->nullable()->after('numero_securite_sociale');
            }
            if (!Schema::hasColumn('patients', 'allergies')) {
                $table->json('allergies')->nullable()->after('antecedents_medicaux');
            }
            if (!Schema::hasColumn('patients', 'groupe_sanguin')) {
                $table->string('groupe_sanguin', 5)->nullable()->after('allergies');
            }
            if (!Schema::hasColumn('patients', 'profession')) {
                $table->string('profession')->nullable()->after('groupe_sanguin');
            }
            if (!Schema::hasColumn('patients', 'contact_urgence')) {
                $table->string('contact_urgence')->nullable()->after('profession');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'lieu_naissance',
                'sexe',
                'numero_securite_sociale',
                'mutuelle',
                'allergies',
                'groupe_sanguin',
                'profession',
                'contact_urgence'
            ]);
        });
    }
};
