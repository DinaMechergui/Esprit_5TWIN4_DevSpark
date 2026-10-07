<?php

namespace App\BackOffice\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuartierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100', 'unique:quartiers,nom'],
            'ville' => ['required', 'string', 'max:100'],
            'code_postal' => ['required', 'string', 'max:10'],
        ];
    }
}
