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
        Schema::table('users', function (Blueprint $table) {

           // $table->string('first_name')->nullable()->after('name');
            
          //  $table->string('gender')->nullable()->after('first_name'); 
            
          //  $table->string('profile_photo_path', 2048)->nullable()->after('password'); // 2048 est la taille max recommandée pour les chemins de fichier
            // Ajoute une colonne pour l'ID du projet, clé étrangère vers la table 'projects'
            // Peut être nulle, et si un projet est supprimé, le project_id de l'utilisateur sera mis à null
            
          //  $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null')->after('role'); // ou after un autre champ existant
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprime la clé étrangère avant de supprimer la colonne
                $table->dropForeign(['project_id']);
                // Supprime les colonnes dans l'ordre inverse de leur création
                $table->dropColumn(['project_id', 'profile_photo_path', 'gender', 'first_name']);
        });
    }
};
