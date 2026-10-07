<?php

namespace App\Models;

use Database\Factories\QuartierFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quartier extends Model
{
    /** @use HasFactory<QuartierFactory> */
    use HasFactory;

    protected $table = 'quartiers';

    protected $fillable = ['nom', 'ville', 'code_postal'];

    public function coupures(): HasMany
    {
        return $this->hasMany(Coupure::class, 'quartier_id');
    }
}
