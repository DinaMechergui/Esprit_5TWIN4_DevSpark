<?php

namespace App\BackOffice\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
{
    /**
     * L'utilisateur est-il autorisé à faire cette demande ?
     */
    public function authorize(): bool
    {
        // Les routes sont déjà protégées par le middleware "admin".
        return true;
    }

    /**
     * Règles de validation du formulaire de modification d'un utilisateur.
     * Le mot de passe est facultatif (l'ancien est conservé s'il est vide).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->route('user')),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:'.User::ROLE_ADMIN.','.User::ROLE_USER],
        ];
    }
}
