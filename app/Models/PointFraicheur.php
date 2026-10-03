<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Point de fraîcheur : lieu où les habitants peuvent se rafraîchir.
 */
class PointFraicheur extends Model
{
    /** @use HasFactory<\Database\Factories\PointFraicheurFactory> */
    use HasFactory;

    /**
     * Nom de la table dans la base de données.
     */
    protected $table = 'points_fraicheur';

    protected $fillable = [
        'type_point_id',
        'nom',
        'adresse',
        'latitude',
        'longitude',
        'horaires',
        'accessible',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'accessible' => 'boolean',
        ];
    }

    /**
     * Type du point de fraîcheur (parc, salle climatisée, fontaine...).
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(TypePoint::class, 'type_point_id');
    }
}
