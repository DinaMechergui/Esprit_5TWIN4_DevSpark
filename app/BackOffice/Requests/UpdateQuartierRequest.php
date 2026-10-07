<?php

namespace App\BackOffice\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuartierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $quartier = $this->route('quartier');

        return [
            'nom' => ['required', 'string', 'max:100', Rule::unique('quartiers', 'nom')->ignore($quartier)],
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['required', 'string', 'max:10'],
        ];
    }
}
