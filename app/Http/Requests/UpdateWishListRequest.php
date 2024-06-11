<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWishListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Vous pouvez ajouter des vérifications d'autorisation ici
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'produit_id' => 'required|exists:produits,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'L\'ID de l\'utilisateur est requis.',
            'user_id.exists' => 'L\'ID de l\'utilisateur doit exister dans la table users.',
            'produit_id.required' => 'L\'ID du produit est requis.',
            'produit_id.exists' => 'L\'ID du produit doit exister dans la table produits.',
        ];
    }
}
