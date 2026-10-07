<?php

namespace App\BackOffice\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCoupureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'quartier_id' => ['required', 'exists:quartiers,id'],
            'type' => ['required', 'in:delestage,panne,surcharge'],
            'statut' => ['required', 'in:prevue,en_cours,terminee'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'description' => ['nullable', 'string'],
        ];
    }
}
