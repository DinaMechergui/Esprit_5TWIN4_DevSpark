<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Type de signalement (catégorie).
 */
class TypeSignalement extends Model
{
    /** @use HasFactory<\Database\Factories\TypeSignalementFactory> */
    use HasFactory;

    /**
     * Nom de la table dans la base de données.
     */
    protected $table = 'type_signalements';

    protected $fillable = [
        'libelle',
    ];

    /**
     * Signalements rattachés à ce type (1 type → N signalements).
     */
    public function signalements(): HasMany
    {
        return $this->hasMany(Signalement::class, 'type_signalement_id');
    }
}
