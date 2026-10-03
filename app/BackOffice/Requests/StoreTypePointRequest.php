<?php

namespace App\BackOffice\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation de la création d'un type de point de fraîcheur.
 */
class StoreTypePointRequest extends FormRequest
{
    /**
     * Les routes sont déjà protégées par le middleware « admin ».
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100', 'unique:type_points,nom'],
            'description' => ['nullable', 'string', 'max:255'],
            'icone' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Messages d'erreur en français.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du type est obligatoire.',
            'nom.string' => 'Le nom du type doit être une chaîne de caractères.',
            'nom.max' => 'Le nom du type ne doit pas dépasser 100 caractères.',
            'nom.unique' => 'Ce nom de type de point existe déjà.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne doit pas dépasser 255 caractères.',
            'icone.string' => 'L\'icône doit être une chaîne de caractères.',
            'icone.max' => 'L\'icône ne doit pas dépasser 50 caractères.',
        ];
    }
}
