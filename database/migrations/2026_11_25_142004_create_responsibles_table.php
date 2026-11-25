<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responsibles', function (Blueprint $table) {

            $table->id();


            // -----------------------------------------------------
            // INFORMATIONS PERSONNELLES
            // -----------------------------------------------------

            // Nom de famille
            $table->string('name');
            // Prénom du responsable.
            $table->string('firstname');
            // Titre ou civilité : M., Mme, Autre
            $table->string('titre', 50)->nullable();
            // Fonction ou poste occupé dans l'établissement.
            // Exemple : Coordinateur pédagogique, Administrateur...
            $table->string('function');


            // -----------------------------------------------------
            // COORDONNÉES ET CONTACT
            // -----------------------------------------------------

            // Adresse email du responsable.
            // Elle est obligatoire et doit être unique :
            $table->string('mail')->unique();

            // Numéro de téléphone du responsable.
            // Le champ est facultatif.
            $table->string('phone', 30)->nullable();

            // -----------------------------------------------------
            // AUTHENTIFICATION
            // -----------------------------------------------------

            // Mot de passe du compte.
            // Cette colonne stockera le mot de passe sous forme hachée
            // et non en texte brut.
            $table->string('password');

            // -----------------------------------------------------
            // TYPE DE RESPONSABLE (RÔLE)
            // -----------------------------------------------------

            // Permet de distinguer les différents profils :
            // "coordinateur" ou "admin".
            //
            // Pour la Policy on laissera passer pour le dashboard de controle uniquement les coordinateurs et les administrateurs.
            // Le poste laisse les prof comme thibaut etre professeur tout en ayant le role Administrateur en plus. Et donc avoir accès a l'espace prof et au dashboard admin.
            //
            // 20 correspond au nombre maximal de caractères autorisés.
            $table->string('role', 20);


            // -----------------------------------------------------
            // GESTION DE LA CONNEXION PERSISTANTE
            // -----------------------------------------------------

            // Crée la colonne "remember_token".
            //
            // Cette colonne est utilisée par Laravel pour la fonctionnalité
            // "Se souvenir de moi" sur le formulaire de connexion.
            //
            // Elle permet de reconnaître un navigateur lors d'une visite
            // ultérieure, sans demander systématiquement au responsable
            // de ressaisir ses identifiants.
            //
            // ATTENTION :
            // Ce jeton est différent du mot de passe.
            //
            // La colonne est facultative et gérée par Laravel.
            $table->rememberToken();


            // -----------------------------------------------------
            // DATES DE CRÉATION ET DE MODIFICATION
            // -----------------------------------------------------
            $table->timestamps();
        });
    }

    /**
     * Annule la migration.
     *
     * Cette méthode est appelée lorsque nous souhaitons revenir
     * en arrière avec une commande comme :
     * php artisan migrate:rollback
     *
     * Elle supprime la table "responsibles".
     */
    public function down(): void
    {
        Schema::dropIfExists('responsibles');
    }
};
