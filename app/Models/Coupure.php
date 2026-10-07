<?php

namespace App\Models;

use Database\Factories\CoupureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupure extends Model
{
    /** @use HasFactory<CoupureFactory> */
    use HasFactory;

    protected $table = 'coupures';

    protected $fillable = [
        'quartier_id',
        'type',
        'statut',
        'date_debut',
        'date_fin',
        'description',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_fin' => 'datetime',
        ];
    }

    public function quartier(): BelongsTo
    {
        return $this->belongsTo(Quartier::class, 'quartier_id');
    }
}
