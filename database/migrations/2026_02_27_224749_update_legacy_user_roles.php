<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->where('role', 'admin')->update(['role' => 'admin_cabinet']);
        DB::table('users')->where('role', 'dentiste')->update(['role' => 'medecin']);
        DB::table('users')->where('role', 'assistant')->update(['role' => 'secretaire']); // Supposé rôle le plus proche
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas de retour en arrière facile sans savoir qui était quoi, 
        // mais on peut remettre admin_cabinet en admin si besoin.
        DB::table('users')->where('role', 'admin_cabinet')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'medecin')->update(['role' => 'dentiste']);
        DB::table('users')->where('role', 'secretaire')->update(['role' => 'assistant']);
    }
};
