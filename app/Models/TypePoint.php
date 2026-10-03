<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Type de point de fraîcheur (parc, salle climatisée, fontaine...).
 */
class TypePoint extends Model
{
    /** @use HasFactory<\Database\Factories\TypePointFactory> */
    use HasFactory;

    /**
     * Nom de la table dans la base de données.
     */
    protected $table = 'type_points';

    protected $fillable = [
        'nom',
        'description',
        'icone',
    ];

    /**
     * Points de fraîcheur rattachés à ce type (1 type → N points).
     */
    public function points(): HasMany
    {
        return $this->hasMany(PointFraicheur::class, 'type_point_id');
    }
}
