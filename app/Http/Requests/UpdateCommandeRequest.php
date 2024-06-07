<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommandeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'adresse' => 'sometimes|required|string|max:255',
            'numero' => 'sometimes|required|string|max:255',
            'montant' => 'sometimes|required|numeric',
            'statut' => 'sometimes|required|numeric|max:50',
            'auteurId' => 'sometimes|required|exists:users,id',
            'livreurId' => 'sometimes|nullable|exists:users,id',
        ];
    }
}
