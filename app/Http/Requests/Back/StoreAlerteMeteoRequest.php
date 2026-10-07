<?php

namespace App\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlerteMeteoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'niveau_alerte_id' => ['required', 'exists:niveau_alertes,id'],
            'titre' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'source' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'niveau_alerte_id.required' => 'Le niveau d\'alerte est obligatoire.',
            'niveau_alerte_id.exists' => 'Le niveau d\'alerte sélectionné n\'existe pas.',
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne doit pas dépasser 150 caractères.',
            'message.required' => 'Le message d\'alerte est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début n\'est pas un format de date valide.',
            'date_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'source.max' => 'La source ne doit pas dépasser 100 caractères.',
        ];
    }
}
