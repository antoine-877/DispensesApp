<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/*
|--------------------------------------------------------------------------
| Model : Responsible
|--------------------------------------------------------------------------
|
| Ce modèle représente les responsables de notre application.
|
| Il regroupe deux types de profils :
| - Coordinateur
| - Administrateur
|
| Les deux profils utilisent la même table "responsibles"
| et disposent des mêmes possibilités dans l'application.
|
| Le champ "role" permet simplement de distinguer leur fonction.
|
*/

class Responsible extends Authenticatable
{
    // -------------------------------------------------------------
    // TRAITS (FONCTIONNALITÉS RÉUTILISABLES)
    // -------------------------------------------------------------

    // Notifiable :
    // Permet au responsable de recevoir des notifications Laravel,
    // par exemple des emails de notification.
    //
    // Authenticatable :
    // Hérité par notre classe.
    // Permet d'utiliser le système d'authentification Laravel.
    use HasFactory, Notifiable;

    // -------------------------------------------------------------
    // ATTRIBUTS MODIFIABLES
    // -------------------------------------------------------------

    // Définit les colonnes qui peuvent être remplies automatiquement
    // lors de la création ou de la modification d'un responsable.
    //
    // Exemple :
    // Responsible::create([
    //     'nom' => 'Dupont',
    //     'prenom' => 'Jean',
    //     ...
    // ]);
    //
    // Seuls les champs présents dans cette liste peuvent être
    // renseignés par cette méthode.
    protected $fillable = [
        'name',
        'firstname',
        'title',
        'function',
        'mail',
        'phone',
        'password',
        'role',
    ];

    // -------------------------------------------------------------
    // ATTRIBUTS MASQUÉS
    // -------------------------------------------------------------

    // Définit les informations qui ne doivent pas apparaître
    // lorsqu'on convertit le modèle en tableau ou en JSON.
    //
    // Cela évite notamment d'exposer les informations sensibles
    // lors de l'envoi de données vers une API.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // -------------------------------------------------------------
    // CONVERSIONS AUTOMATIQUES (CASTS)
    // -------------------------------------------------------------

    // Définit comment Laravel doit traiter certaines colonnes.
    protected function casts(): array
    {
        return [

            // Demande à Laravel de hacher automatiquement le mot
            // de passe lorsqu'il est enregistré.
            'password' => 'hashed',

        ];
    }
}
