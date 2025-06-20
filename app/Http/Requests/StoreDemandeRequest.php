<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'cin' => 'required|string|max:50',
            'telephone' => 'required|string|max:50',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',

            'categorie' => 'required|array',
            'categorie.*' => 'required|string|max:255',

            'sous_type' => 'required|array',
            'sous_type.*' => 'nullable|string|max:255',

            'prix_total' => 'required|numeric|min:0',
            'langue_origine' => 'required|string|max:100',
            'langue_souhaitee' => 'required|string|max:100',
            'remarque' => 'nullable|string',
        ];
    }


}
