<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin')->change();
            }

            if (!Schema::hasColumn('users', 'actif')) {
                $table->boolean('actif')->default(true)->after('role');
            }

            if (!Schema::hasColumn('users', 'derniere_connexion')) {
                $table->timestamp('derniere_connexion')->nullable()->after('remember_token');
            }

            if (!Schema::hasColumn('users', 'nom')) {
                $table->string('nom')->nullable()->after('id');
            }

            if (!Schema::hasColumn('users', 'prenom')) {
                $table->string('prenom')->nullable()->after('nom');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'prenom')) {
                $table->dropColumn('prenom');
            }

            if (Schema::hasColumn('users', 'nom')) {
                $table->dropColumn('nom');
            }

            if (Schema::hasColumn('users', 'derniere_connexion')) {
                $table->dropColumn('derniere_connexion');
            }

            if (Schema::hasColumn('users', 'actif')) {
                $table->dropColumn('actif');
            }
        });
    }
};
