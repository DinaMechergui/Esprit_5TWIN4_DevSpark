<?php

namespace App\BackOffice\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
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
     * Règles de validation du formulaire de création d'un utilisateur.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:'.User::ROLE_ADMIN.','.User::ROLE_USER],
        ];
    }
}
