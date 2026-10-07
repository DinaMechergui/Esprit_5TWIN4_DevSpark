<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NiveauAlerte extends Model
{
    use HasFactory;

    /**
     * Nom de la table associée au modèle.
     */
    protected $table = 'niveau_alertes';

    /**
     * Les attributs assignables en masse.
     */
    protected $fillable = [
        'libelle',
        'couleur',
        'niveau',
    ];

    /**
     * Alertes météo rattachées à ce niveau.
     */
    public function alertes(): HasMany
    {
        return $this->hasMany(AlerteMeteo::class, 'niveau_alerte_id');
    }
}
