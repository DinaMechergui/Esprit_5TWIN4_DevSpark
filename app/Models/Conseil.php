<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Conseil pratique affiché aux habitants pendant les canicules et coupures.
 */
class Conseil extends Model
{
    use HasFactory;

    protected $table = 'conseils';

    protected $fillable = ['categorie_conseil_id', 'titre', 'contenu'];

    // Relation inverse : un conseil appartient à une catégorie.
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieConseil::class, 'categorie_conseil_id');
    }

    // Scope de recherche : mot-clé dans le titre ou le contenu.
    public function scopeRecherche($query, ?string $mot)
    {
        return $query->when($mot, fn ($q) => $q->where(function ($qq) use ($mot) {
            $qq->where('titre', 'like', "%{$mot}%")
               ->orWhere('contenu', 'like', "%{$mot}%");
        }));
    }
}
