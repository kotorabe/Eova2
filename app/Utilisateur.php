<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Model implements Authenticatable
{
    use SoftDeletes, Notifiable, AuthenticatableTrait;
    //protected $primaryKey = 'id_utilisateur';
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    //protected $table = 'utilisateurs';
    protected $guarded = [];

    public function getAuthIdentifierName()
    {
        return 'id'; // Le nom de la colonne qui sert d'identifiant
    }

    public function getAuthIdentifier()
    {
        return $this->getKey(); // La valeur de l'identifiant
    }

    public function getAuthPassword()
    {
        return $this->password; // La valeur du champ de mot de passe
    }
}
