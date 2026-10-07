<?php

namespace App\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;

class StoreNiveauAlerteRequest extends FormRequest
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
            'libelle' => ['required', 'string', 'max:50', 'unique:niveau_alertes,libelle'],
            'couleur' => ['required', 'string', 'in:vert,jaune,orange,rouge'],
            'niveau' => ['required', 'integer', 'between:1,4'],
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.unique' => 'Ce libellé existe déjà.',
            'couleur.required' => 'La couleur est obligatoire.',
            'couleur.in' => 'La couleur doit être l\'une des suivantes : vert, jaune, orange, rouge.',
            'niveau.required' => 'Le niveau est obligatoire.',
            'niveau.between' => 'Le niveau doit être compris entre 1 et 4.',
        ];
    }
}
